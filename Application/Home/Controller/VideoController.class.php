<?php
namespace Home\Controller;
use Think\Controller;
class MusicController extends Controller {
    

    public function getVideoListPaging($pageNo=0,$pageSize=10){
    	$start = $pageNo*$pageSize;
        $videoModel = new \Home\Model\VideoModel();
        $videoList = $videoModel->getVideoListPaging($start,$pageSize);

        $result = array("videoList"=>$videoList);
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

}