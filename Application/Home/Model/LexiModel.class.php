<?php
namespace Home\Model;
use Think\Model;
class LexiModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应
    
    public function total($instId){
    	$sql = "select count(1) total from classoa_classroom where inst_id=%d and status=0";
        return $this->query($sql,$instId);
    }

    public function showClassrooms($instId,$start,$pageSize){
        $sql = "select * from classoa_classroom where inst_id=%d and status=0 order by classroom_id desc limit %d,%d";
        return $this->query($sql,$instId,$start,$pageSize);
    }

    public function deleteClassroom($instId,$classroomId){
    	$sql = "update classoa_classroom set status=1 where classroom_id=%d and inst_id=%d";
    	return $this->execute($sql,$classroomId,$instId);
    }

    //--------------------word----------------------------
    public function saveWord($word,$meaning){
        $sql = "insert into lexi_word(word,meaning) values('%s','%s')";
        $this->execute($sql,$word,$meaning);
        $queryIdSql = "SELECT @@IDENTITY as id";
        return $this->query($queryIdSql);
    }

    public function saveWordAndRootRela($wordId,$wordRootId){
        $sql = "insert into lexi_word_root_rela(word_id,word_root_id) values(%d,%d)";
        $this->execute($sql,$wordId,$wordRootId);
    }

    public function showWordsByRoot($wordRootId){
        $sql = "SELECT lw.* FROM lexi_word_root_rela lwrr LEFT JOIN lexi_word lw ON lwrr.word_id=lw.id WHERE lwrr.word_root_id=%d order by lw.word asc";
        return $this->query($sql,$wordRootId);
    }

    //--------------------category------------------------
    public function showCategories(){
        $sql = "select id,category_name from lexi_word_root_category";
        return $this->query($sql);
    }
    //-------------------word_root------------------------
    public function saveWordRoot($wordroot,$meaning,$origin,$category){
        $sql = "insert into lexi_word_root(word_root,meaning,origin,category_id) values('%s','%s',%d,%d)";
        return $this->execute($sql,$wordroot,$meaning,$origin,$category);
    }

    public function showWordroots(){
        $sql = "select id,word_root from lexi_word_root";
        return $this->query($sql);
    }
}

