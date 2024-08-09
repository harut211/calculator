<?php

namespace App\Console\Commands;

use App\Http\Services\OperationService;
use Illuminate\Console\Command;
use League\Csv\Reader;
use function Laravel\Prompts\text;

class CalculateCommand extends Command
{

    protected $operationService;

    public function __construct(OperationService $operationService)
    {
        parent::__construct();
        $this->operationService = $operationService;
    }


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Work witch csv file';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $filename = text(
            label: 'Write your file name(type file.csv)',
            required: true
        );

        $filename = storage_path('app/public/csv-files/' . $filename);


        if (file_exists($filename)) {
            $header = [  'date' ,
                'user_id',
                'user_type' ,
                'operation_type' ,
                'amount',
                'currency'];
            $csv = Reader::createFromPath($filename);
            $csv->setHeaderOffset(null);
            $records = $csv->getRecords();

            $collection = collect($records)->map(function ($record) use ($header) {
                return array_combine($header, $record);
            });

            $calculator = new OperationService($collection);
            $commissionFees = $calculator->calculate();

            foreach ($commissionFees as $com) {
               echo  "user id- "  . $com['user_id'] ." " . "fee: " . $com['commission'] . PHP_EOL;
            }

        } else {
            echo 'Csv file not found';
        }
    }
}
