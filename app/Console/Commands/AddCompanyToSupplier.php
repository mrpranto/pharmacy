<?php

namespace App\Console\Commands;

use App\Models\People\Supplier;
use App\Models\Product\Company;
use Illuminate\Console\Command;

class AddCompanyToSupplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-company-to-supplier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Company::query()->chunk(100, function ($companies) {
           $companies->each(function ($company) {
                Supplier::query()->updateOrCreate([
                   'name' => $company->name,
                ]);
           });
        });

        $this->info('Company added to supplier');
    }
}
