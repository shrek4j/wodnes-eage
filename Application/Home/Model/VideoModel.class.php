<?php
namespace Home\Model;
use Think\Model;
class VideoModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应

    public function getVideoListPaging($start,$num){
        $sql = "SELECT * FROM video_list ORDER BY DATE DESC LIMIT %d,%d";
        return $this->query($sql,$start,$num);
    }
	
}

