<?php
namespace Home\Behaviors;
class ClientCheckBehavior extends \Think\Behavior{

    //行为执行入口
    public function run(&$return){
    	
    	//设置默认默认主题为 Mobile
        C('DEFAULT_V_LAYER','Mobile');

        if (ismobile()) {
        	session('isMobile','1');
        	//I('session.isMobile',1); 
        }else{
        	session('isMobile','0');
        	//I('session.isMobile',0); 
        }
        $return = true;
    }

}