<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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

	<title>ODIN'S EYE 词根词典</title>
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
    <form action="showWordsByMorpheme" method="post" id="showWordsByMorpheme">
        <input type="hidden" name="morphemeId" id="morphemeId"/>
    </form>
    
    <div class="fuzzy-search">
        <!-- fuzzy search -->
        <div class="weui-search-bar" id="searchBar">
            <form class="weui-search-bar__form">
                <div class="weui-search-bar__box">
                    <i class="weui-icon-search"></i>
                    <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索词根" required  />
                    <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
                </div>
                <label class="weui-search-bar__label" id="searchText">
                    <i class="weui-icon-search"></i>
                    <span>搜索词根</span>
                </label>
            </form>
            <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel" style="color:#1875b7;">取消</a>
        </div>
        <div class="weui-cells searchbar-result" id="searchResult" style="margin-top:0px;display:none;width:100%;background-color:#f3f2f2;font-family: -apple-system-font,Helvetica Neue,Helvetica,sans-serif;font-size:14px;">
            <div class="weui-cell weui-cell_access">
            <!--
                <div class="weui-cell__bd weui-cell_primary">
                    <p>实时搜索文本</p>
                </div>
            -->
            </div>
            
        </div>
    </div>

</div>

	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/zepto.min.js"></script>
    <script type="text/javascript" src="/Public/js/weixin/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        $(function(){
            var $searchBar = $('#searchBar'),
                $searchResult = $('#searchResult'),
                $searchText = $('#searchText'),
                $searchInput = $('#searchInput'),
                $searchClear = $('#searchClear'),
                $searchCancel = $('#searchCancel');

            function hideSearchResult(){
                $searchResult.hide();
                $searchInput.val('');
            }
            function cancelSearch(){
                hideSearchResult();
                $searchBar.removeClass('weui-search-bar_focusing');
                $searchText.show();
            }

            $searchBar.on('click', function(){
                $searchBar.addClass('weui-search-bar_focusing');
                $searchInput.focus();
            });
            $searchInput
                .on('blur', function () {
                    if(!this.value.length) cancelSearch();
                })
                .on('input', function(){
                    if(this.value.length > 8){
                        return;
                    }
                    if(this.value.length) {
                        $.ajax({
                            type: "POST",
                            url: "fuzzySearchMorph",
                            data: "fuzzyMorph="+this.value,
                            success: function(msg){
                                if(msg == 'noresult'){
                                    return;
                                }
                                var dataObj = eval("("+msg+")");
                                var html = '';
                                $.each(dataObj,function(idx,item){   
                                    html   +=   '<div class="weui-cell weui-cell_access" onclick="showWords('+item.id+')">'+
                                                    '<div class="weui-cell__bd weui-cell_primary">'+
                                                        '<p><span class="bold-font">词根：</span>'+item.word_root +'</p>'+
                                                        '<p><span class="bold-font">释义：</span>'+item.meaning +'</p>'+
                                                    '</div>'+
                                                '</div>';
                                    
                                });
                                $searchResult.show();
                                $searchResult.empty();
                                $searchResult.append(html);
                           }
                        });
                    } else {
                        $searchResult.hide();
                    }
                })
            ;
            $searchClear.on('click', function(){
                hideSearchResult();
                $searchInput.focus();
            });
            $searchCancel.on('click', function(){
                cancelSearch();
                $searchInput.blur();
            });
        });

        function showWords(morphemeId){
            var $searchBar = $('#searchBar'),
                $searchResult = $('#searchResult'),
                $searchText = $('#searchText'),
                $searchInput = $('#searchInput'),
                $searchClear = $('#searchClear'),
                $searchCancel = $('#searchCancel');

            $searchResult.hide();
            $searchInput.val('');
            $searchBar.removeClass('weui-search-bar_focusing');
            $searchText.show();
            $("#morphemeId").val(morphemeId);
            $("#showWordsByMorpheme").submit();
        }
    </script>
</body>
</html>