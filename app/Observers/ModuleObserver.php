<?php

namespace App\Observers;
use Request;
class ModuleObserver
{
    public function saving($model){
        $this->run_event($model,'saving');
    }
    public function updated($model){
        $this->run_event($model,'updated');
    }
    public function created($model){
        $this->run_event($model,'created');
    }
    public function updating($model){
        $this->run_event($model,'updating');
    }
    public function restored($model){
        $this->run_event($model,'restored');
    }
    public function deleted($model){
        $this->run_event($model,'deleted');
    }
    protected function run_event($model,$event){
        $action=$model->getTable().'_'.$event;
        run_action($action,[$model]);
    }


}
