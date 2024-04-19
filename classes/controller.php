<?php
class Controller extends View{
    function __construct(){

        $uri = $_SERVER['REQUEST_URI'];

        $this->db = new SQLite3('database.db');

        try{
            $data = json_decode(file_get_contents('php://input'));

            if($data->save == 'save_page'){

                ($this->savePage($data)) ? die(json_encode(['save_page'=>true])) : die(json_encode(['save_page'=>false]));
            }

            if($data->auth){
                die($this->Login($data->auth));
            }

        } catch (Exception $e) {

        }

        if(isset($_FILES['upload']['name'])){
            die(Model::doUpload($_FILES));
        }

        $content = Model::checkPage($uri);

        View::baseTemplate($content);
    }

    public function addPage($data, $db){

        $stmt = $db->prepare('INSERT INTO pages (content, url) VALUES (:content, :url)');

        $stmt->bindValue(':content', $data->content);

        $stmt->bindValue(':url', $data->url);

        $result = $stmt->execute();

        $db->close();
    }

    public function updatePage($data, $db){

        $stmt = $this->db->prepare("UPDATE `pages` SET content = '{$data->content}' WHERE `url` LIKE '%{$data->url}';");

        $result = $stmt->execute();

        $db->close();
    }

    public function savePage($data){

        $data->url = Model::u_decode($data->url);

        $query = "SELECT * FROM pages WHERE url LIKE '%{$data->url}'";

        $result = $this->db->query($query);

        $row = $result->fetchArray();

        if($row['id']){
            $this->updatePage($data, $this->db);
        } else {
            $this->addPage($data, $this->db);
        }

        return true;
    }

    public function Login($data){

        if(DEF_LOGIN == $data->login && DEF_PASS == md5($data->password)){
            // $_SESSION['login'];
            // $_SESSION['password']
            $_SESSION['auth'] = true;
            return json_encode(array("auth"=>True));
        } else {
            return json_encode(array("auth"=>false));
        }

    }
}
