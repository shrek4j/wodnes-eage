<?php
namespace Home\Model;
use Think\Model;
class MusicModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应

    public function getMusicUrl(){
        $sql = "SELECT url,wanna_say from music_list where is_use=1 limit 0,1";
        return $this->query($sql);
    }
	
}

