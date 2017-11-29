<?php
namespace Home\Controller;
use Think\Controller;
class LearnWordController extends Controller {

    private $portionPerDay = 6;//每天学习单词数量

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
		$today=date('Y-m-d');
		$countToday = $learnWordModel->countTodayLearnt($userId,$today,$group);
		$todayLearntCount = $countToday[0]['count_today'];
		if($todayLearntCount == null){
			$todayLearntCount = 0;
		}
		$unlearntSinceTodayCount = $totalCount - ($learntCount - $todayLearntCount);
		$portionToday = $unlearntSinceTodayCount > $this->portionPerDay ? $this->portionPerDay : $unlearntSinceTodayCount;
		$durationDay = ceil($unlearntSinceTodayCount/$this->portionPerDay);
		//获取学习进度
		$progress = $learnWordModel->checkUserLearnProgress($userId,$group);

		$result = array("group"=>$group,"progress"=>$progress,"totalCount"=>$totalCount,"learntCount"=>$learntCount,"portionPerDay"=>$this->portionPerDay,"durationDay"=>$durationDay,"todayLearntCount"=>$todayLearntCount,"portionToday"=>$portionToday);
        $data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
	}

	private function countAllWordsToLearn($learnWordModel,$group){
		$total = $learnWordModel->countWordsToLearn($group);
		$totalCount = $total[0]['total_count'];
		if($totalCount == null){
			$totalCount = 0;
		}
		return $totalCount;
	}

	public function countLearnt($learnWordModel,$userId,$group){
		$learnt = $learnWordModel->countLearnt($userId,$group);
		$learntCount = $learnt[0]['count_learnt']; 
		if($learntCount == null){
			$learntCount = 0;
		}
		return $learntCount;
	}

	public function countLearnDays($learnWordModel,$userId,$group){
		$days = $learnWordModel->countLearnDays($userId,$group);
		$learnDaysCount = $days[0]['count_learn_days']; 
		if($learnDaysCount == null){
			$learnDaysCount = 0;
		}
		return $learnDaysCount;
	}

	public function countCrazyDays($learnWordModel,$userId,$group){
		$days = $learnWordModel->countLearnDays($userId,$group);
		$learnDaysCount = $days[0]['count_crazy_days']; 
		if($learnDaysCount == null){
			$learnDaysCount = 0;
		}
		return $learnDaysCount;
	}

	public function countTodayLearnt($learnWordModel,$userId,$today,$group){
		$countToday = $learnWordModel->countTodayLearnt($userId,$today,$group);
		$todayLearntCount = $countToday[0]['count_today'];
		if($todayLearntCount == null){
			$todayLearntCount = 0;
		}
		return $todayLearntCount;
	}

	
	public function countMostOneDay($learnWordModel,$userId,$today,$group){
		$countMostOneDay = $learnWordModel->countMostOneDay($userId,$today,$group);
		$mostOneDayCount = $countMostOneDay[0]['mostOneDay'];
		if($mostOneDayCount == null){
			$mostOneDayCount = 0;
		}
		return $mostOneDayCount;
	}
	/**
	* $progress  start:开始学习(需要插入学习进度信息)  resume:第二天继续学习(无需做任何处理)   next:下一个单词(需要保存上一个单词的学习情况)
	*/
	public function doLearn($userId,$group=1,$progress,$wordId,$status,$portionToday){
		$today=date('Y-m-d');
		$learnWordModel = new \Home\Model\LearnWordModel();

		//1.判断动作
		if($progress == "start"){//只会在第一次点击一个学习计划的开始学习时进入
			$learnWordModel->addUserLearnProgress($userId,$group,$today);
			$learnWordModel->addUserLearnCrazy($userId,$group,$today,0);
		}else if($progress == "next"){//每次选择下一个单词时
			if($wordId != null && $wordId != 0 && $status != null){
				$learnWordModel->saveLearnStatus($wordId,$userId,$status,$today,$group);
			}
			//TODO 如果是未掌握，加入到复习清单中
		}else if($progress == "resume"){//每次退出后，再进入，点击继续学习时
			$crazy = $learnWordModel->countUserLearnCrazy($userId,$group,$today);
			if($crazy[0]['crazy_count'] == 0){
				$learnWordModel->addUserLearnCrazy($userId,$group,$today,0);
			}
			
		}else if($progress == "goCrazy"){//进入无尽模式时，每天最多走入此分支一次
			$learnWordModel->setUserLearnCrazy(1,$userId,$group,$today);
		}

		//今天的任务完成，记录今天的学习状态为已完成
		$todayLearntCount = $this->countTodayLearnt($learnWordModel,$userId,$today,$group);//今天学了多少单词
		if($progress == "next" && $todayLearntCount == $portionToday){
			$learnWordModel->setUserLearnCrazy(2,$userId,$group,$today);
		}

		//3.尝试获取新单词
		$nextWord = $learnWordModel->getNextWord($userId,$group);
		if(!empty($nextWord)){
			if($todayLearntCount == $portionToday && $progress != "goCrazy"){//完成了今天的学习任务
				$learnDaysCount = $this->countLearnDays($learnWordModel,$userId,$group);//已经学了多少天了
				$crazyDaysCount = $this->countCrazyDays($learnWordModel,$userId,$group);
				$learntCount = $this->countLearnt($learnWordModel,$userId,$group);
				$result = array("isFinished"=>"todayTrue","learnDaysCount"=>$learnDaysCount,"crazyDaysCount"=>$crazyDaysCount,"learntCount"=>$learntCount,"group"=>$group,"portionToday"=>$portionToday);
			}else{//继续学习下个单词
				$nextWordId = $nextWord[0]['id'];
				$roots = $learnWordModel->getRootInfo($nextWordId);
				$percent = round($todayLearntCount*100/$portionToday);
				$crazy = $learnWordModel->checkUserLearnCrazy($userId,$group,$today);
				$isCrazy = $crazy[0]['is_crazy'];
				$isFinished = "false";
				if($isCrazy == 1){
					$isFinished = "todayCrazy";
				}
				$result = array("isFinished"=>$isFinished,"group"=>$group,"nextWord"=>$nextWord,"roots"=>$roots,"todayLearntCount"=>$todayLearntCount,"portionToday"=>$portionToday,"portionPerDay"=>$this->portionPerDay,"percent"=>$percent);
			}
		}else{//学习完成
			$learnWordModel->setUserLearnProgressFinished($today,$userId,$group);

			$learnDaysCount = $this->countLearnDays($learnWordModel,$userId,$group);
			$crazyDaysCount = $this->countCrazyDays($learnWordModel,$userId,$group);
			$learntCount = $this->countLearnt($learnWordModel,$userId,$group);
			$mostOneDayCount = $this->countMostOneDay($learnWordModel,$userId,$group);
			$result = array("isFinished"=>"true","learnDaysCount"=>$learnDaysCount,"crazyDaysCount"=>$crazyDaysCount,"learntCount"=>$learntCount,"mostOneDayCount"=>$mostOneDayCount);
		}
		$data = json_encode($result,JSON_UNESCAPED_UNICODE);
        $this->ajaxReturn($data);
	}

}