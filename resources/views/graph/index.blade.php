<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Graph') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Equity Curve Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Equity Curve Over Time</h3>
                        <x-button onclick="downloadSampleData()">
                            Download Sample Data
                        </x-button>
                    </div>

                    <!-- Chart Canvas -->
                    <div class="w-full" style="height: 400px;">
                        <canvas id="equityCurveChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Financial Metrics Grid -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Performance Metrics</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="shadow-xl border p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Annual Return</h4>
                            <p class="text-2xl font-bold {{ $metrics['annual_return'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($metrics['annual_return'] * 100, 2) }}%
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Mean PnL × 365</p>
                        </div>
                        <div class="shadow-xl border p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Sharpe Ratio</h4>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ number_format($metrics['sharpe_ratio'], 4) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">(Mean / StdDev) × √365</p>
                        </div>
                        <div class="shadow-xl border p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Maximum Drawdown</h4>
                            <p class="text-2xl font-bold text-red-600">
                                {{ number_format($metrics['max_drawdown'] * 100, 2) }}%
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Max of DD</p>
                        </div>
                        <div class="shadow-xl border p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Calmar Ratio</h4>
                            <p class="text-2xl font-bold text-purple-600">
                                {{ number_format($metrics['calmar_ratio'], 4) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Annual Return / |Max DD|</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        function downloadSampleData() {
            window.open('{{ asset("sample_data.csv") }}', "_blank")
        }

        // Equity Curve Data from Laravel
        const equityData = @json($equityData);

        // Create the Equity Curve Chart
        const ctx = document.getElementById('equityCurveChart').getContext('2d');
        const equityCurveChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: equityData.dates,
                datasets: [{
                    label: 'Equity',
                    data: equityData.equity,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.1,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                    pointHoverBorderColor: 'white',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    title: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y.toFixed(4);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 20
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Equity Value',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(2);
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
