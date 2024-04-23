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

        if($_SESSION['auth']){
            if(isset($_FILES['upload']['name'])){
                die(Model::doUpload($_FILES));
            }
        }

        $content = Model::checkPage($uri);

        View::baseTemplate($content);
    }

    public function addPage($data, $db){

        $stmt = $db->prepare('INSERT INTO pages (title, content, meta_d, meta_k, url) VALUES (:title, :content, :meta_d, :meta_k, :url)');

        $stmt->bindValue(':title', $data->title);
        $stmt->bindValue(':content', $data->content);
        $stmt->bindValue(':meta_d', $data->meta_d);
        $stmt->bindValue(':meta_k', $data->meta_k);
        $stmt->bindValue(':url', $data->url);

        $result = $stmt->execute();

        $db->close();
    }

    public function updatePage($data, $db){

        $stmt = $this->db->prepare("UPDATE `pages` SET title = '{$data->title}', content = '{$data->content}', meta_d = '{$data->meta_d}', meta_k = '{$data->meta_k}' WHERE `url` LIKE '%{$data->url}';");

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
            $_SESSION['auth'] = true;
            return json_encode(array("auth"=>True));
        } else {
            return json_encode(array("auth"=>false));
        }

    }
}
