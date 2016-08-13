<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<?php  if($weixin==1) { ?>
<html lang="zh" class="wx">
<?php  } else { ?>
<html lang="zh" class="pu">
<?php  } ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
<link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/public.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/option_box.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/public_disbox.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/head_nav.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/user-list.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/user_box.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/nearby02.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/home_page.css" rel="stylesheet" type="text/css"/>
  <link href="../addons/jy_ppp/css/bottom_call.css" rel="stylesheet" type="text/css" />
<style>
.qunsay{
    position: fixed;
    bottom: 80px;
    left: 0;
    width: 100%
}
.sayHiss {
    margin: 0 auto;
    width: 90%;
    height: 50px;
    background: rgba(236,32,88,0.75);
    border-radius: 8px;
    font-size: 24px;
    color: #ffffff;
    text-align: center;
    line-height: 50px;
    max-width: 480px
}
.sayHiss>img{width:30px}
.fujin {
    margin-top: 0!important;
    padding-bottom: 5px;
    padding-left: 1.1pc;
    background: url(../addons/jy_ppp/images/fj_icon.png) no-repeat;
    background-size: 26%;
    text-align: right;
    font-size: 13px;
    color: #F78AA9;
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
<body>
<div>
    <div>
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
        <section class="content" id="found_section">
        <section id="nearby_list" class="content_02 nearby02">
        <!-- begin -->
        <?php  $now=time();?>
        <?php  if(is_array($tuijian)) { foreach($tuijian as $t) { ?>
        <ul class="disbox-hor user-list" data-id="<?php  echo $t['id'];?>">
            <li class="foot-icon"><a href="<?php  echo $this->createMobileUrl('detail',array('id'=>$t['id']))?>">
                <?php  if(!empty($t['avatar'])) { ?>
                    <img src="<?php  echo tomedia($t['avatar'])?>" />
                <?php  } else { ?>
                    <?php  if($t['sex']==1) { ?>
                        <img src="../addons/jy_ppp/images/boy.png"/>
                    <?php  } else { ?>
                        <img src="../addons/jy_ppp/images/girl.png"/>
                    <?php  } ?>
                <?php  } ?>
            </a></li>
            <li class="disbox-flex user_mession"><a href="<?php  echo $this->createMobileUrl('detail',array('id'=>$t['id']))?>">
            <b class="tit"><?php  echo $t['nicheng'];?></b>
            <p>
                <?php  if(!empty($moni_province) && $t['type2']==3) { ?>
                    <?php  echo $moni_province;?>
                <?php  } else { ?>
                    <?php  if(empty($sitem['user_addr'])) { ?>
                        <?php  if(!empty($t['city'])) { ?>
                            <?php  echo $province[$t['province']];?><?php  echo $sub_array[$t['province']][$t['city']];?>
                        <?php  } else { ?>
                            <?php  echo $province[$t['province']];?>
                        <?php  } ?>
                    <?php  } else { ?>
                        <?php  if($sitem['user_addr']==2) { ?>
                            <?php  if(!empty($t['city'])) { ?>
                                <?php  echo $sub_array[$t['province']][$t['city']];?>
                            <?php  } else { ?>
                                <?php  echo $province[$t['province']];?>
                            <?php  } ?>
                        <?php  } else { ?>
                            <?php  echo $province[$t['province']];?>
                        <?php  } ?>
                    <?php  } ?>
                <?php  } ?>
                <?php  if(!empty($t['brith'])) { ?>
                <?php  $birthday=$t['brith'];$month=0;if(date('m', $now)>date('m', $birthday))$month=1;if(date('m', $now)==date('m', $birthday))if(date('d', $now)>=date('d', $birthday))$month=1;$nianlin=date('Y', $now)-date('Y', $birthday)+$month;?><?php  echo $nianlin;?>岁<?php  } ?> <?php  if(!empty($t['height'])) { ?><?php  echo $t['height'];?>cm<?php  } ?></p>
            <p class="foot_imgarrange"><?php  if(!empty($t['beizhu'])) { ?><?php  echo $t['beizhu'];?><?php  } else { ?><?php  if(!empty($t['province2'])) { ?>寻找<?php  echo $province[$t['province2']];?><?php  if(!empty($t['age'])) { ?><?php  echo $match_age[$t['age']];?><?php  } ?><?php  if(!empty($t['height2'])) { ?> <?php  echo $match_height[$t['height2']];?>cm<?php  } ?>的<?php  echo $xz_sex;?><?php  } else { ?>寻找<?php  if(!empty($t['province'])) { ?><?php  echo $province[$t['province']];?><?php  } else { ?><?php  echo $province['11'];?><?php  } ?>的<?php  echo $xz_sex;?><?php  } ?><?php  } ?></p>
            </a></li>
            <div ><p class="fujin"><?php  echo $this->xuniaddress($t['id'])?>公里</p>   <span class="hai_hello" onclick="sayHello(this,<?php  echo $t['id'];?>)" style="font-size:13px">打招呼</span></div>
        </ul>
        <?php  } } ?>
        <!-- end -->
        </section>

         <?php  if(count($tuijian)>9) { ?>
            <div class="see_more" onclick="moreMsg(this)">点击查看更多</div>
        <?php  } ?>
        <div class="loading" >没有更多了</div>

        <div style="height:120px"></div>
        </section>
    </div>
</div>

<div class="qunsay">
    <div class="sayHiss">
    <i class="icon-bt-hello"></i>群打招呼</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template(menu, TEMPLATE_INCLUDEPATH)) : (include template(menu, TEMPLATE_INCLUDEPATH));?>
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
$(".sayHiss").bind("click",function(){
    var str = "";
    for(var i=0;i<$(".disbox-hor").length;++i){
        if($(".disbox-hor").eq(i).find("span").hasClass("hai_hello")){
            str += $(".disbox-hor").eq(i).data("id")+",";
        }
    }
    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('luck',array('op'=>'zhaohu')),2)?>"+"&str="+str,function(data){
        if(data==1)
        {
            $(".hai_hello").removeClass("hai_hello").addClass("hello_out").html("已打招呼");
            $.tips("群打招呼已发出，请耐心等待回复");
        }
        else
        {
            alert("网络出错，请重试!");
        }
    });
})
function moreMsg(obj){
    var str = "";
    for(var i=0;i<$(".disbox-hor").length;++i){
        str += $(".disbox-hor").eq(i).data("id")+",";
    }

    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('luck',array('op'=>'more')),2)?>"+"&str="+str,function(data){
        if(data.status==1)
        {
            $("#nearby_list").append(data.log);
        }else if(data.status==2)
        {
            $("#nearby_list").append(data.log);
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
    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('luck',array('op'=>'zhaohu2')),2)?>"+"&id="+id,function(data){
        if(data==1)
        {
            $(obj).removeClass("hai_hello").addClass("hello_out").html("已打招呼");
            $.tips("招呼已发出，请耐心等待Ta的回复");
        }else if(data==2)
        {
            $(obj).removeClass("hai_hello").addClass("hello_out").html("已打招呼");
            $.tips("你今天已经向Ta打过招呼了。");
        }
        else
        {
            alert("网络出错，请重试!");
        }
    });
}
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</body>
</html>