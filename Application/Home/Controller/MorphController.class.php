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

    public function fuzzySearchMorph($fuzzyMorph){
        if(strlen($fuzzyMorph)>8){
            $this->ajaxReturn('noresult');
        }
        //做安全校验,防sql注入
        $fuzzyMorph = preg_replace("/[^a-zA-Z\-]/","",$fuzzyMorph);
        if($fuzzyMorph == ""){
            $this->ajaxReturn('noresult');
        }
        $morph = new \Home\Model\MorphModel();
        $morphList = $morph->fuzzySearchMorph($fuzzyMorph);
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

	public function showSimilarWords($wId=1){
        $morph = new \Home\Model\MorphModel();
        $wordList = $morph->showSimilarWords($wId);
        $this->assign('wordList',$wordList);
        $this->assign('num',1);//记录编号
        layout(true);
        $this->display();
    }
}