<?php namespace MartiniMultimedia\Asso\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date as D;

use MartiniMultimedia\Asso\Models\Company;
use MartiniMultimedia\Asso\Models\Person;
use Spinner\Spinner;

use Carbon\Carbon;

class Import extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'asso:import';

    /**
     * @var string The console command description.
     */
    protected $description = 'Importazione dati...';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $spinner = new Spinner(\Spinner\DOTS);

        $this->output->writeln('Importazione Aziende e Personale');
        $spinner = new Spinner(\Spinner\DOTS);
        $spinner->tick('Loading file... ');
        $inputFileName = plugins_path().'/martinimultimedia/asso/console/imports/ELENCO UNICO V3.xlsx';
        
        
        $spinner->tick('Clear db...     ');
        Company::truncate();
        Person::truncate();
        
/** Create a new Xls Reader  **/
        $reader = new Xlsx();
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); 
        for ($row = 2; $row <= $highestRow; ++$row) {
            //for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $spinner->tick('Seeding db...   ');
                $company= Company::firstOrNew(['name'=>$this->strtotitle($worksheet->getCellByColumnAndRow(1, $row)->getValue())]);

                $company->name = $this->strtotitle($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                $company->address = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $company->zip = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $company->city = $this->strtotitle($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $company->state = $worksheet->getCellByColumnAndRow(5, $row)->getValue();


                $emails=$this->getEmailsArrayFromString($worksheet->getCellByColumnAndRow(16, $row)->getValue()." ".$worksheet->getCellByColumnAndRow(17, $row)->getValue());
                $i=0;
                $e=[];
                foreach ($emails as $email) {
                    $e[$i]['email']=$email;
                    $i++;
                }

                //$company->emails=json_encode($e);
                $company->emails=$e;

                $phones=$this->getPhonesArrayFromString($worksheet->getCellByColumnAndRow(16, $row)->getValue()." ".$worksheet->getCellByColumnAndRow(17, $row)->getValue());

                $i=0;
                $p=[];
                foreach ($phones as $phone) {
                    $p[$i]['number']=$phone;
                    $p[$i]['rif']="";
                    $i++;
                }

             //   $company->emails=json_encode($p);
                $company->phones=$p;
                
                $vat_cf=[];
                $vat_cf=explode('-',$worksheet->getCellByColumnAndRow(6, $row)->getValue());
                
                $company->vat = $vat_cf[0];
                if (count($vat_cf)==2) {
                    $company->cf = $vat_cf[1];
                }
                $company->sdi = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                
                $atecos = explode('-',$worksheet->getCellByColumnAndRow(8, $row)->getValue());
                //$this->output->writeln($atecos[0]);
                $company->ateco_id = intval($atecos[0]);
                $company->save();

                $person = Person::firstOrNew([
                    'first_name' => $worksheet->getCellByColumnAndRow(9, $row)->getValue(),
                    'last_name' => $worksheet->getCellByColumnAndRow(10, $row)->getValue()
                    ]);
                $person->first_name = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $person->last_name = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $person->birth_city = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                $person->emails=$e;
                $person->phones=$p;
                //$birth_date=new Carbon(trim(" ",$worksheet->getCellByColumnAndRow(12, $row)->getValue()));
                //$person->birth_date = Carbon::createFromFormat('d-m-Y h:i',strtotime($worksheet->getCellByColumnAndRow(12, $row)->getValue()));
                
                
                //if (!empty($worksheet->getCellByColumnAndRow(12, $row)->getValue())) {
            //    $this->birth_date = Date::excelToDateTimeObject($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                //}
                /*
                if (!empty($worksheet->getCellByColumnAndRow(12, $row)->getValue())) {
                    $person->birth_date=date('Y-m-d',strtotime($worksheet->getCellByColumnAndRow(12, $row)->getValue()));
                    $this->output->writeln($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                }
                */
        
                if (D::isDateTime($worksheet->getCellByColumnAndRow(12, $row))) {
                //$person->birth_date=date('Y-m-d',strtotime($worksheet->getCellByColumnAndRow(12, $row)->getFormattedValue()));
                $person->birth_date=date('Y-m-d',D::excelToTimestamp($worksheet->getCellByColumnAndRow(12, $row)->getValue()));
                
                //$this->output->writeln(D::excelToTimestamp($worksheet->getCellByColumnAndRow(12, $row)->getValue()));
                }
                $person->cf = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                $person->task = $this->strtotitle($worksheet->getCellByColumnAndRow(14, $row)->getValue());

                if(strtolower($worksheet->getCellByColumnAndRow(15, $row)->getValue()) == "si") {
                    $person->cna=true;
                } else {
                    $person->cna=false;
                }
                if((strtolower($worksheet->getCellByColumnAndRow(15, $row)->getValue()) != "si") && (strtolower($worksheet->getCellByColumnAndRow(15, $row)->getValue() != "no"))) {
                    $person->other=$worksheet->getCellByColumnAndRow(15, $row)->getValue();
                 }

                                
             //   $person->emails = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
           //     $person->phones = $worksheet->getCellByColumnAndRow(17, $row)->getValue();

                $person->company_id=$company->id;
                $person->save();
                /*
                $company->firstOrCreate(
                    ['name'=>$this->strtotitle($worksheet->getCellByColumnAndRow(1, $row)->getValue())],
                    [
                        
                    ]
                );
                */
                //\Carbon\Carbon::createFromFormat('d-m-Y h:i', $value);
                //echo $value."\n";

                //$this->output->writeln($this->strtotitle($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
            //}
        }   
        $this->output->writeln("      done      ");

    }

    public function strtotitle($t) {
        $words=explode(' ',$t);
        $s=['il','lo','la','i','gli','le', 'di','a','e','da','in','con','su','per','tra','fra','s.r.l.','s.n.c.','s.p.a.','s.a.s.'];
        $a=['srl','snc','sas','spa'];

        
        foreach ($words as $key => $word) {
            $lword=strtolower($word);
            if ($key == 0 or !in_array($lword, $s)) {
                $words[$key] = ucwords($lword);
                
            } else {
                $words[$key]= strtolower($lword);
            }

            if (in_array($lword, $a)) {
                $words[$key]=implode('.',str_split($lword)).".";
            }
        } 

        return implode(' ', $words); 
    }

    public function getEmailsArrayFromString($sString = '')
    {
        $sPattern = '/[\._\p{L}\p{M}\p{N}-]+@[\._\p{L}\p{M}\p{N}-]+/u';
        preg_match_all($sPattern, $sString, $aMatch);
        $aMatch = array_keys(array_flip(current($aMatch)));
    
        return $aMatch;
    }

    public function getPhonesArrayFromString($sString = '')
    {
        $sPattern = '/\b[0-9]{3}\s*[-]?\s*[0-9]{3}\s*[-]?\s*[0-9]{4}\b/';
        preg_match_all($sPattern, $sString, $aMatch);
        $aMatch = array_keys(array_flip(current($aMatch)));
    
        return $aMatch;
    }
    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
