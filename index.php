<?php
session_start();
require './lib/Core.php';
define('APP_ROOT','app');//定义APP根目录
define('DEFAULT_APP','home');//定义默认APP
define('URL_MODEL','3');//定义URL模式 1.常规 2.单参模式 3.伪静态模式
define('URL_SUFFIX','html');//定义URL后缀，URL模式不为1时生效
vz_init();