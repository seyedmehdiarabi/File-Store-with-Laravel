<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Config;
use File;
class publishAsset extends Command
{
    protected $modules=[];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publishAsset {--module=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish asset modules';

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
     * @return mixed
     */
    public function handle()
    {
        $option=$this->option('module');
        $empty=true;
        $this->modules=Config::get('app.modules');
        foreach ($this->modules as $module){
            if($option==$module || $option==null){
                $assetDir=base_path('modules/'.$module.'/assets');
                if(is_dir($assetDir)){
                    $list=scandir($assetDir);
                    $fileList=array_diff($list,array('.','..','files','front','backend','.DS_Store'));
                    foreach ($fileList as $item){
                        if (!is_dir(public_path('modules/'.$module))){
                            mkdir(public_path('modules/'.$module));
                        }
                        $path=$assetDir.'/'.$item;
                        $new_path=public_path('modules/'.$module).'/'.$item;
                        if(is_dir($path)){
                            File::copyDirectory($path,$new_path);
                            $empty=false;
                        }
                        else if(is_file($path)){
                            File::copy($path,$new_path);
                            $empty=false;
                        }
                    }
                }
            }
        }
        if(!$empty){
            $this->info('asset published');
        }
        else{
            $this->info('not found assets');
        }
    }
}
