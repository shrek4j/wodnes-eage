<?php
namespace Home\Controller;
use Think\Controller;
class CollectRootController extends Controller {

    public function showCollectByRoot($rootId,$userId){
        $collectRootModel = new \Home\Model\CollectRootModel();
        
        $collectCount = $collectRootModel->showCollects($rootId);
        $collectByUser = $collectRootModel->checkIfCollect($rootId,$userId);

        if(count($collectByUser) < 1){
            $collectFlag = "false";
        }else{
            $collectFlag = "true";
        }

        if(count($collectCount) < 1){
            $showCount = 0;
        }else{
            $showCount = $collectCount[0]['show_count'];
        }

        $result = array("cnt"=>$showCount,"flg"=>$collectFlag);
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

    public function addCollectRoot($rootId,$userId){
        $num = 1;//数量增加1
        $collectRootModel = new \Home\Model\CollectRootModel();

        $collectRootModel->addCollectRootUserRela($rootId,$userId);
        $collectCount = $collectRootModel->showCollects($rootId);
        if(count($collectCount) < 1){
            $collectRootModel->addCollectRoot($rootId);
        }else{
            $collectRootModel->updateCollectRoot($rootId,$num);
        }
        $result = array("result"=>"success");
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

    public function deleteCollectRoot($rootId,$userId){
        $num = -1;//数量增加1
        $collectRootModel = new \Home\Model\CollectRootModel();

        $collectRootModel->delCollectRootUserRela($rootId,$userId);
        $collectCount = $collectRootModel->showCollects($rootId);
        if(count($collectCount) > 0){
            $collectRootModel->updateCollectRoot($rootId,$num);
        }
        $result = array("result"=>"success");
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

    public function showRootCollectsByUser($userId=0){
        $collectRootModel = new \Home\Model\CollectRootModel();
        $rootCollectsList = $collectRootModel->showRootCollectsByUser($userId);

        $result = array("collects"=>$rootCollectsList);
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

    public function showVideoWordCollectsByUserPaging($userId=0,$start=0,$pageSize=10){
        $collectRootModel = new \Home\Model\CollectRootModel();
        $rootCollectsList = $collectRootModel->showVideoWordCollectsByUserPaging($userId,$start,$pageSize);
        $count = $collectRootModel->countVideoWordCollects($userId);
        $result = array("collects"=>$rootCollectsList,"totalCount"=>$count[0]['count']);
        $data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
    }
}