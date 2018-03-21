<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    

    public function onLogin($code){
        $count = 0;
        while(($openid == null || $openid == "") && $count < 10){
            $APPID = "wx4ffd6dcb1f7a62c4";
            $SECRET = "3c53ecb5f0b6a6517ad9224a22db32d3";
            $wxurl = "https://api.weixin.qq.com/sns/jscode2session?appid=".$APPID."&secret=".$SECRET."&js_code=".$code."&grant_type=authorization_code";

            // 初始化一个 cURL 对象 
            $curl = curl_init(); 
            // 设置你需要抓取的URL 
            curl_setopt($curl, CURLOPT_URL, $wxurl); 
            // 设置header 响应头是否输出
            curl_setopt($curl, CURLOPT_HEADER, 0); 
            // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
            // 1如果成功只将结果返回，不自动输出任何内容。如果失败返回FALSE 
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
            // 运行cURL，请求网页 
            $wxdata = curl_exec($curl); 
            // 关闭URL请求 
            curl_close($curl);

            $wxdataRslt = json_decode($wxdata);
            $openid = $wxdataRslt->openid;
            count++;
        }
        
        $userModel = new \Home\Model\UserModel();
        $user = $userModel->checkIfUserExist($openid);
        if(count($user) <= 0){
            $userModel = new \Home\Model\UserModel();
            $user = $userModel->addUser($openid);
        }
        $userId = $user[0]['id'];
        $result = array("sfz"=>$userId);
        


        $data = json_encode($result);
        $this->ajaxReturn($data);
    }

}