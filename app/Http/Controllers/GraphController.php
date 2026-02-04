<?php

namespace App\Http\Controllers;

class GraphController extends Controller
{
    public function index()
    {
        // Read and parse the CSV file
        $csvPath = public_path('sample_data.csv');
        $equityData = $this->parseEquityData($csvPath);

        // Calculate financial metrics
        $metrics = $this->calculateMetrics($equityData);

        return view('graph.index', compact('equityData', 'metrics'));
    }

    /**
     * Calculate financial metrics
     */
    private function calculateMetrics($equityData)
    {
        $pnl = $equityData['pnl'];
        $drawdown = $equityData['drawdown'];

        $meanPnl = $this->calculateMean($pnl);
        $annualReturn = $meanPnl * 365;

        $stdDevPnl = $this->calculateStdDev($pnl, $meanPnl);
        $sharpeRatio = $stdDevPnl > 0 ? ($meanPnl / $stdDevPnl) * sqrt(365) : 0;

        $maxDrawdown = max($drawdown);

        $calmarRatio = $maxDrawdown > 0 ? $annualReturn / abs($maxDrawdown) : 0;

        return [
            'annual_return' => $annualReturn,
            'sharpe_ratio' => $sharpeRatio,
            'max_drawdown' => $maxDrawdown,
            'calmar_ratio' => $calmarRatio
        ];
    }

    /**
     * Calculate mean (average) of an array
     */
    private function calculateMean($values)
    {
        $count = count($values);
        return $count > 0 ? array_sum($values) / $count : 0;
    }

    /**
     * Calculate standard deviation
     */
    private function calculateStdDev($values, $mean)
    {
        $count = count($values);

        if ($count <= 1) {
            return 0;
        }

        $variance = 0;
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }

        $variance = $variance / $count;

        return sqrt($variance);
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
