<?php
class Model {

    public $db;

    function __construct(){
        return true;
    }

    public function createHtml($tag, $attr = array(), $content){

        if(is_array($attr))
            foreach($attr as $key => $val){
                if($val)
                    $res[] = "{$key}='{$val}'";
            }

        if(is_array($res))
            $attrs = ' '.implode(' ', $res);

        return "<{$tag}{$attrs}>{$content}</{$tag}>";
    }

    public function crateLink($url, $text){
        return $this->createHtml('p',[], $this->createHtml('a', ['id'=>'openEditor', 'href'=>$url], $text));
    }

    public function createPage(){
        if($_SESSION['auth']){
            return $this->crateLink('javascript:void(0);','Создать');
        }
    }

    public function editPage(){
        if($_SESSION['auth']){
            return $this->crateLink('javascript:void(0);','Редактировать');
        }
    }

    public function getContent($url){

        $q = "SELECT * FROM `pages` WHERE `url` LIKE '%{$url}'";

        $result = $this->db->query($q);
        $data = $result->fetchArray();

        if($data){
            $data['link'] = $this->editPage();
            return $data;
        } else {
            return ['content'=>$this->createHtml('div',array('id'=>'mainContent'),'404. Страница '.$url.' не найдена!'), 'link'=>$this->createPage()];
        }

        $this->db->close();
    }

    public function doUpload($file){

        $file_name = $file['upload']['name'];
        $tmp_name = $file['upload']['tmp_name'];

        $file_p = stripslashes("/upload/".$file_name);
        $ext = end(explode('.',$file_name));

        if(move_uploaded_file($tmp_name, "$uploads_dir/$file_name") || $_SESSION['auth']){
            $data['file'] = $file_name;
            $data['url'] = $file_p;
            $data['uploaded'] = 1;
        } else {
            $data['uploaded'] = 0;
            $data['error'] = 'Error';
        }

        return json_encode($data);
    }

    public function processContent($file, $content){
        $arr = [
            'id'=>'mainContent',
            'data-title'=>$content['title']?$content['title']:'title',
            'data-meta_d'=>$content['meta_d']?$content['meta_d']:'meta_d',
            'data-meta_k'=>$content['meta_k']?$content['meta_k']:'meta_k',
        ];

        $file = str_replace('{{title}}', $content['title'], $file);
        $file = str_replace('{{meta_d}}', $content['meta_d'], $file);
        $file = str_replace('{{meta_k}}', $content['meta_k'], $file);

        return str_replace('{{content}}', $content['link'].$this->createHtml('div', $arr, $content['content']), $file);
    }

    public function renderTemplate($file, $content){
        $output = file_get_contents($file);
        return $this->processContent($output, $content);
    }

    public function u_decode($url){
        return urldecode($url);
    }

    public function checkPage($url){

        try{

            $url = $this->u_decode($url);

            if($url == '/logout'){
                unset($_SESSION['auth']);
                header('Location: /');
                exit;
            }

            if(!$url && $url == '/'){
                $url = 'index.html';
            }

            $url = stripslashes($url);

            return $this->getContent($url);

        } catch (Exception $e){
            return false;
        }

    }
}
