<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TraitCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Trait File';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $basefile = __DIR__ . '/BaseFiles/BaseTrait.php';
        $destfile = __DIR__ . '/../../Traits/'.$name.'.php';

        $alreadyexits = file_exists($destfile);
        if($alreadyexits){
            return $this->error('file already exists');
        }

        copy($basefile, $destfile);
        $basecontent = file_get_contents($basefile);
        $changedcontent = str_replace(['base_namespace','base_name'], ['App\Traits\\'. $name, $name],$basecontent);
        file_put_contents($destfile, $changedcontent);

        return $this->info('trait created');
    }
}
