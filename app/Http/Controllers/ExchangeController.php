<?php

namespace App\Http\Controllers;

use App\Http\Services\OperationService;
use App\Models\Operation;
use Illuminate\Http\Request;
use League\Csv\Reader;

class ExchangeController extends Controller
{
    public $operationService;

    public function __construct(OperationService $operationService)
    {
        $this->operationService = $operationService;
    }


    public function show()
    {
        $report = Operation::all();
        return view('dashboard', compact('report'));
    }


    public function index(Request $request)
    {

        $filename = storage_path('app/public/' . $request->input('fileName'));

        $csv = Reader::createFromPath($filename);

        if (file_exists($filename)) {
            foreach ($csv as $line) {

                if (empty($line)) {
                    continue;
                }
                if (!in_array($line[5], ['USD', 'EUR', 'JPY'])) {
                    continue;
                }
                $this->operationService->operationStore($line);
            }
            return redirect()->route('show');
        } else {
            return back()->with(['error' => 'Csv file not found']);
        }


    }

}
