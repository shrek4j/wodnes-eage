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

	<title>词根列表</title>
    <link ref="shortcut icon" href="/morph_favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="/Public/css/base_morph_mobile.css" rel="stylesheet">
    <style type="text/css">
        .main-view-inner{margin:4% 4% 4% 4%;}
        .main-view-inner-bar{margin:0% 0% 2% 0%;}
        .word-root-list{margin-top:4%;}
        .fuzzy-search{margin-top:2%;}
        #weui-picker-confirm{color:#1875b7;}
        .weui-picker__item{font-size:18px;}
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

    <div class="main-view-inner">
        <div class="main-view-inner-bar">

            <form action="showMorphemesByCapital" method="post" id="showMorphemesByCapital">
                <input type="hidden" name="capital" id="capital" value="<?php echo ($capital); ?>"/>
            </form>
            <form action="showWordsByMorpheme" method="post" id="showWordsByMorpheme">
                <input type="hidden" name="morphemeId" id="morphemeId"/>
            </form>

            <div class="alphabet-search">
                <!-- search alphabetically -->
                <a href="javascript:;" class="weui-btn weui-btn_default" style="font-size:14px;" id="showPicker">选择首字母查询：<span id="capitalLetter"><?php echo ($capital); ?></span></a>
            </div>
            
        </div>

        <div class="word-root-list">
            <?php if(!empty($morphList)): if(is_array($morphList)): foreach($morphList as $key=>$morph): ?><div class="weui-panel">
                    <div class="weui-panel__bd">
                        <div class="weui-media-box weui-media-box_text" onclick="showWords(<?php echo ($morph["id"]); ?>);">
						
                            <h4 class="weui-media-box__title"><?php echo ($num++); ?>.&nbsp;<?php echo ($morph["word_root"]); ?></h4>
							<ul class="weui-media-box__info">
								<li class="weui-media-box__info__meta" style="color:#807e7e;">来源：
									<?php switch($morph["origin"]): case "1": ?>Latin<?php break;?>
										<?php case "2": ?>Greek<?php break;?>
										<?php default: ?>Unknown<?php endswitch;?>
								</li>
							</ul>
                            <ul class="weui-media-box__info">
                                <li class="weui-media-box__info__meta" style="color:#807e7e;">释义：<?php echo ($morph["meaning"]); ?></li>
                            </ul>
                        </div>
                    </div>
                </div><?php endforeach; endif; ?>
            <?php else: ?>
               <div style="font-size:14px;margin-top:200px;" class="text-center">此字母下没有词根哦~</div><?php endif; ?>
        </div>
    </div>
</div>

	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/zepto.min.js"></script>
    <script type="text/javascript" src="/Public/js/weixin/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="/Public/js/weixin/weui.min.js"></script>

	<script type="text/javascript">

        function showWords(morphemeId){
			$("#morphemeId").val(morphemeId);
			$("#showWordsByMorpheme").submit();
		}

		$('.select-capital').change(function(){
			$("#capital").val($("#selectCapital").find("option:selected").val());
			$("#showMorphemesByCapital").submit();
		});


        $('#showPicker').on('click', function () {
            weui.picker([{
                label: 'a',
                value: 'a'
            }, {
                label: 'b',
                value: 'b'
            }, {
                label: 'c',
                value: 'c'
            },{
                label: 'd',
                value: 'd'
            }, {
                label: 'e',
                value: 'e'
            }, {
                label: 'f',
                value: 'f'
            }, {
                label: 'g',
                value: 'g'
            }, {
                label: 'h',
                value: 'h'
            }, {
                label: 'i',
                value: 'i'
            }, {
                label: 'j',
                value: 'j'
            }, {
                label: 'k',
                value: 'k'
            }, {
                label: 'l',
                value: 'l'
            }, {
                label: 'm',
                value: 'm'
            }, {
                label: 'n',
                value: 'n'
            }, {
                label: 'o',
                value: 'o'
            }, {
                label: 'p',
                value: 'p'
            }, {
                label: 'q',
                value: 'q'
            }, {
                label: 'r',
                value: 'r'
            }, {
                label: 's',
                value: 's'
            }, {
                label: 't',
                value: 't'
            }, {
                label: 'u',
                value: 'u'
            }, {
                label: 'v',
                value: 'v'
            }, {
                label: 'w',
                value: 'w'
            }, {
                label: 'x',
                value: 'x'
            }, {
                label: 'y',
                value: 'y'
            }, {
                label: 'z',
                value: 'z'
            }

            ], {
                onChange: function (result) {

                },
                onConfirm: function (result) {
                    $("#capital").val(result);
                    $("#showMorphemesByCapital").submit();
                },
                id: 'singleLinePicker'
            });
        });
		
    </script>
</body>
</html>