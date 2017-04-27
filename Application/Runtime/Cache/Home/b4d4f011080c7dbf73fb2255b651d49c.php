<?php if (!defined('THINK_PATH')) exit();?>
 <!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">  
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>单词列表</title>
    <link ref="shortcut icon" href="/morph_favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="/Public/css/base_morph_mobile.css" rel="stylesheet">
    <style type="text/css">
        .root-view{margin:2% 2% 2% 4%;font-size: 14px;}
        .word-view{margin:2% 2% 2% 2%;}
    </style>
    
</head>
<body class="global">

 <!-- 导航 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Public/css/weui/dist/style/weui.min.css"/>
    <style type="text/css">
        .global{background: #f8f8f8;}
        .btm-nav{position:fixed;z-index:1000;width:100%;height:9%;bottom:0px;margin-right:0.5%;box-shadow:0 2px 2px 0 rgba(0,0,0,0.1);margin:80% 0% 0% 0%;}
        .info-bar{position:fixed;z-index:1000;width:100%;padding:5px 5px 5px 5px;background:white;box-shadow:0 1px 2px 0 rgba(0,0,0,0.1);background-color:black;}
        .operator-info{font-size:12px;font-weight:bold;color:#337ab7;}
        .operator-info .favico{margin:10px 5px 10px 20px;}
        .operator-info .brand{margin:10px 20px 10px 0px;}
        .operator-info .role{margin:10px 20px 10px 20px;float:right;}
    </style>

<div class="info-bar">
    <div class="operator-info">
        <span class="favico"><img width="40px" height="40px" src="/Public/img/morphlogo.png"/></span>
        <span class="brand" style="font-family:Helvetica;">ODIN'S EYE</span>
		<span class="role">
            <a href="searchMorpheme" style="margin-right:10px;">
                <span class="glyphicon glyphicon-search" aria-hidden="true" style="margin-right:3px;"></span>搜索
            </a>
            <a href="showMorphemesByCapital">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true" style="margin-right:3px;"></span>列表
            </a>
        <span>
    </div>
</div>

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

    <script type="text/javascript">
        $(".weui_tabbar_item").click(function(){
            $(".weui_tabbar_item").removeClass("weui_bar_item_on");
            $(this).addClass("weui_bar_item_on");       
        });

    </script>


<div class="main-view">
    <div class="root-view">
        <div class="weui_cells_title bold-font">词根：<?php echo ($morpheme[0]["word_root"]); ?></div>
        <div class="weui_cells_title ">
            <span class="bold-font">来源：</span>
            <?php switch($morpheme[0]["origin"]): case "1": ?>Latin<?php break;?>
                <?php case "2": ?>Greek<?php break;?>
                <?php default: ?>Unknown<?php endswitch;?>
        </div>
        <div class="weui_cells_title "><span class="bold-font">释义：</span><?php echo ($morpheme[0]["meaning"]); ?></div>
    </div>

    <div class="word-view">
        <?php if(!empty($wordList)): ?><div class="weui-panel weui-panel_access">
                <div class="weui-panel__hd">单词列表</div>
                <div class="weui-panel__bd">
                <?php if(is_array($wordList)): foreach($wordList as $key=>$word): ?><div class="weui-media-box weui-media-box_text">
                        <h4 class="weui-media-box__title">
                            <?php echo ($num++); ?>.&nbsp;<?php echo ($word["word"]); ?>
                            <span class="weui-badge" style="margin-left: 5px;font-size:8px;background-color:#5091d4;">
                                <?php switch($word["freq_level"]): case "1": ?>极高<?php break;?>
                                    <?php case "2": ?>&nbsp;高&nbsp;<?php break;?>
                                    <?php case "3": ?>&nbsp;中&nbsp;<?php break;?>
                                    <?php case "4": ?>&nbsp;低&nbsp;<?php break;?>
                                    <?php case "5": ?>极低<?php break;?>
                                    <?php default: endswitch;?>
                            </span>
                        </h4>
                        <p class="weui-media-box__desc"><?php echo ($word["translation"]); ?></p>
                        <ul class="weui-media-box__info">
                            <li class="weui-media-box__info__meta">其他</li>
                            <li class="weui-media-box__info__meta weui-media-box__info__meta_extra">
                                <a href="showSimilarWords?wId=<?php echo ($word["id"]); ?>">查看关联单词</a>
                            </li>
                        </ul>
                    </div><?php endforeach; endif; ?>
                </div>
            </div>
        <?php else: ?>
           <div style="font-size:16px;margin-top:200px;" class="text-center">这个词根或词缀还没有单词哦~</div><?php endif; ?>
    </div>
</div>
</div>


<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

</body>
</html>