<?php
session_start();
define('CLASSES_PATH', './classes/');
define('TEMP_PATH', './templates/');


function checkConfig(){
    $file = 'config.php';

    if (!file_exists($file)) {
        $output = file_get_contents(TEMP_PATH.'config.html');
        if($_POST){

            $data = $_POST;
            $data['password'] = md5($data['password']);
// print_r($data);
            $content = "<?php\n
define('DEF_SITE_NAME', '{$data['sitename']}');\n
define('DEF_LOGIN', '{$data['login']}');\n
define('DEF_PASS', '{$data['password']}');";

            file_put_contents($file, $content);
            header('Location: /');

        }

        die($output);
    }

    require_once($file);
}

checkConfig();

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
// echo "<pre>";
// print_r($_SERVER);
$app = new controller();
