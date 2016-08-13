<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<?php  if($weixin==1) { ?>
<html lang="zh" class="wx">
<?php  } else { ?>
<html lang="zh" class="pu">
<?php  } ?>
<head>
<meta charset="UTF-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
<link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/public.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/option_box.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/public_disbox.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/public_headmessage.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/head_nav.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/user-list.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/user_box.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/home_page.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/herd_hello.css" rel="stylesheet"/>
<link href="../addons/jy_ppp/css/common_alert.css" rel="stylesheet"/>
<style>
.description{position:absolute;bottom:0;font-size:13px;color:#fff;text-align:center;width:100%}
.index_list {
    padding: 2% 0% 4% 0%;
}
.index_list ul li {
    display: inline-block;
    width: 32.3%;
    max-width: 32.3%;
}
.mv_box{
    border-radius: 5px;
    height:128px;
    overflow: hidden;
    position: relative;
}
.mv_box img {
    width: 100%;
    max-height:128px;
    height: 128px;
    vertical-align: middle;
}
.mv_box span.default {
    background: url('../addons/jy_ppp/images/gray_heart.png') no-repeat scroll center center;
    background-size: 100%;
}
.mv_box span.like{background: url('../addons/jy_ppp/images/red_heart.png') no-repeat scroll center center;
    background-size: 100%;}
.mv_box span {
    display: block;
    width: 35px;
    height: 35px;
    position: absolute;
    left: 5px;
    top: 5px;
    z-index: 1;
}
.top_mess .mess_hide img {
    vertical-align: middle;
}
.top_mess .mess_hide {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #F1668D;
    color: #fff;
    text-align: center;
    line-height: 60px;
    opacity: .8;
}
.mess_fixed{
    position: fixed;
    z-index: 5;
    right: 10px;
    top: 120px;
}
.mess_fixed .none{display: none;}

.top_mess{
    
}
.mess_show{
    width: 180px;height: 50px;border-radius: 30px;background-color: #F1668D;color: #fff;text-align: center;line-height: 50px;opacity: .8;
}
.mess_fixed img {
    background: none;
}
.showNum{    position: absolute;
    top: 8px;
    right: 9pt;
    display: inline-block;
    width: 23px;
    height: 23px;
    border-radius: 50%;
    background-color: #e8565b;
    text-align: center;
    font-size: 11px;
    line-height: 23px;}
</style>
</head>
<body class="" isshownewyearad="0" data-jsid="" data-userid="430431076" yy="no">
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template(menu, TEMPLATE_INCLUDEPATH)) : (include template(menu, TEMPLATE_INCLUDEPATH));?>
<section id="index_section" class="home_page">
<!-- <div class="filtrate">
    <ol class="filtrate_con">
        <a style="display:inline-block;width:64px;height:64px;background: url(http://px1.youyuan.com/resize/photo/n/n/n/90/110/201510/03/10/30/1443839401102AC61D1E_c.jpg) center center no-repeat;background-size: cover;position:relative" href="../addons/jy_ppp/index_1.html"><p class="description">活跃用户</p></a>
    
        <a style="display:inline-block;width:64px;height:64px;background: url(http://px1.youyuan.com/resize/photo/n/n/n/90/110/201509/08/13/34/1441690488503A4E29B1_c.jpg) center center no-repeat;background-size: cover;position:relative" href="../addons/jy_ppp/index_1.html"><p class="description">小巧玲珑</p></a>
    
        <a style="display:inline-block;width:64px;height:64px;background: url(http://px1.youyuan.com/resize/photo/n/n/n/90/110/201511/12/12/13/1447301596239A2DDC57_c.jpg) center center no-repeat;background-size: cover;position:relative" href="../addons/jy_ppp/index_1.html"><p class="description">活跃用户</p></a>
    
        <a style="display:inline-block;width:64px;height:64px;background: url(http://px1.youyuan.com/resize/photo/n/n/n/90/110/201505/23/19/10/1432379454092A048E34_c.png) center center no-repeat;background-size: cover;position:relative" href="../addons/jy_ppp/index_1.html"><p class="description">收入颇丰</p></a>
    
        <a style="display:inline-block;width:64px;height:64px;background: url(http://px1.youyuan.com/resize/photo/n/n/n/90/110/201511/18/11/32/1447817572681ABFD8C0_c.jpg) center center no-repeat;background-size: cover;position:relative" href="../addons/jy_ppp/index_1.html"><p class="description">活跃用户</p></a>
    </ol>
</div> -->

<!--公告 消息-->
<!-- <div id="noticeDiv" class="notice">
    <img src="../addons/jy_ppp/images/blue_voice.png"/>
    <div id="notice_box" class="notice_box">
        <ol id="ul1" data-key="H5-A1" class="person">
            <li class="adli"><a href="../addons/jy_ppp/huafei.html" class="adStatistics" activeid="90" positionid="H5-A1" provinceid="23" userid="430431076" adtype="0">100元话费限时赠送,点击查看</a></li>
        </ol>
    </div>
</div> -->
<div class="content">
    <div class="amend">
        <div class="msg_tit">
            <p class="left" id="province">
                <span><?php  echo $province[$temp_province];?></span>
                <select id="provinceSel">
                  <?php  if(is_array($province)) { foreach($province as $p => $k) { ?>
                    <option value="<?php  echo $p;?>" <?php  if($p==$temp_province) { ?>selected<?php  } ?>><?php  echo $k;?></option>
                  <?php  } } ?>
                </select>
            </p>
            <a href="<?php  echo $this->createMobileUrl('match')?>" class="right">
                        征友条件
                <i class="re_trg"></i>
            </a>
        </div>
    </div>
    <div  class="index_list">
    <?php  if(!empty($sitem['mail_float'])) { ?>
    <div class="mess_fixed">
        <!-- 消息开始 -->
        <div class="top_mess">
            <div class="mess_hide quick_message none link" href="<?php  echo $this->createMobileUrl('mail')?>" style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);">
                <img src="../addons/jy_ppp/images/email.png" alt="" style="margin-top:-5px;">
                <span class="showNum"><?php  echo $weidu_num;?></span>
            </div>
            <div class="mess_show quick_message link" href="<?php  echo $this->createMobileUrl('mail')?>">
                <span><?php  echo $weidu_num;?>封未读消息</span>
                <img src="../addons/jy_ppp/images/email.png" alt="" >
            </div>
        </div>
        <!-- 消息结束 -->
    </div>
    <?php  } ?>
        <?php  $now=time();?>
        <?php  $lll=0;?>
        <ul id="recommend"><?php  if(is_array($tuijian)) { foreach($tuijian as $t) { ?><?php  if($lll!=3) { ?><li style="margin-right: 3px; margin-bottom: 5px;" class="inflo2" data-id="<?php  echo $t['id'];?>"><?php  } else { ?><li class="inflo2" style=" margin-bottom: 5px;" data-id="<?php  echo $t['id'];?>"><?php  } ?>
                <div class="mv_box">
                    <a href="<?php  echo $this->createMobileUrl('detail',array('id'=>$t['id']))?>">
                        <?php  if(!empty($t['avatar'])) { ?>
                      <img src="<?php  echo tomedia($t['avatar'])?>" />
                      <?php  } else { ?>
                          <?php  if($t['sex']==1) { ?>
                              <img src="../addons/jy_ppp/images/boy.png"/>
                          <?php  } else { ?>
                              <img src="../addons/jy_ppp/images/girl.png"/>
                          <?php  } ?>
                      <?php  } ?>
                    </a>
                    <span id="span_<?php  echo $t['id'];?>" onclick="sayHello(this,<?php  echo $t['id'];?>)" class="default"></span>
                </div>
            </li><?php  $lll=$lll+1;?><?php  if($lll==3) { ?><?php  $lll=0;?><?php  } ?><?php  } } ?>
        </ul>

    </div>
    <?php  if(count($tuijian)>11) { ?>
        <div class="see_more" onclick="moreMsg(this)">点击查看更多</div>
    <?php  } ?>
    <div class="loading" >没有更多了</div>
</div>
</section>
<div id="mask" class="mask hidden" style="width:100%; height:100%; background:rgba(0,0,0,0.7); position:fixed; left:0; top:0; z-index:1000;">
    <div class="msg_tip">
        <h3>提示</h3>
        <p>有头像收到的回信比没有头像的多十倍！</p>
        <p>快去上传头像吧~</p>
        <ul>
            <li class="btn_cancel">暂不上传</li>
            <li class="btn_sure">立即上传</li>
        </ul>
    </div>
</div>
<div style="height:70px"></div>
</body>
<script src="../addons/jy_ppp/js/zepto.min.js"></script>
<script src="../addons/jy_ppp/js/public.js"></script>
<script src="../addons/jy_ppp/js/waiting.js"></script>
<script>

if ($(".top_mess").length > 0) {
    setTimeout(function () {
        $(".mess_show").addClass('none');
        $(".mess_hide").removeClass('none').show();
    }, 1000);

}


function moreMsg(obj){
    var str = "";
    for(var i=0;i<$(".inflo2").length;++i){
        str += $(".inflo2").eq(i).data("id")+",";
    }

    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('index',array('op'=>'more')),2)?>"+"&str="+str,function(data){
        if(data.status==1)
        {
            $("#recommend").append(data.log);
        }else if(data.status==2)
        {
            $("#recommend").append(data.log);
            $(".see_more").hide();
            $(".loading").show();
        }
        else
        {
            alert("网络出错，请重试!");
        }
    },'json');

}
function sayHello(obj,id){
    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('index',array('op'=>'zhaohu2')),2)?>"+"&id="+id,function(data){
        if(data==1)
        {
            $(obj).removeClass("default").addClass("like");
            $.tips("招呼已发出，请耐心等待Ta的回复");
        }else if(data==2)
        {
            $(obj).removeClass("default").addClass("like");
            $.tips("你今天已经向Ta打过招呼了。");
        }
        else if(data==3)
        {
            $("#mask").show();
        }
        else
        {
            alert("网络出错，请重试!");
        }
    });
}
$("#provinceSel").bind("change",function(){
    var self = this;
    var str = $(self).find("option:selected").text();
    var selVal = $(self).val();
    $(self).prev().text(str);
});
$(".btn_sure").bind("click",function(){
      setTimeout(function(){
            location.href = "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('upload'),2)?>";
        }, 1500);
});
$(".btn_cancel").bind("click",function(){
      $("#mask").hide();
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</html>