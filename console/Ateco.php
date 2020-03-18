<?php namespace MartiniMultimedia\Asso\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use MartiniMultimedia\Asso\Models\Ateco as A;
use MartiniMultimedia\Asso\Models\Person;
use Spinner\Spinner;

use Carbon\Carbon;

class Ateco extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'asso:ateco';

    /**
     * @var string The console command description.
     */
    protected $description = 'Importazione codici Ateco...';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->output->writeln('Importazione Ateco...');
        $spinner = new Spinner(\Spinner\DOTS);
        $spinner->tick('Loading file... ');
        $inputFileName = plugins_path().'/martinimultimedia/asso/console/imports/ateco-2019.xlsx';
        
        

        A::truncate();
        $spinner->tick('Database Cleared');
/** Create a new Xls Reader  **/
        $reader = new Xlsx();
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        //$highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); 
//        $main_id=1000000;
  //      $parent_id=null;
        for ($row = 2; $row <= $highestRow; ++$row) {
         
            $spinner->tick('Loading...      ');
                $code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                if(!is_numeric(trim($code))&&(strlen(trim($code))==1)) {
//                    $main_id++;
//                    $this->output->writeln("alpha");
/*
                    $ateco = new A();
                    $ateco->id=$main_id;
                    $ateco->code = str_replace(".","",$code);
                    $ateco->name = $code;
                    $ateco->description = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $ateco->save();
                    
                    $this->output->writeln("code: ".$ateco->code." id:".$ateco->id);
*/
                } else {

                    $c=explode('.',$code);

/*                    $this->output->writeln("-count: ".count($c));
                    $this->output->writeln("-code: ".$code);
                    
                    $this->output->writeln("-id:".str_replace(".","",$code)." parent_id:".$parent_id."code:".$code);
*/
                    $ateco =  A::firstOrNew(['id'=>str_replace(".","",$code)]);

                    $ateco->id = str_replace(".","",$code);
//                    $ateco->parent_id = $parent_id;
                    $ateco->code = str_replace(".","",$code);
                    $ateco->name = $code;
                    $ateco->description = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $ateco->save();
/*
                    if (count($c)==1) {

                        $parent_id= $main_id;

                    } else if (count($c)==2) {
                        $parent_id = intval($c[0].$c[1]);
                    } else if (count($c)==3) {
                        $parent_id = intval($c[0].$c[1].$c[2]);
                    }
                    $this->output->writeln("-".$ateco->code);
                    */
                }

                
         
        }   
        $this->output->writeln("      done      ");
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
