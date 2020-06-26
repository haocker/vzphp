<?php

load('Base.php');
class Index extends Base
{

    function _index(){
        $this->assign('html',url('user/admin'));
        $this->show('index');
    }
}