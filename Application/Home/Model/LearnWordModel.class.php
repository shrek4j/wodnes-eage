<?php
namespace Home\Model;
use Think\Model;
class LearnWordModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应

    public function countWordsToLearn($group){
        $sql = "SELECT COUNT(DISTINCT wwrr.word_id) total_count 
                FROM wiki_word_root_rela wwrr 
        		LEFT JOIN wiki_word_root_learn_template wwrlt ON wwrr.word_root_id=wwrlt.word_root_id  
        		LEFT JOIN wiki_word wr ON wwrr.word_id=wr.id 
        		WHERE wr.learn_flag=1 
        		AND `group`=%d";
        return $this->query($sql,$group);
    }

    public function countLearnt($userId,$group){
    	$sql = "SELECT COUNT(uwwl.id) count_learnt 
                FROM user_wiki_word_learn uwwl 
				WHERE uwwl.user_id =%d 
				AND uwwl.`group`=%d";
        return $this->query($sql,$userId,$group);
    }

    public function saveLearnStatus($wordId,$userId,$learnStatus,$learnDate,$group){
    	$sql = "INSERT INTO user_wiki_word_learn(word_id,user_id,learn_status,learn_date,`group`) VALUES(%d,%d,%d,'%s',%d)";
    	$this->execute($sql,$wordId,$userId,$learnStatus,$learnDate,$group);
    }

    public function getNextWord($userId,$group){
    	$sql = "SELECT DISTINCT wr.* FROM wiki_word_root_rela wwrr 
        		LEFT JOIN wiki_word_root_learn_template wwrlt ON wwrr.word_root_id=wwrlt.word_root_id  
        		LEFT JOIN wiki_word wr ON wwrr.word_id=wr.id 
        		WHERE wr.learn_flag=1 
        		AND `group`=%d
        		AND wr.id NOT IN (SELECT DISTINCT word_id FROM user_wiki_word_learn WHERE user_id=%d AND `group`=%d)
        		ORDER BY wwrlt.id ASC,wr.id ASC 
        		LIMIT 1";
        return $this->query($sql,$group,$userId,$group);		
    }

    public function getRootInfo($wordId){
    	$sql = "SELECT wwr.*,wwrr.true_root FROM wiki_word_root_rela wwrr 
				LEFT JOIN wiki_word_root wwr ON wwrr.word_root_id=wwr.id
				WHERE wwrr.word_id=%d order by wwr.morpheme_type desc";
		return $this->query($sql,$wordId);
    }

	public function addUserLearnProgress($userId,$group,$startDate){
    	$sql = "INSERT INTO user_wiki_word_learn_progress (user_id,`group`,is_finished,start_date) VALUES(%d,%d,0,'%s')";
    	return $this->execute($sql,$userId,$group,$startDate);
    }

    public function setUserLearnProgressFinished($finishDate,$userId,$group){
    	$sql = "UPDATE user_wiki_word_learn_progress SET is_finished=1,finish_date='%s' WHERE user_id=%d AND `group`=%d";
    	return $this->execute($sql,$finishDate,$userId,$group);
    }

    public function countTodayLearnt($userId,$today,$group){
    	$sql = "SELECT COUNT(uwwl.id) count_today 
    			FROM user_wiki_word_learn uwwl 
    	 		WHERE uwwl.user_id=%d AND uwwl.learn_date='%s' AND uwwl.`group`=%d";
    	return $this->query($sql,$userId,$today,$group);
    }

    public function checkUserLearnProgress($userId,$group){
    	$sql = "SELECT is_finished,start_date,finish_date FROM user_wiki_word_learn_progress WHERE user_id=%d AND `group`=%d";
    	return $this->query($sql,$userId,$group);
    }

    public function countUserLearnCrazy($userId,$group,$today){
    	$sql = "SELECT count(1) crazy_count FROM user_wiki_word_learn_crazy WHERE user_id=%d AND `group`=%d AND learn_date='%s'";
    	return $this->query($sql,$userId,$group,$today);
    }

    public function checkUserLearnCrazy($userId,$group,$today){
    	$sql = "SELECT is_crazy FROM user_wiki_word_learn_crazy WHERE user_id=%d AND `group`=%d AND learn_date='%s'";
    	return $this->query($sql,$userId,$group,$today);
    }

    public function addUserLearnCrazy($userId,$group,$today,$isCrazy){
    	$sql = "INSERT INTO user_wiki_word_learn_crazy (user_id,`group`,learn_date,is_crazy) VALUES(%d,%d,'%s',%d)";
    	return $this->execute($sql,$userId,$group,$today,$isCrazy);
    }

    public function setUserLearnCrazy($isCrazy,$userId,$group,$today){
    	$sql = "UPDATE user_wiki_word_learn_crazy SET is_crazy=%d WHERE user_id=%d AND `group`=%d AND learn_date='%s'";
    	return $this->execute($sql,$isCrazy,$userId,$group,$today);
    }

    public function countLearnDays($userId,$group){
    	$sql = "SELECT COUNT(DISTINCT learn_date) count_learn_days FROM user_wiki_word_learn_crazy WHERE user_id=%d AND `group`=%d";
        return $this->query($sql,$userId,$group);
    }

    public function countCrazyDays($userId,$group){
    	$sql = "SELECT COUNT(DISTINCT learn_date) count_crazy_days FROM user_wiki_word_learn_crazy WHERE user_id=%d AND `group`=%d AND is_crazy=1";
        return $this->query($sql,$userId,$group);
    }

    public function countMostOneDay($userId,$group){
    	$sql = "SELECT COUNT(1) count_most FROM user_wiki_word_learn WHERE user_id=%d AND `group`=%d GROUP BY learn_date ORDER BY count_most DESC LIMIT 1";
        return $this->query($sql,$userId,$group);
    }

    public function deleteLearnWords($userId, $group){
        $sql = "DELETE FROM user_wiki_word_learn WHERE user_id=%d AND `group`=%d";
        return $this->execute($sql,$userId,$group);
    }

    public function deleteLearnDaily($userId, $group){
        $sql = "DELETE FROM user_wiki_word_learn_crazy WHERE user_id=%d AND `group`=%d";
        return $this->execute($sql,$userId,$group);
    }

    public function deleteLearnProgress($userId, $group){
        $sql = "DELETE FROM user_wiki_word_learn_progress WHERE user_id=%d AND `group`=%d";
        return $this->execute($sql,$userId,$group);
    }

    public function getLearntWords($userId, $group, $learnFlag, $start, $pageSize){
        $sql = "SELECT wr.id,wr.word,wr.translation,wr.learn_by_root FROM user_wiki_word_learn uwwl LEFT JOIN wiki_word wr ON uwwl.word_id=wr.id WHERE uwwl.user_id = %d AND uwwl.`group` = %d AND uwwl.learn_status = %d ORDER BY wr.id ASC LIMIT %d,%d";
        return $this->query($sql, $userId, $group, $learnFlag, $start, $pageSize);
    }

    public function countLearntWords($userId, $group, $learnFlag){
        $sql = "SELECT count(wr.id) as count FROM user_wiki_word_learn uwwl LEFT JOIN wiki_word wr ON uwwl.word_id=wr.id WHERE uwwl.user_id = %d AND uwwl.`group` = %d AND uwwl.learn_status = %d";
        return $this->query($sql, $userId, $group, $learnFlag);
    }

    public function updateWordLearnStatus($userId, $group, $wordId, $learnFlag){
        $sql = "UPDATE user_wiki_word_learn SET learn_status = %d WHERE user_id = %d AND `group` = %d AND word_id = %d";
        return $this->execute($sql, $learnFlag, $userId, $group, $wordId);
    }
    
}