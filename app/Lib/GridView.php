<?php


namespace App\Lib;

use Session;
use Config;
class GridView
{
    protected static $dataProvider=[];

    protected static $columns=[];

    protected static $actions=[];

    public static $right_click=[];

    protected static $tableLabel='';

    protected static $route_param='';

    protected static $routeName=null;

    protected static $route_query_string='';

    protected static $tableCssClass='';

    protected static $removeEditLink=false;

    protected static $removeDeleteLink=false;

    protected static $viewComponent='panel-table';

    protected static $props=[];

    protected static $actionRouteParam='';

    public static function showTable($args,$removeEditLink=false,$removeDeleteLink=false){
        self::setProperty($args);
        self::$removeEditLink=$removeEditLink;
        self::$removeDeleteLink=$removeDeleteLink;
        self::$routeName=request()->route() ? request()->route()->getName() : null;
        self::addOtherColumn();
        self::addRightClick();
        self::addOtherModuleAction();
        self::createTable();
    }

    protected static function setProperty($args){
        if(is_array($args)){
            if(array_key_exists('dataProvider',$args)){
                self::$dataProvider=$args['dataProvider'];
            }
            if(array_key_exists('columns',$args)){
                self::$columns=$args['columns'];
            }
            if(array_key_exists('route_param',$args)){
                self::$route_param=$args['route_param'];
            }
            if(array_key_exists('actions',$args)){
                self::$actions=$args['actions'];
            }
            if(array_key_exists('tableLabel',$args)){
                self::$tableLabel=$args['tableLabel'];
            }
            if(array_key_exists('route_query_string',$args)){
                self::$route_query_string=$args['route_query_string'];
            }
            if(array_key_exists('tableCssClass',$args)){
                self::$tableCssClass=$args['tableCssClass'];
            }
            if(array_key_exists('viewComponent',$args)){
                self::$viewComponent=$args['viewComponent'];
            }
            if(array_key_exists('props',$args)){
                self::$props=$args['props'];
            }
            if(array_key_exists('actionRouteParam',$args)){
                self::$actionRouteParam=$args['actionRouteParam'];
            }
        }
    }

    protected static function createTable(){

        echo '<form method="post" id="data_form">';

        $headers=self::getHeader();
        $items=self::getBody();
        echo '<'.self::$viewComponent.'
         :headers="'.str_replace('"',"'",json_encode($headers)).'"
         :items="'.str_replace('"',"'",json_encode($items)).'"
         :right_click="'.str_replace('"',"'",json_encode(self::$right_click)).'"
         >';

        echo '</'.self::$viewComponent.'>';

        echo '</form>';
    }

    protected static function getHeader(){
        $headers=[];

        $key=0;
        if(!self::$removeDeleteLink){
            $headers[0]=[
                'text'=>'#',
                'align'=>'center'
            ];
            $key=1;
        }

        $headers[$key]=[
            'text'=>'ردیف',
            'align'=>'center'
        ];

        foreach (self::$columns as $column){
            $headers[]=[
                'text'=>$column['label'],
                'align'=>'center'
            ];
        }

        if(!self::$removeDeleteLink || !self::$removeEditLink || sizeof(self::$actions)>0){
            $headers[]=[
                'text'=>'عملیات',
                'align'=>'center'
            ];
        }

        return $headers;
    }

    protected static function getBody(){

        $items=[];

        $i=(isset($_GET['page'])) ? (($_GET['page']-1)*10): 0 ;

        $data=self::$dataProvider;
        $route_param=self::$route_param;

        foreach ($data as $key=>$value){
            $i++;

            $items[$key]=['id'=>$value['id']];

            foreach (self::$props as $prop){
                $items[$key][$prop]=$value[$prop];
            }
            if(!self::$removeDeleteLink){
                $items[$key]['column0']='<v-checkbox name="'.$route_param.'_id" value="'.$value->id.'"></v-checkbox>';
                $items[$key]['column1']=replace_number($i);
                $j=2;
            }
            else{
                $items[$key]['column0']=replace_number($i);
                $j=1;
            }
            foreach (self::$columns as $column){
                $v=self::getColumnValue($column['attr'],$value);
                $c='column'.$j;
                $items[$key][$c]=Htmlspecialchars($v,ENT_QUOTES);
                $j++;
            }
            $items[$key]['column'.$j]=self::getAction($value,$route_param);
        }

        return $items;
    }

    protected static function getColumnValue($attr,$value){
        if(is_string($attr)){
            $v=$value->$attr;
        }
        else{
            $v=$attr($value);
        }
        return $v;
    }

    protected static function getAction($value,$route_param){
        $res='';
        if(!self::$removeDeleteLink || !self::$removeEditLink || sizeof(self::$actions)>0){
            $res.='<div class="action-box">';

            $res.=self::getOtherAction($value);

            if(!self::$removeEditLink){
                if(!method_exists($value,'trashed') ||  !$value->trashed()){
                    $res.='<a class="router-link" href="'.url(self::$actionRouteParam.'/'.$route_param.'/'.$value->id.'/edit').self::$route_query_string.'">
                     <v-icon color="primary">mdi-circle-edit-outline</v-icon>
                    </a> ';
                }
            }

            $res.=self::restoreAction($value);

            if(!self::$removeDeleteLink){
                $res.=self::deleteAction($value);
            }
            $res.='</div>';
        }
        return $res;
    }

    protected static function deleteAction($value){
        $url=url(self::$actionRouteParam.'/'.self::$route_param.'/'.$value->id).self::$route_query_string;
        $sendTrash='yes';
        if(method_exists($value,'trashed') && $value->trashed()){
            $sendTrash='no';
        }
        $id=$value->id;
        return '<delete-link row-id="'.$id.'" label="'.self::$tableLabel.'" sendTrash="'.$sendTrash.'" url="'.$url.'"></delete-link>';
    }

    protected static function restoreAction($value){
        if(method_exists($value,'trashed') && $value->trashed()){
            $url=url(self::$actionRouteParam.'/'.self::$route_param.'/'.$value->id).self::$route_query_string;
            $id=$value->id;
            return '<restore-link row-id="'.$id.'" label="'.self::$tableLabel.'" url="'.$url.'"></restore-link>';
        }
        else{
            return '';
        }
    }

    protected static  function addSuccessMessageBox(){
        if(Session::has('message'))
        {
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <?= Htmlspecialchars(Session::get('message'),ENT_QUOTES) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
        }
    }

    protected static function getOtherAction($value){
        $res='';
        foreach (self::$actions as $action){
            $res.=$action($value);
        }
        return $res;
    }

    protected static function addRightClick(){
        $routeName=self::$routeName;
        if($routeName){
            $routeName=str_replace('.','_',$routeName);
            $action=$routeName.'_right_click';
            $result=run_action($action,[],true,true);
            if(is_array($result)){
                self::$right_click=self::$right_click+$result;
            }
        }
    }

    protected static function addOtherColumn(){
        if(self::$routeName && sizeof(self::$dataProvider)>0){
            $routeName=str_replace('.','_',self::$routeName);
            $action1=$routeName.'_table';
            $action2='table_widget';
            $results=[];
            $results[]=run_action($action1,[self::$dataProvider],true);
            $results[]=run_action($action2,[self::$dataProvider],true);
            foreach ($results as $result){
                if(is_array($result)){
                    foreach ($result as $array){
                        if(is_array($array)){
                            for ($i=0;$i<sizeof($array);$i++){
                                $key=sizeof(self::$columns);
                                self::$columns[$key]=$array[$i];
                            }
                        }
                    }
                }
            }
        }
    }

    protected static function addOtherModuleAction(){
        $routeName=self::$routeName;
        if($routeName){
            $routeName=str_replace('.','_',$routeName);
            $action=$routeName.'_action';
            $result=run_action($action,[],true);
            if(is_array($result)){
                foreach ($result as $array){
                    self::$actions[]=$array;
                }
            }
        }
    }
}

