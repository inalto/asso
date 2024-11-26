<?php namespace MartiniMultimedia\Asso\Console;

use Illuminate\Console\Command;
use MartiniMultimedia\Asso\Models\Company;
use MartiniMultimedia\Asso\Models\AtecoCompany;

class SyncCompanyAteco extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asso:sync-ateco';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync company and ateco data into ateco_company join table';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            // Check if the record already exists in company_ateco
            $existingRecord = AtecoCompany::where('company_id', $company->id)->first();

            if (!$existingRecord) {
                // Create a new record if it doesn't exist
                $newRecord = new AtecoCompany();
                $newRecord->company_id = $company->id;
                $newRecord->ateco_id = $company->ateco_id; // Assuming ateco_id is present in the Company model
                $newRecord->save();

                $this->info("Inserted: company_id = {$company->id}, ateco_id = {$company->ateco_id}");
            } else {
                $this->info("Skipped (already exists): company_id = {$company->id}, ateco_id = {$company->ateco_id}");
            }
        }

        return Command::SUCCESS;
    }
}
