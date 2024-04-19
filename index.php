<?php
session_start();
define('CLASSES_PATH', './classes/');
define('TEMP_PATH', './templates/');
define('DEF_LOGIN', 'admin');
define('DEF_PASS', '4297f44b13955235245b2497399d7a93');

function __autoload($class_name)
{

    require_once(CLASSES_PATH.$class_name.'.php');

    if(method_exists($class_name,'init'))

        call_user_func(array($class_name,'init'));

    return true;

}
__autoload("model");
__autoload("view");
__autoload("controller");

$app = new controller();
