<?php namespace MartiniMultimedia\Asso\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date as D;

use MartiniMultimedia\Asso\Models\Company;
use MartiniMultimedia\Asso\Models\Person;

use Carbon\Carbon;

class ImportGestionale extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'asso:import-gestionale';

    /**
     * @var string The console command description.
     */
    protected $description = 'Import users and companies from gestionale Excel file';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $filePath = $this->option('file') ?: plugins_path().'/martinimultimedia/asso/console/imports/20250912_gestionale.xlsx';
        $dryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Starting import from: {$filePath}");
        if ($dryRun) {
            $this->warn("DRY RUN MODE - No data will be saved");
        }

        try {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $highestRow = $worksheet->getHighestRow();
            $this->info("Found {$highestRow} rows to process");

            // Expected columns mapping
            $columnMapping = [
                'A' => 'nome',
                'B' => 'cognome', 
                'C' => 'codice_fiscale',
                'D' => 'mansione',
                'E' => 'azienda',
                'F' => 'mail_generica',
                'G' => 'mail_personale',
                'H' => 'mail_corsi',
                'I' => 'mail_contabilita',
                'J' => 'pec',
                'K' => 'tel_fisso',
                'L' => 'tel_cellulare',
                'M' => 'tel_privato',
                'N' => 'fax'
            ];

            // Verify headers
            $this->info("Verifying column headers...");
            $expectedHeaders = ['NOME', 'COGNOME', 'CODICE FISCALE', 'MANSIONE', 'AZIENDA', 'MAIL generica', 'MAIL personale', 'MAIL corsi', 'MAIL contabilità', 'PEC', 'TEL fisso', 'TEL cellulare', 'TEL privato', 'FAX'];
            
            for ($col = 0; $col < count($expectedHeaders); $col++) {
                $columnLetter = chr(65 + $col); // A, B, C, etc.
                $headerValue = $worksheet->getCell($columnLetter . '1')->getValue();
                if (trim($headerValue) !== $expectedHeaders[$col]) {
                    $this->warn("Header mismatch in column {$columnLetter}: expected '{$expectedHeaders[$col]}', found '{$headerValue}'");
                }
            }

            $stats = [
                'processed' => 0,
                'companies_created' => 0,
                'companies_updated' => 0,
                'people_created' => 0,
                'people_updated' => 0,
                'errors' => 0
            ];

            $this->output->progressStart($highestRow - 1);

            // Process each row (skip header row)
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    $rowData = $this->extractRowData($worksheet, $row, $columnMapping);
                    
                    if (empty($rowData['nome']) && empty($rowData['cognome']) && empty($rowData['azienda'])) {
                        // Skip empty rows
                        continue;
                    }

                    $stats['processed']++;

                    // Process company first
                    $company = null;
                    if (!empty($rowData['azienda'])) {
                        $companyResult = $this->processCompany($rowData, $dryRun);
                        $company = $companyResult['company'];
                        if ($companyResult['created']) {
                            $stats['companies_created']++;
                        } elseif ($companyResult['updated']) {
                            $stats['companies_updated']++;
                        }
                    }

                    // Process person
                    if (!empty($rowData['nome']) || !empty($rowData['cognome'])) {
                        $personResult = $this->processPerson($rowData, $company, $dryRun);
                        if ($personResult['created']) {
                            $stats['people_created']++;
                        } elseif ($personResult['updated']) {
                            $stats['people_updated']++;
                        }
                    }

                } catch (\Exception $e) {
                    $this->error("Error processing row {$row}: " . $e->getMessage());
                    $this->error("Row data: " . json_encode($rowData));
                    $stats['errors']++;
                }

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();

            // Display summary
            $this->info("\nImport Summary:");
            $this->table(['Metric', 'Count'], [
                ['Rows processed', $stats['processed']],
                ['Companies created', $stats['companies_created']],
                ['Companies updated', $stats['companies_updated']],
                ['People created', $stats['people_created']],
                ['People updated', $stats['people_updated']],
                ['Errors', $stats['errors']]
            ]);

            if ($dryRun) {
                $this->warn("DRY RUN completed - No data was actually saved");
            } else {
                $this->info("Import completed successfully!");
            }

        } catch (\Exception $e) {
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Extract data from a spreadsheet row
     */
    private function extractRowData($worksheet, $row, $columnMapping)
    {
        $data = [];
        foreach ($columnMapping as $column => $field) {
            $cellValue = $worksheet->getCell($column . $row)->getValue();
            $data[$field] = $cellValue ? trim($cellValue) : null;
        }
        
        // Apply proper case formatting to names and company
        if (!empty($data['nome'])) {
            $data['nome'] = $this->formatProperCase($data['nome']);
        }
        if (!empty($data['cognome'])) {
            $data['cognome'] = $this->formatProperCase($data['cognome']);
        }
        if (!empty($data['azienda'])) {
            $data['azienda'] = $this->formatCompanyName($data['azienda']);
        }
        if (!empty($data['mansione'])) {
            $data['mansione'] = $this->formatProperCase($data['mansione']);
        }
        
        return $data;
    }

    /**
     * Process company data - create or update
     */
    private function processCompany($rowData, $dryRun = false)
    {
        $companyName = $rowData['azienda'];
        
        // Try to find existing company by name
        $company = Company::where('name', 'LIKE', "%{$companyName}%")->first();
        
        $emails = [];
        $phones = [];
        
        // Collect emails
        if (!empty($rowData['mail_generica'])) $emails[] = $rowData['mail_generica'];
        if (!empty($rowData['mail_contabilita'])) $emails[] = $rowData['mail_contabilita'];
        
        // Collect phones and fax
        if (!empty($rowData['tel_fisso'])) $phones[] = $rowData['tel_fisso'];
        if (!empty($rowData['fax'])) $phones[] = $rowData['fax'];
        
        $companyData = [
            'name' => $companyName,
            'emails' => !empty($emails) ? array_unique($emails) : null,
            'phones' => !empty($phones) ? array_unique($phones) : null,
            'pec' => $rowData['pec'] ?: null,
        ];

        $created = false;
        $updated = false;

        if ($company) {
            // Update existing company
            if (!$dryRun) {
                // Merge emails and phones with existing data
                $existingEmails = $this->decodeJsonField($company->emails);
                $existingPhones = $this->decodeJsonField($company->phones);
                
                $mergedEmails = array_unique(array_merge($existingEmails, $emails));
                $mergedPhones = array_unique(array_merge($existingPhones, $phones));
                
                $company->emails = !empty($mergedEmails) ? $mergedEmails : $company->emails;
                $company->phones = !empty($mergedPhones) ? $mergedPhones : $company->phones;
                
                if (!empty($rowData['pec']) && empty($company->pec)) {
                    $company->pec = $rowData['pec'];
                }
                
                $company->save();
            }
            $updated = true;
            $this->line("Updated company: {$companyName}");
        } else {
            // Create new company
            if (!$dryRun) {
                try {
                    $company = Company::create($companyData);
                } catch (\Exception $e) {
                    $this->error("Error creating company '{$companyName}': " . $e->getMessage());
                    $this->error("Company data: " . json_encode($companyData));
                    throw $e;
                }
            }
            $created = true;
            $this->line("Created company: {$companyName}");
        }

        return [
            'company' => $company,
            'created' => $created,
            'updated' => $updated
        ];
    }

    /**
     * Process person data - create or update
     */
    private function processPerson($rowData, $company, $dryRun = false)
    {
        $firstName = $rowData['nome'];
        $lastName = $rowData['cognome'];
        $codiceFiscale = $rowData['codice_fiscale'];
        
        // Try to find existing person by codice fiscale first, then by name
        $person = null;
        if (!empty($codiceFiscale)) {
            $person = Person::where('cf', $codiceFiscale)->first();
        }
        
        if (!$person && !empty($firstName) && !empty($lastName)) {
            $person = Person::where('first_name', $firstName)
                           ->where('last_name', $lastName)
                           ->first();
        }

        $emails = [];
        $phones = [];
        
        // Collect emails
        if (!empty($rowData['mail_personale'])) $emails[] = $rowData['mail_personale'];
        if (!empty($rowData['mail_corsi'])) $emails[] = $rowData['mail_corsi'];
        
        // Collect phones
        if (!empty($rowData['tel_cellulare'])) $phones[] = $rowData['tel_cellulare'];
        if (!empty($rowData['tel_privato'])) $phones[] = $rowData['tel_privato'];
        
        $personData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'cf' => $codiceFiscale,
            'task' => $rowData['mansione'],
            'emails' => !empty($emails) ? array_unique($emails) : null,
            'phones' => !empty($phones) ? array_unique($phones) : null,
            'company_id' => $company ? $company->id : null,
        ];

        $created = false;
        $updated = false;

        if ($person) {
            // Update existing person
            if (!$dryRun) {
                // Merge emails and phones with existing data
                $existingEmails = $this->decodeJsonField($person->emails);
                $existingPhones = $this->decodeJsonField($person->phones);
                
                $mergedEmails = array_unique(array_merge($existingEmails, $emails));
                $mergedPhones = array_unique(array_merge($existingPhones, $phones));
                
                $person->emails = !empty($mergedEmails) ? $mergedEmails : $person->emails;
                $person->phones = !empty($mergedPhones) ? $mergedPhones : $person->phones;
                
                // Update other fields if they're empty
                if (!empty($rowData['mansione']) && empty($person->task)) {
                    $person->task = $rowData['mansione'];
                }
                
                if ($company && empty($person->company_id)) {
                    $person->company_id = $company->id;
                }
                
                if (!empty($codiceFiscale) && empty($person->cf)) {
                    $person->cf = $codiceFiscale;
                }
                
                $person->save();
            }
            $updated = true;
            $this->line("Updated person: {$firstName} {$lastName}");
        } else {
            // Create new person
            if (!$dryRun) {
                try {
                    $person = Person::create($personData);
                } catch (\Exception $e) {
                    $this->error("Error creating person '{$firstName} {$lastName}': " . $e->getMessage());
                    $this->error("Person data: " . json_encode($personData));
                    throw $e;
                }
            }
            $created = true;
            $this->line("Created person: {$firstName} {$lastName}");
        }

        return [
            'person' => $person,
            'created' => $created,
            'updated' => $updated
        ];
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions()
    {
        return [
            ['file', 'f', InputOption::VALUE_OPTIONAL, 'Path to the Excel file to import'],
            ['dry-run', null, InputOption::VALUE_NONE, 'Run without making any changes to the database'],
        ];
    }

    /**
     * Format text to proper case (Title Case) for names
     */
    private function formatProperCase($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Convert to lowercase first
        $text = mb_strtolower($text, 'UTF-8');
        
        // List of words that should remain lowercase (except at the beginning)
        $lowercase_words = ['di', 'da', 'del', 'della', 'dei', 'delle', 'dall', 'dalle', 'dal', 'd', 'de', 'la', 'le', 'lo', 'gli', 'il', 'in', 'con', 'per', 'su', 'tra', 'fra', 'a', 'e', 'o', 'ma', 'se', 'che'];
        
        // Split by spaces and capitalize each word
        $words = explode(' ', $text);
        $formatted_words = [];
        
        foreach ($words as $index => $word) {
            // Always capitalize the first word
            if ($index === 0) {
                $formatted_words[] = mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
            } else {
                // Check if word should remain lowercase
                if (in_array($word, $lowercase_words)) {
                    $formatted_words[] = $word;
                } else {
                    $formatted_words[] = mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
                }
            }
        }
        
        return implode(' ', $formatted_words);
    }

    /**
     * Format company names with proper case
     */
    private function formatCompanyName($text)
    {
        if (empty($text)) {
            return $text;
        }

        // List of business terms that should remain uppercase
        $uppercase_terms = ['SRL', 'SPA', 'SNC', 'SAS', 'SRLS', 'COOP', 'SOC', 'SOCIETÀ', 'SOCIETA', 'COOPERATIVA', 'S.R.L.', 'S.P.A.', 'S.N.C.', 'S.A.S.', 'S.R.L.S.', 'LTDA', 'LLC', 'INC', 'CORP', 'LTD', 'GMBH', 'AG', 'SA', 'BV', 'NV', 'AB', 'AS', 'OY', 'OYJ', 'KG', 'CO', 'E C.', '& C.', 'F.LLI', 'FRATELLI'];
        
        // List of words that should remain lowercase (except at the beginning)  
        $lowercase_words = ['di', 'da', 'del', 'della', 'dei', 'delle', 'dall', 'dalle', 'dal', 'd', 'de', 'la', 'le', 'lo', 'gli', 'il', 'in', 'con', 'per', 'su', 'tra', 'fra', 'a', 'e', 'o', 'ma', 'se', 'che', 'al', 'alla', 'alle', 'allo', 'agli'];

        // Convert to lowercase first
        $text = mb_strtolower($text, 'UTF-8');
        
        // Split by spaces and process each word
        $words = explode(' ', $text);
        $formatted_words = [];
        
        foreach ($words as $index => $word) {
            $word_upper = mb_strtoupper($word, 'UTF-8');
            $word_clean = preg_replace('/[^\p{L}\p{N}]/u', '', $word_upper); // Remove punctuation for comparison
            
            // Always capitalize the first word
            if ($index === 0) {
                // Check if it's a business term
                if (in_array($word_clean, $uppercase_terms) || in_array($word_upper, $uppercase_terms)) {
                    $formatted_words[] = $word_upper;
                } else {
                    $formatted_words[] = mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
                }
            } else {
                // Check if it's a business term (keep uppercase)
                if (in_array($word_clean, $uppercase_terms) || in_array($word_upper, $uppercase_terms)) {
                    $formatted_words[] = $word_upper;
                }
                // Check if word should remain lowercase
                else if (in_array($word, $lowercase_words)) {
                    $formatted_words[] = $word;
                } else {
                    $formatted_words[] = mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
                }
            }
        }
        
        return implode(' ', $formatted_words);
    }

    /**
     * Safely decode JSON field that might be string or array
     */
    private function decodeJsonField($field)
    {
        if (empty($field)) {
            return [];
        }
        
        // If it's already an array, return it
        if (is_array($field)) {
            return $field;
        }
        
        // If it's a string, try to decode it
        if (is_string($field)) {
            $decoded = json_decode($field, true);
            return $decoded ? $decoded : [];
        }
        
        return [];
    }
}