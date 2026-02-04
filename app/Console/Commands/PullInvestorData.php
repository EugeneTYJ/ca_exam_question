<?php

namespace App\Console\Commands;

use App\Models\Fund;
use App\Models\Investment;
use App\Models\Investor;
use App\Services\CardinalAlphaApiService;
use Illuminate\Console\Command;

class PullInvestorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:pull {--type=all : Type of data to pull (investors, funds, investments, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull data from Cardinal Alpha API and store in database';

    protected CardinalAlphaApiService $apiService;

    public function __construct(CardinalAlphaApiService $apiService)
    {
        parent::__construct();
        $this->apiService = $apiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        $this->info('Starting data pull from Cardinal Alpha API...');

        try {
            match ($type) {
                'investors' => $this->pullInvestors(),
                'funds' => $this->pullFunds(),
                'investments' => $this->pullInvestments(),
                'all' => $this->pullAll(),
                default => $this->error('Invalid type. Use: investors, funds, investments, or all'),
            };

            $this->info('Data pull completed successfully!');
        } catch (\Exception $e) {
            $this->error('Error pulling data: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function pullAll()
    {
        $this->pullFunds();
        $this->pullInvestors();
        $this->pullInvestments();
    }

    protected function pullInvestors()
    {
        $this->info('Pulling investors...');
        $investors = $this->apiService->getInvestors();

        $bar = $this->output->createProgressBar(count($investors));
        $bar->start();

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($investors as $investorData) {
            try {
                $investor = Investor::updateOrCreate(
                    ['api_id' => $investorData['id']],
                    [
                        'name' => $investorData['name'],
                        'email' => $investorData['email'],
                        'contact_number' => $investorData['contact_number'],
                    ]
                );

                if ($investor->wasRecentlyCreated) {
                    $created++;
                } else {
                    $updated++;
                }
            } catch (\Exception $e) {
                $skipped++;
                $this->warn("\nSkipping investor {$investorData['name']} (ID: {$investorData['id']}): " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Investors - Created: {$created}, Updated: {$updated}, Skipped: {$skipped}");
    }

    protected function pullFunds()
    {
        $this->info('Pulling funds...');
        $funds = $this->apiService->getFunds();

        $bar = $this->output->createProgressBar(count($funds));
        $bar->start();

        foreach ($funds as $fundData) {
            Fund::updateOrCreate(
                ['api_id' => $fundData['id']],
                [
                    'name' => $fundData['name'],
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Pulled ' . count($funds) . ' funds.');
    }

    protected function pullInvestments()
    {
        $this->info('Pulling investments...');
        $investments = $this->apiService->getInvestments();

        $bar = $this->output->createProgressBar(count($investments));
        $bar->start();

        foreach ($investments as $investmentData) {
            // Find the local investor and fund by their API IDs
            $investor = Investor::where('api_id', $investmentData['investor']['id'])->first();
            $fund = Fund::where('api_id', $investmentData['fund']['id'])->first();

            if (!$investor || !$fund) {
                $this->warn("Skipping investment {$investmentData['uid']} - investor or fund not found");
                continue;
            }

            Investment::updateOrCreate(
                ['api_id' => $investmentData['id']],
                [
                    'uid' => $investmentData['uid'],
                    'investor_id' => $investor->id,
                    'fund_id' => $fund->id,
                    'start_date' => $investmentData['start_date'],
                    'capital_amount' => $investmentData['capital_amount'],
                    'status' => $investmentData['status'],
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Pulled ' . count($investments) . ' investments.');
    }
}
