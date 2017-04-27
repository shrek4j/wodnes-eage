<?php
namespace Home\Behaviors;
class ClientCheckBehavior extends \Think\Behavior{

    //行为执行入口
    public function run(&$return){
    	
        if (ismobile()) {
            //设置默认默认主题为 Mobile
            C('DEFAULT_V_LAYER','Mobile');
        }
        $return = true;
    }

}