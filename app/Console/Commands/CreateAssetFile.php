<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Config;
use Storage;
class CreateAssetFile extends Command
{
    protected $modules=[];
    protected $error=false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:createAsset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create asset files';

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
        $this->modules=Config::get('app.modules');
        $this->createScss();
        $this->createJs();
        if(!$this->error){
            $this->info('File creation done');
        }
    }
    public function createScss(){
        $types=['front','backend'];
        foreach ($types as $type){
            $files=['desktop','mobile','all'];
            foreach ($files as $file){
                $string='';
                foreach ($this->modules as $module){
                    $asset_file=base_path('modules/'.$module.'/assets/'.$type.'/css/'.$file.'.css');
                    if(file_exists($asset_file)){
                        $string.='@import '.'"'.'../../../modules/'.$module.'/assets/'.$type.'/css/'.$file.'.css'.'";'."\n";
                    }
                }
                if($string!=''){
                    Storage::disk('assets')->put('sass/'.$type.'/'.$file.'.scss',$string);
                }
            }
        }
    }
    public function createJs(){
        $files=['desktop','mobile','all'];
        foreach ($files as $file){
            $string='';
            foreach ($this->modules as $module){
                $asset_file=base_path('modules/'.$module.'/assets/front/js/'.$file.'.js');
                if(file_exists($asset_file)){
                    $string.='import '.'"'.'../../../modules/'.$module.'/assets/front/js/'.$file.'.js'.'";'."\n";
                }
            }
            if($string!=''){
                Storage::disk('assets')->put('js/front/'.$file.'.js',$string);
            }
        }

        $this->createBackendJsFile();
    }
    public function createBackendJsFile(){
        $string='';
        foreach ($this->modules as $module){
            $asset_file=base_path('modules/'.$module.'/assets/backend/js/panel.js');
            if(file_exists($asset_file)){
                $string.='import '.'"'.'../../../modules/'.$module.'/assets/backend/js/panel.js'.'";'."\n";
            }
        }
        if($string!=''){
            Storage::disk('assets')->put('js/backend/panel.js',$string);
        }
    }
}
