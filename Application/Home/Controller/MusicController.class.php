<?php
namespace Home\Controller;
use Think\Controller;
class MusicController extends Controller {
    

    public function getMusicUrl(){
        $musicModel = new \Home\Model\MusicModel();
        $music = $musicModel->getMusicUrl();

        $result = array("mUrl"=>$music[0]['url']);
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

}