<?php
namespace Home\Controller;
use Think\Controller;
class LearnWordController extends Controller {

    private $portionPerDay = 20;//每天学习单词数量

	/**
	*展示每天的学习进度
	*/
	public function showTaskInfo($userId,$group=1){
		$learnWordModel = new \Home\Model\LearnWordModel();
		//获取学习数据
		$totalCount = $this->countAllWordsToLearn($learnWordModel,$group);
		if($totalCount == null){
			$totalCount = 0;
		}
		$learntCount = $this->countLearnt($learnWordModel,$userId,$group);
		if($learntCount == null){
			$learntCount = 0;
		}
		$unlearntCount = $totalCount - $learntCount;
		$portionToday = $unlearntCount > $this->$portionPerDay ? $this->$portionPerDay : $unlearntCount;
		$durationDay = ceil($unlearntCount/$this->portionPerDay);
		//获取学习进度
		$progress = $learnWordModel->checkUserLearnProgress($userId,$group);
		$today=date('Y-m-d');
		$countToday = $learnWordModel->countTodayLearnt($userId,$today,$group);
		$todayLearntCount = $countToday[0]['countToday'];
		if($todayLearntCount == null){
			$todayLearntCount = 0;
		}
		$result = array("group"=>$group,"progress"=>$progress,"totalCount"=>$totalCount,"learntCount"=>$learntCount,"portionPerDay"=>$this->portionPerDay,"durationDay"=>$durationDay,"todayLearntCount"=>$todayLearntCount,"portionToday"=>$portionToday);
        $data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
	}

	private function countAllWordsToLearn($learnWordModel,$group=1){
		$totalCount = $learnWordModel->countWordsToLearn($group);
		return $totalCount[0]['total_count'];
	}

	public function countLearnt($learnWordModel,$userId,$group=1){
		$learntCount = $learnWordModel->countLearnt($userId,$group);
		return $learntCount[0]['count_learnt']; 
	}

	/**
	* $progress  start:开始学习(需要插入学习进度信息)  resume:第二天继续学习(无需做任何处理)   next:下一个单词(需要保存上一个单词的学习情况)
	*/
	public function doLearn($userId,$group=1,$progress,$wordId,$status){
		$today=date('Y-m-d');
		$learnWordModel = new \Home\Model\LearnWordModel();

		if($progress == "start"){
			$learnWordModel->addUserLearnProgress($userId,$group,$today);
		}else if($progress == "next"){
			if(!empty($wordId) && !empty($status)){
				$learnWordModel->saveLearnStatus($wordId,$userId,$status,$today);
			}
			//TODO 如果是未掌握，加入到复习清单中
		}else if($progress == "resume"){
			//do nothing
		}

		//尝试获取新单词
		$nextWord = $learnWordModel->getNextWord($userId,$group);
		if(!empty($nextWord)){
			//下个单词信息
			$nextWordId = $nextWord[0]['id'];
			$roots = $learnWordModel->getRootInfo($nextWordId);
			//今天学了多少单词
			$countToday = $learnWordModel->countTodayLearnt($userId,$today,$group);
			$todayLearntCount = $countToday[0]['countToday'];
			if($todayLearntCount == null){
				$todayLearntCount = 0;
			}
			$percent = round($todayLearntCount*100/$this->portionPerDay);
			$result = array("isFinished"=>"false","group"=>$group,"nextWord"=>$nextWord,"roots"=>$roots,"todayLearntCount"=>$todayLearntCount,"portionPerDay"=>$this->portionPerDay,"percent"=>$percent);
		}else{//学习完成
			$learnWordModel->setUserLearnProgressFinished($today,$userId,$group);
			//TODO 显示学习成果，分享给大家
			$result = array("isFinished"=>"true");
		}
		$data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
	}

}