<?php
require './lib/Functions.php';
require './lib/Api.php';
require './lib/View.php';
require './lib/Mysql.php';
function vz_init(){
    if (URL_MODEL=='1'){
        $module = get('m')!=''?get('m'):DEFAULT_APP;
        $controller = get('c')!=''?get('c'):'Index';
        $action = get('a')!=''?get('a'):'index';
    }else{
        $v = get('v');
        if($v==''){
            $module = get('m')!=''?get('m'):DEFAULT_APP;
            $controller = get('c')!=''?get('c'):'Index';
            $action = get('a')!=''?get('a'):'index';
        }else{
            $v = str_ireplace('.'.URL_SUFFIX,'',$v);
            $urlArr = explode('/',$v);
            if (sizeof($urlArr)>3){
                $module = $urlArr[1];
                $controller = $urlArr[2];
                $action = $urlArr[3];
                foreach ($urlArr as $k=>$v){
                    if($k>3&&$k%2==1){
                        $_GET[$urlArr[$k-1]] = $urlArr[$k];
                    }
                }
            }else{
                die('404');
            }
        }

    }
    define('_MODULE_',$module);
    define('_CONTROLLER_',$controller);
    define('_ACTION_',$action);
    $action = '_'.$action;
    if(file_exists('./'.APP_ROOT.'/'.$module.'/controller/'.$controller.'.php')){
        define('MODULE_PATH','./'.APP_ROOT.'/'.$module.'/');
        require './'.APP_ROOT.'/'.$module.'/controller/'.$controller.'.php';
        $clazz = new $controller();
        if(method_exists($clazz,$action)){
            $clazz->$action();
        }else{
            echo '404';
        }
    }else{
        echo '404';
    }
}