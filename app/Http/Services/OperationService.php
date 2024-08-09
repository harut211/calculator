<?php

namespace App\Http\Services;


use Carbon\Carbon;
use Illuminate\Support\Collection;

class OperationService
{


    protected $operations;
    protected $freeLimit = 1000.00;
    protected $privateCommission = 0.003;
    protected $businessCommission = 0.005;
    protected $depositCommission = 0.0003;

    protected $conversionRates = [
        'JPY' => 0.0062,
        'USD' => 0.9200,
        'EUR' => 1.0000,
    ];

    public function __construct(Collection $operations)
    {
        $this->operations = $operations;
    }

    public function calculate(): array
    {
        $commissionFees = [];
        $groupedByWeek = $this->operations->groupBy(function ($operation) {
            return Carbon::parse($operation['date'])->startOfWeek()->format('Y-W');
        });

        foreach ($groupedByWeek as $operations) {
            $weeklyTotals = [];
            $operationCounts = [];

            foreach ($operations as $operation) {
                $userId = $operation['user_id'];
                $operationAmount = (float)$operation['amount'];
                $currency = $operation['currency'];
                $commission = 0;

                $amountInEur = $this->convertToEur($operationAmount, $currency);

                if ($operation['operation_type'] === 'deposit') {
                    $commission = $this->calculateCommission($amountInEur, $this->depositCommission);
                } else if ($operation['user_type'] === 'private') {
                    if (!isset($weeklyTotals[$userId])) {
                        $weeklyTotals[$userId] = 0;
                        $operationCounts[$userId] = 0;
                    }
                    if ($operation['operation_type'] === 'withdraw') {
                        if ($operationCounts[$userId] <= 3) {
                            if ($weeklyTotals[$userId] + $amountInEur <= $this->freeLimit) {
                                $commission = 0;
                            } else {
                                $usedAmount = $weeklyTotals[$userId] + $amountInEur - $this->freeLimit;
                                $commission = $this->calculateCommission($usedAmount, $this->privateCommission);
                            }
                        } else {
                            $commission = $this->calculateCommission($amountInEur, $this->privateCommission);
                        }
                        $weeklyTotals[$userId] += $amountInEur;
                        $operationCounts[$userId]++;
                    }
                } else if ($operation['user_type'] === 'business') {
                    if ($operation['operation_type'] === 'withdraw') {
                        $commission = $this->calculateCommission($amountInEur, $this->businessCommission);
                    }
                }
                $commissionFees[] = [
                    'date' => $operation['date'],
                    'user_id' => $operation['user_id'],
                    'operation_type' => $operation['operation_type'],
                    'amount' => $operationAmount,
                    'currency' => $currency,
                    'commission' => $commission
                ];
            }
        }
        return $commissionFees;
    }

    protected function calculateCommission($amount, $rate): float
    {
        $commission = $amount * $rate;

        $commission = round($commission, 2);

        return $commission;
    }

    protected function convertToEur($amount, $currency): float
    {
        $rate = $this->conversionRates[$currency] ?? 1;
        return $amount * $rate;
    }

}

