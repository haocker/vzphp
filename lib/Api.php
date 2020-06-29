<?php

class Api
{
    function __construct()
    {
        header('Content-type:text/json');
    }
    function success($data){
        $result = ['success'=>true,'data'=>$data];
        echo json_encode($result);
        exit;
    }
    function error($msg){
        $result = ['success'=>false,'msg'=>$msg];
        echo json_encode($result);
        exit;
    }
    function break($data){
        ob_end_clean();
        ob_start();
        $result = ['success'=>true,'data'=>$data];
        echo json_encode($result);
        $size = ob_get_length();
        header('HTTP/1.1 200 OK');
        header('Content-Length: '.$size);
        header('Connection: close');
        ob_end_flush();
        ob_flush();
        flush();
        if(function_exists('fastcgi_finish_request')){
            fastcgi_finish_request();
        }
        ignore_user_abort(true);
        @ini_set('max_execution_time','0');
        @ini_set('memory_limit','-1');
    }
}