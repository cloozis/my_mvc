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

            $content = "<?php\n
define('DEF_SITE_NAME', '{$data['sitename']}');\n
define('DEF_LOGIN', '{$data['login']}');\n
define('DEF_PASS', '{$data['password']}');";

            file_put_contents($file, $content);
            createPagesTable();
            header('Location: /');

        }

        die($output);
    }

    require_once($file);
}

checkConfig();

function createPagesTable(){
    $db = new SQLite3('database.db');
    $q = 'CREATE TABLE "pages" (
        "id"	INTEGER,
        "title"	TEXT,
        "content"	TEXT,
        "meta_d"	TEXT,
        "meta_k"	TEXT,
        "url"	TEXT,
        PRIMARY KEY("id")
    );';

    $db->query($q);
    $db->close();
}

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
