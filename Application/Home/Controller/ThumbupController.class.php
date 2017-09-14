<?php
namespace Home\Controller;
use Think\Controller;
class ThumbupController extends Controller {

    public function showThumbupByArticle($articleId,$userId){
        $thumbupModel = new \Home\Model\ThumbupModel();
        
        $thumbupCount = $thumbupModel->showThumbups($articleId);
        $thumbupByUser = $thumbupModel->checkIfThumbup($articleId,$userId);

        if(count($thumbupByUser) < 1){
            $thumbupFlag = "false";
        }else{
            $thumbupFlag = "true";
        }

        if(count($thumbupCount) < 1){
            $showCount = 0
        }else{
            $showCount = $thumbupCount[0]['show_count'];
        }

        $result = array("cnt"=>$showCount,"flg"=>$thumbupFlag);
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

    public function addThumbup($articleId,$userId){
        try{

            $num = 1;//数量增加1
            $thumbupModel = new \Home\Model\ThumbupModel();

            $thumbupModel->addThumbupArticleUserRela($articleId,$userId);
            $thumbupCount = $thumbupModel->showThumbups($articleId);
            if(count($thumbupCount) < 1){
                $thumbupModel->addThumbupArticle($articleId);
            }else{
                $thumbupModel->updateThumbupArticle($articleId,$num);
            }
            $result = array("result"=>"success");
        }catch(Exception $e){
            $result = array("result"=>"failure");
        }finally{
            $data = json_encode($result);
            $this->ajaxReturn($data);
        }
    }

    public function deleteThumbup($articleId,$userId){
        try{
            $num = -1;//数量增加1
            $thumbupModel = new \Home\Model\ThumbupModel();

            $thumbupModel->delThumbupArticleUserRela($articleId,$userId);
            $thumbupCount = $thumbupModel->showThumbups($articleId);
            if(count($thumbupCount) > 0){
                $thumbupModel->updateThumbupArticle($articleId,$num);
            }
            $result = array("result"=>"success");
        }catch(Exception $e){
            $result = array("result"=>"failure");
        }finally{
            $data = json_encode($result);
            $this->ajaxReturn($data);
        }
    }
}