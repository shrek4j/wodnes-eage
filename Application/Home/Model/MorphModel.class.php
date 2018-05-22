<?php
namespace Home\Model;
use Think\Model;
class MorphModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应
    
	#UPDATE wiki_word SET has_translation = 1 WHERE translation NOT LIKE ''
    #UPDATE wiki_word SET has_translation = 0 WHERE translation LIKE ''
    public function showWordsByMorpheme($morphemeId){
        $sql = "SELECT ww.id,ww.word,ww.translation,ww.freq_level,ww.all_ielts_freq,ww.learn_by_root,ww.kaoyan_tag FROM wiki_word_root_rela wwrr LEFT JOIN wiki_word ww ON wwrr.word_id=ww.id WHERE wwrr.word_root_id=%d AND ww.has_translation = 1 order by ww.log_freq desc,LENGTH(ww.word) asc,ww.word asc";
        return $this->query($sql,$morphemeId);
    }

    public function showWordsByMorphemePaging($morphemeId,$start,$num){
        $sql = "SELECT ww.id,ww.word,ww.translation,ww.freq_level,ww.all_ielts_freq,ww.learn_by_root,ww.kaoyan_tag FROM wiki_word_root_rela wwrr LEFT JOIN wiki_word ww ON wwrr.word_id=ww.id WHERE wwrr.word_root_id=%d AND ww.has_translation = 1 order by ww.log_freq desc,LENGTH(ww.word) asc,ww.word asc limit %d,%d";
        return $this->query($sql,$morphemeId,$start,$num);
    }

    public function showSimilarWords($wId){
        $sql = "SELECT ww.word,ww.translation,ww.freq_level,ww.freq FROM wiki_word_rela wwr LEFT JOIN wiki_word ww ON wwr.similar_word_id=ww.id WHERE wwr.word_id=%d order by ww.log_freq desc,LENGTH(ww.word) asc,ww.word asc";
        return $this->query($sql,$wId);
    }
	
	public function showMorphemeById($morphemeId){
        $sql = "select id,word_root,meaning,origin,rank,audio,audio_recorder from wiki_word_root where id = %d";
        return $this->query($sql,$morphemeId);
    }

    public function showMorphemeByCapital($capital){
        $sql = "select id,word_root,meaning,origin,rank from wiki_word_root where capital_letter = '%s' order by total_log_freq desc";
		return $this->query($sql,$capital);
    }

    public function showMorphemeByCapitalPaging($capital,$start,$num){
        $sql = "select id,word_root,meaning,origin,rank from wiki_word_root where capital_letter = '%s' order by total_log_freq desc limit ".$start.",".$num;
        return $this->query($sql,$capital,$start,$num);
    }

    //只查询词根，忽略前缀
    public function showAllMorphemes($start,$num){
        $sql = "select id,word_root,meaning,origin,rank from wiki_word_root where morpheme_type=0 order by rank asc limit ".$start.",".$num;
        return $this->query($sql);
    }

    public function fuzzySearchMorph($fuzzyMorph){
        $sql = "select id,word_root,meaning,origin from wiki_word_root where (word_root like '".$fuzzyMorph."%' or word_root like '%,".$fuzzyMorph."%') limit 5";
        return $this->query($sql);
    }

    public function fuzzySearchMorphAndWord($fuzzyMorph){
        $sql = "SELECT id,word_root,meaning,origin FROM wiki_word_root WHERE (word_root LIKE '".$fuzzyMorph."%' OR word_root LIKE '%,".$fuzzyMorph."%') LIMIT 5 UNION SELECT wwr.id,wwr.word_root,wwr.meaning,origin FROM wiki_word ww INNER JOIN wiki_word_root_rela wwrr ON ww.id = wwrr.word_id INNER JOIN wiki_word_root wwr ON wwrr.word_root_id=wwr.id WHERE ww.word = '".$fuzzyMorph."' LIMIT 5";
        return $this->query($sql);
    }
}

