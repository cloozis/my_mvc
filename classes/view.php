<?php
class View extends Model {
    function __construct(){
        return true;
    }

    public function baseTemplate($content){
        if($_SESSION['auth']){
            $fileTemp = TEMP_PATH."admin.html";
        } else {
            $fileTemp = TEMP_PATH."index.html";
        }

        echo Model::renderTemplate($fileTemp, $content);
    }


}
