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
}