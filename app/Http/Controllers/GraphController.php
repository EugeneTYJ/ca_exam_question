<?php

namespace App\Http\Controllers;

class GraphController extends Controller
{
    public function index()
    {
        /*
         *  Todo: calculate Sharpe Ratio, Calmar Ratio, MDD, Annual Return here
         *  Todo: Make sure the next page has a graph
         *
         */

        // Read and parse the CSV file
        $csvPath = public_path('sample_data.csv');
        $equityData = $this->parseEquityData($csvPath);

        return view('graph.index', compact('equityData'));
    }

    /**
     * Parse the equity data from CSV file
     */
    private function parseEquityData($filePath)
    {
        $data = [
            'dates' => [],
            'equity' => [],
            'pnl' => [],
            'drawdown' => []
        ];

        if (!file_exists($filePath)) {
            return $data;
        }

        $file = fopen($filePath, 'r');

        // Skip header row
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) >= 4) {
                $data['dates'][] = $row[0];
                $data['pnl'][] = (float) $row[1];
                $data['drawdown'][] = (float) $row[2];
                $data['equity'][] = (float) $row[3];
            }
        }

        fclose($file);

        return $data;
    }
}
