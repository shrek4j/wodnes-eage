<?php
namespace Home\Controller;
use Think\Controller;
class StockController extends Controller {
    
    public function getAllStocks(){
		$stockModel = new \Home\Model\StockModel();
        $stockList = $stockModel->getAllStocks();

        $result = array("stockList"=>$stockList);
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

    public function getStockHistoryDaily($stock,$startDate,$endDate){
        $stockModel = new \Home\Model\StockModel();
        $dailyHis = $stockModel->getStockHistoryDaily($stock,$startDate,$endDate);

        $result = array("dailyHis"=>$dailyHis);
        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

}