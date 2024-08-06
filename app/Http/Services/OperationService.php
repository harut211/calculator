<?php

namespace App\Http\Services;


use App\Models\Operation;

class OperationService
{

    public function operationStore($line)
    {
        $operation = Operation::updateOrCreate([
            'operation_date' => $line[0],
            'user_id' => $line[1],
            'user_type' => $line[2],
            'operation_type' => $line[3],
            'amount' => (float)$line[4],
            'currency' => $line[5],
        ]);
        $commission = $this->calculateCommission($operation);
        $operation->commission_fee = $commission;
        $operation->save();
        return true;
    }

    public function calculateCommission(Operation $operation)
    {
        $commission = 0;

        if ($operation->operation_type === 'deposit') {
            $commission = $operation->amount * 0.0003;
        } else {
            if ($operation->user_type === 'private') {
                $freeWithdraw = 3;
                $weeklyAmount = 1000;

                $weeklyData = Operation::
                where('user_id', $operation->user_id)
                    ->where('operation_date', '>=', date('Y-m-d', strtotime('last monday')))
                    ->where('operation_date', '<=', date('Y-m-d'))
                    ->where('operation_type', 'withdraw')
                    ->get();

               $weeklyInfo =  checkWeeklyData($weeklyData);

                if ( $weeklyInfo['count'] < $freeWithdraw &&  $weeklyInfo['money'] < $weeklyAmount) {
                    $commission = 0;
                } else {
                    $commission = $operation->amount * 0.003;
                }

            } else {
                $commission = $operation->amount * 0.005;
            }
        }
        return number_format($commission, 2, '.', '');

    }

}

