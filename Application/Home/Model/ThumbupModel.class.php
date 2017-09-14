<?php
namespace Home\Model;
use Think\Model;
class ThumbupModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应

    public function checkIfThumbup($articleId,$userId){
        $sql = "SELECT id from thumbup_article_user_rela where user_id=%d and article_id=%d";
        return $this->query($sql,(int)$userId,(int)$articleId);
    }

    public function showThumbups($articleId){
        $sql = "SELECT show_count from thumbup_article where article_id=%d";
        return $this->query($sql,$articleId);
    }
	
	public function addThumbupArticle($articleId){
        $sql = "insert into thumbup_article(article_id,show_count,real_count) values(%d,1,1)";
        $this->execute($sql,$articleId);
    }

    public function addThumbupArticleUserRela($articleId,$userId){
        $sql = "insert into thumbup_article_user_rela(article_id,user_id) values(%d,%d)";
        $this->execute($sql,$articleId,$userId);
    }

    public function updateThumbupArticle($articleId,$num){
        $sql = "update thumbup_article set show_count=show_count+%d,real_count=real_count+%d where article_id=%d";
        $this->execute($sql,$num,$num,$articleId);
    }

    public function delThumbupArticleUserRela($articleId,$userId){
        $sql = "DELETE FROM thumbup_article_user_rela WHERE article_id=%d AND user_id=%d";
        $this->execute($sql,$articleId,$userId);
    }
}

