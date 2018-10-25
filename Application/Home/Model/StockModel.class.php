<?php
namespace Home\Model;
use Think\Model;
class StockModel extends Model {
    protected $connection = 'DB_CONFIG3';//调用配置文件中的数据库配置1
    protected $autoCheckFields =false;//模型和数据表无需一一对应

    public function getStockHistoryDaily($stock){
    	$table = "ts_history_data_0";
    	$category = substr($stock,0,1);
    	if($category == 0){
    		$table = "ts_history_data_0";
    	}else if($category == 3){
    		$table = "ts_history_data_3";
    	}else if($category == 6){
    		$table = "ts_history_data_6";
    	}

        $sql = "SELECT * FROM ".$table." WHERE stock_code = '%s' ORDER BY trade_date asc";
        return $this->query($sql,$stock);
    }
	
	public function getAllStocks(){
        $sql = "SELECT ts_code,`name`,`area`,industry,list_date FROM ts_stock_list ORDER BY id asc";
        return $this->query($sql);
    }
}