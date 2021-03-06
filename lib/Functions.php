<?php
function get($key){
    return isset($_GET[$key])?$_GET[$key]:'';
}
function post($key){
    return isset($_POST[$key])?$_POST[$key]:'';
}
function upload($key){
    return isset($_FILES[$key])?$_FILES[$key]:null;
}
function curl_get($url,$cookies='',$headers=[]){
    $ch = curl_init();
    if($cookies!=''){
        curl_setopt($ch,CURLOPT_COOKIE,$cookies);
    }
    if($headers!=[]){
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    }
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch,CURLOPT_TIMEOUT,30);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.104 Mobile Safari/537.36');
    $temp = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($temp, 0, $headerSize);
    $body = substr($temp, $headerSize);
    curl_close($ch);
    $result = ['header'=>$header,'body'=>$body];
    return $result;
}
function curl_post($url,$data,$cookies='',$headers=[]){
    $ch = curl_init();
    if($cookies!=''){
        curl_setopt($ch,CURLOPT_COOKIE,$cookies);
    }
    if($headers!=[]){
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    }
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch,CURLOPT_TIMEOUT,30);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.104 Mobile Safari/537.36');
    $temp = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($temp, 0, $headerSize);
    $body = substr($temp, $headerSize);
    curl_close($ch);
    $result = ['header'=>$header,'body'=>$body];
    return $result;
}
function db($table){
    $file = MODULE_PATH.'config/db.php';
    if (!file_exists($file)){
        $file = COMMON_PATH.'config/db.php';
        if (!file_exists($file)){
            return null;
        }
    }
    $db_config = require $file;
    $conn = new Mysql($db_config);
    $conn->table($table);
    return $conn;
}
function url($express = '',$param = []){
    $module = DEFAULT_APP;
    $controller = 'Index';
    $action = 'index';
    $expressArr = explode('/',$express);
    switch (sizeof($expressArr)){
        case 1:
            if($expressArr[0]!=''){
                $action = $expressArr[0];
            }
            break;
        case 2:
            $controller = $expressArr[0];
            $action = $expressArr[1];
            break;
        case 3:
            $module = $expressArr[0];
            $controller = $expressArr[1];
            $action = $expressArr[2];
            break;
    }
    switch (URL_MODEL){
        case '1':
            $url = "/?m={$module}&c={$controller}&a={$action}";
            foreach ($param as $k=>$v){
                $url.="&{$k}={$v}";
            }
            break;
        case '2':
            $url = "/index.php/{$module}/{$controller}/{$action}";
            foreach ($param as $k=>$v){
                $url.="/{$k}/{$v}";
            }
            $url.='.'.URL_SUFFIX;
            break;
        case '3':
            $url = "/{$module}/{$controller}/{$action}";
            foreach ($param as $k=>$v){
                $url.="/{$k}/{$v}";
            }
            $url.='.'.URL_SUFFIX;
            break;
    }
    return $url;
}
function load($file){
    $path = './'.APP_ROOT.'/'._MODULE_.'/controller/'.$file;
    if (file_exists($path)){
        require $path;
    }
}
function ext($file){
    $path = EXT_PATH.$file;
    if (file_exists($path)){
        require $path;
    }
}
function postData(){
    return file_get_contents("php://input");
}
function cfg($key=''){
    $file = MODULE_PATH.'config/cfg.php';
    if (!file_exists($file)){
        return null;
    }
    $cfg = require $file;
    if ($key!=''){
        return $cfg[$key];
    }else{
        return $cfg;
    }
}
function session($k,$v=null){
    if($v!=null){
        $_SESSION[$k] = $v;
    }else{
        $result = isset($_SESSION[$k])?$_SESSION[$k]:null;
        return $result;
    }
}
function cache($path,$v=null){
    $cacheDir = 'cache/data/';
    if (!file_exists($cacheDir.$path)){
        mkdir($cacheDir.$path,0777,true);
    }
    if($v!=null){
        file_put_contents($cacheDir.$path.'/.cache',json_encode($v));
    }else{
        $result = file_exists($cacheDir.$path.'/.cache')?json_decode(file_get_contents($cacheDir.$path.'/.cache'),true):null;
        return $result;
    }
}
function rmCache($path){
    $cacheDir = 'cache/data/';
    if (file_exists($cacheDir)){
        unlink($cacheDir.$path.'/.cache');
    }
}