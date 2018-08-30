<?php
namespace Home\Model;
use Think\Model;
class CollectRootModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应

    public function checkIfCollect($rootId,$userId){
        $sql = "SELECT id from collect_root_user_rela where user_id=%d and root_id=%d";
        return $this->query($sql,(int)$userId,(int)$rootId);
    }

    public function showCollects($rootId){
        $sql = "SELECT show_count from collect_root where root_id=%d";
        return $this->query($sql,$rootId);
    }
	
	public function addCollectRoot($rootId){
        $sql = "insert into collect_root(root_id,show_count,real_count) values(%d,1,1)";
        $this->execute($sql,$rootId);
    }

    public function addCollectRootUserRela($rootId,$userId){
        $sql = "insert into collect_root_user_rela(root_id,user_id) values(%d,%d)";
        $this->execute($sql,$rootId,$userId);
    }

    public function updateCollectRoot($rootId,$num){
        $sql = "update collect_root set show_count=show_count+%d,real_count=real_count+%d where root_id=%d";
        $this->execute($sql,$num,$num,$rootId);
    }

    public function delCollectRootUserRela($rootId,$userId){
        $sql = "DELETE FROM collect_root_user_rela WHERE root_id=%d AND user_id=%d";
        $this->execute($sql,$rootId,$userId);
    }

    public function showRootCollectsByUser($userId){
        $sql = "SELECT wwr.id,wwr.word_root FROM collect_root_user_rela crur LEFT JOIN wiki_word_root wwr ON crur.root_id=wwr.id WHERE user_id=%d ORDER BY crur.id desc";
        return $this->query($sql,$userId);
    }

    public function showVideoWordCollectsByUserPaging($userId,$start,$pageSize){
        $sql = "SELECT ww.* FROM user_video_word_learn uvwl LEFT JOIN wiki_word ww ON uvwl.word_id=ww.id WHERE uvwl.user_id=%d ORDER BY uvwl.id DESC LIMIT %d,%d";
        return $this->query(intval($userId),intval($start),intval($pageSize));
    }

    public function countVideoWordCollects($userId){
        $sql = "SELECT count(1) as count FROM user_video_word_learn uvwl LEFT JOIN wiki_word ww ON uvwl.word_id=ww.id WHERE uvwl.user_id=%d";
        return $this->query($sql, $userId);
    }
}

