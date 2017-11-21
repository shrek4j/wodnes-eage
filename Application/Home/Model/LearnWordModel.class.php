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
                LEFT JOIN wiki_word_root_rela wwrr ON uwwl.word_id=wwrr.word_id
                LEFT JOIN wiki_word_root_learn_template wwrlt ON wwrr.word_root_id=wwrlt.word_root_id
				WHERE uwwl.user_id =%d 
				AND wwrlt.`group`=%d";
        return $this->query($sql,$userId,$group);
    }

    public function saveLearnStatus($wordId,$userId,$learnStatus,$learnDate){
    	$sql = "INSERT INTO user_wiki_word_learn(word_id,user_id,learn_status,learn_date) VALUES(%d,%d,%d,'%s')";
    	$this->execute($sql,$wordId,$userId,$learnStatus,$learnDate);
    }

    public function getNextWord($userId,$group){
    	$sql = "SELECT wr.* FROM wiki_word_root_rela wwrr 
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
    	$sql = "SELECT COUNT(uwwl.id) countToday 
    			FROM user_wiki_word_learn uwwl 
                LEFT JOIN wiki_word_root_rela wwrr ON uwwl.word_id=wwrr.word_id
                LEFT JOIN wiki_word_root_learn_template wwrlt ON wwrr.word_root_id=wwrlt.word_root_id
    	 		WHERE uwwl.user_id=%d AND uwwl.learn_date='%s' AND wwrlt.`group`=%d";
    	return $this->query($sql,$userId,$today,$group);
    }

    public function checkUserLearnProgress($userId,$group){
    	$sql = "SELECT is_finished,start_date,finish_date FROM user_wiki_word_learn_progress WHERE user_id=%d AND `group`=%d";
    	return $this->query($sql,$userId,$group);
    }
}