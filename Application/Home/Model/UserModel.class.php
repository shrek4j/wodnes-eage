<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    protected $connection = 'DB_CONFIG2';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应

    public function checkIfUserExist($openid){
        $sql = "SELECT id from user where openid='%s'";
        return $this->query($sql,$openid);
    }
	
	public function addUser($openid){
        $sql = "insert into user(openid) values('%s')";
        $this->execute($sql,$openid);
        $queryIdSql = "SELECT @@IDENTITY as id";
        return $this->query($queryIdSql);
    }

    public function checkCoins($userId){
        $sql = "SELECT coins FROM user WHERE id = %d";
        return $this->query($sql,$userId);
    }

    public function changeCoin($coin, $userId){
        $sql = "UPDATE user SET coins = coins + %d WHERE id = %d";
        $this->execute($sql,$coin,$userId);
    }
}

