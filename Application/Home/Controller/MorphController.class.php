<?php
namespace Home\Controller;
use Think\Controller;
class MorphController extends Controller {

    public function showMorphemesByCapital($capital='a'){
        $morph = new \Home\Model\MorphModel();
        $morphList = $morph->showMorphemeByCapital($capital);
        $this->assign('morphList',$morphList);
		$this->assign('num',1);//记录编号
		$this->assign('capital',$capital);
        layout(true);
        $this->display();
    }

    public function showMorphemesByCapitalJson($capital='a'){
        $morph = new \Home\Model\MorphModel();
        
        if(strval($capital) == '100'){
            $morphList = $morph->showAllMorphemes(0,100);
            $showType = "1";
        }else if(strval($capital) == '200'){
            $morphList = $morph->showAllMorphemes(100,100);
            $showType = "2";
        }else if(strval($capital) == '300'){
            $morphList = $morph->showAllMorphemes(200,100);
            $showType = "3";
        }else{
            $morphList = $morph->showMorphemeByCapital($capital);
            $showType = "0";
        }
        $result = array("morphList"=>$morphList,"capital"=>$capital,"showType"=>$showType);
        $data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
    }

    public function showMorphemesByCapitalJsonPaging($capital='a',$pageNo=0,$pageSize=20){
        $morph = new \Home\Model\MorphModel();
        $start = $pageNo*$pageSize;
        if(strval($capital) == '100'){
            $morphList = $morph->showAllMorphemes($start,$pageSize);
            $showType = "1";
        }else if(strval($capital) == '200'){
            $morphList = $morph->showAllMorphemes($start+100,$pageSize);
            $showType = "2";
        }else if(strval($capital) == '300'){
            $morphList = $morph->showAllMorphemes($start+200,$pageSize);
            $showType = "3";
        }else{
            $morphList = $morph->showMorphemeByCapital($capital);
            $showType = "0";
        }
        $result = array("morphList"=>$morphList,"capital"=>$capital,"showType"=>$showType);
        $data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
    }

    public function fuzzySearchMorph($fuzzyMorph){
        if(strlen($fuzzyMorph)>25){
            $this->ajaxReturn('noresult');
        }
        //做安全校验,防sql注入
        $fuzzyMorph = preg_replace("/[^a-zA-Z\-]/","",$fuzzyMorph);
        if($fuzzyMorph == ""){
            $this->ajaxReturn('noresult');
        }
        $morph = new \Home\Model\MorphModel();
        
        if(strlen($fuzzyMorph)<3){
            $morphList = $morph->fuzzySearchMorph($fuzzyMorph);
        }else{//当q长度大于2时，开始同时搜索单词和词根
            $morphList = $morph->fuzzySearchMorphAndWord($fuzzyMorph);
        }
        
        $data = json_encode($morphList);
        $this->ajaxReturn($data);
    }

    public function showWordsByMorpheme($morphemeId=1){
        $morph = new \Home\Model\MorphModel();
        $wordList = $morph->showWordsByMorpheme($morphemeId);
        $this->assign('wordList',$wordList);
        $this->assign('num',1);//记录编号
		$morpheme = $morph->showMorphemeById($morphemeId);
        $this->assign('morpheme',$morpheme);
        layout(true);
        $this->display();
    }

    public function showWordsByMorphemeJson($morphemeId=1){
        $morph = new \Home\Model\MorphModel();
        $wordList = $morph->showWordsByMorpheme($morphemeId);
        $morpheme = $morph->showMorphemeById($morphemeId);

        $result = array("wordList"=>$wordList,"morpheme"=>$morpheme);
        $data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
    }

	public function showSimilarWords($wId=1){
        $morph = new \Home\Model\MorphModel();
        $wordList = $morph->showSimilarWords($wId);
        $this->assign('wordList',$wordList);
        $this->assign('num',1);//记录编号
        layout(true);
        $this->display();
    }

    public function showSimilarWordsJson($wId=1){
        $morph = new \Home\Model\MorphModel();
        $wordList = $morph->showSimilarWords($wId);
        $result = array("wordList"=>$wordList);
        $data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
    }
}