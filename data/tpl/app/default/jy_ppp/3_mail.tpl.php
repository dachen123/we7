<?php defined('IN_IA') or exit('Access Denied');?>﻿<!DOCTYPE html>
<?php  if($weixin==1) { ?>
<html lang="zh" class="wx">
<?php  } else { ?>
<html lang="zh" class="pu">
<?php  } ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
<link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/public.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/option_box.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/public_disbox.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/head_nav.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/user-list.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/user_box.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/mail.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/home_page.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/herd_hello.css" rel="stylesheet" type="text/css">
<link href="../addons/jy_ppp/css/simple_info.css" rel="stylesheet" type="text/css">
<style>
.wx .information{ width: 100%;height: 40px;line-height: 40px;background: #fff;margin-bottom: 5px;text-align: center;font-size: 0.9em; margin-top:0px !important; padding-top:0px !important}
.wx .onekey_set{ margin-top:0px; border:1px solid red;}
a>.user-list>li.foot-icon>img{width: 50px;max-height: 61px;border-radius: 3px;}
</style>
</head>
<body>
<div class="tips" style="display: none;">
</div>
<div class="waiting" style="display: none;">
</div>
<div class="blank0">
</div>

<div>
    <div>
        <div href="#" class="onekey_set">
            免扰信件过多？一键设置！<i class="re_trg"></i>
        </div>
        <section id="index_section" class="home_page content">

        <div id="mail_list">
            <?php  if(!empty($visit['nicheng'])) { ?>
            <a href="<?php  echo $this->createMobileUrl('visit')?>"><ul class="disbox-hor user-list">
                <li class="foot-icon disbox-center"><img src="../addons/jy_ppp/images/footprint.png" style="width: 50px;height: 50px;border-radius: 3px"/></li>
                <li class="disbox-flex user_mession">
                    <b class="tit">最近访客</b>
                    <p class="bot">
                        <span class="pink"><?php  echo $visit['nicheng'];?></span>查看了你的信息
                    </p>
                </li>
                <li class="disbox-flex">
                    <div class="re_trg"></div>
                </li>
            </ul></a>
            <?php  } ?>

            <?php  if($lianxi>0) { ?>
            <a href="<?php  echo $this->createMobileUrl('lianxi')?>"><ul class="disbox-hor user-list">
                <li class="foot-icon disbox-center"><img src="../addons/jy_ppp/images/man.png" style="width: 50px;height: 50px"></li>
                <li class="disbox-flex user_mession">
                    <b class="tit">最近联系人</b>
                    <p class="bot">查看最近联系人</p>
                </li>
                <li class="disbox-flex">
                    <div class="re_trg"></div>
                </li>
            </ul></a>
            <?php  } ?>
            <?php  $now=time();?>
            <?php  if(is_array($list)) { foreach($list as $l) { ?>
            <a href="<?php  echo $this->createMobileUrl('chat',array('id'=>$l['mid']))?>"><ul class="disbox-hor user-list mailid" data-id="<?php  echo $l['mid'];?>">
                <li class="foot-icon disbox-center">
                    <?php  if($l['type']!=3) { ?>
                        <?php  if(!empty($l['avatar'])) { ?>
                            <img src="<?php  echo tomedia($l['avatar'])?>">
                        <?php  } else { ?>
                            <?php  if($l['sex']==1) { ?>
                                <img src="../addons/jy_ppp/images/boy.png"/>
                            <?php  } else { ?>
                                <img src="../addons/jy_ppp/images/girl.png"/>
                            <?php  } ?>
                        <?php  } ?>
                    <?php  } else { ?>
                        <?php  if(!empty($sitem['adminthumb'])) { ?>
                        <img src="<?php  echo tomedia($sitem['adminthumb'])?>">
                        <?php  } else { ?>
                        <img src="../addons/jy_ppp/images/adminthumb.png">
                        <?php  } ?>
                    <?php  } ?>
                </li>
                <li class="disbox-flex user_mession">
                <?php  if($l['type']!=3) { ?>
                <b class="tit"><?php  echo $l['nicheng'];?><span class="pink"><?php  if(empty($l['yuedu'])) { ?><?php  if($l['type']==1) { ?>[会员推荐]<?php  } ?><?php  if($l['type']==2) { ?>[最新回信]<?php  } ?><?php  } ?></span>
                <?php  } else { ?>
                <b class="tit">管理员<span class="pink"></span>
                <?php  } ?>
                </b>
                <p class="bot">
                <?php  if($l['type']!=3) { ?>
                    <?php  if(!empty($l['brith'])) { ?>
                    <?php  $birthday=$l['brith'];$month=0;if(date('m', $now)>date('m', $birthday))$month=1;if(date('m', $now)==date('m', $birthday))if(date('d', $now)>=date('d', $birthday))$month=1;$nianlin=date('Y', $now)-date('Y', $birthday)+$month;?>
                    <?php  echo $nianlin;?>岁·<?php  } ?>
                    <?php  if(!empty($moni_province) && $l['type2']==3) { ?>
                        <?php  echo $moni_province;?>
                    <?php  } else { ?>
                        <?php  if(empty($sitem['user_addr'])) { ?>
                            <?php  if(!empty($l['city'])) { ?>
                                <?php  echo $province[$l['province']];?><?php  echo $sub_array[$l['province']][$l['city']];?>
                            <?php  } else { ?>
                                <?php  echo $province[$l['province']];?>
                            <?php  } ?>
                        <?php  } else { ?>
                            <?php  if($sitem['user_addr']==2) { ?>
                                <?php  if(!empty($l['city'])) { ?>
                                    <?php  echo $sub_array[$l['province']][$l['city']];?>
                                <?php  } else { ?>
                                    <?php  echo $province[$l['province']];?>
                                <?php  } ?>
                            <?php  } else { ?>
                                <?php  echo $province[$l['province']];?>
                            <?php  } ?>
                        <?php  } ?>
                    <?php  } ?>
                    <?php  if(!empty($l['height'])) { ?>·<?php  echo $l['height'];?>cm<?php  } ?>
                <?php  } ?>
                </p>
                </li>
                <div class="right">
                    <?php  echo date("G:i",$l['createtime'])?>
                </div>
                <?php  if($l['yuedu']==0) { ?><i class="cycle_tag">未读</i><?php  } ?>
            </ul></a>
            <?php  } } ?>
            
        </div>
        <?php  if(count($list)>9) { ?>
                <div class="see_more" onclick="moreMsg(this)">点击查看更多</div>
            <?php  } ?>
                <div class="loading" >没有更多了</div>
        <div class="blank80">
        </div>
        </section>
    </div>
</div>

<div id="mask" class="mask hidden" style="width:100%; height:100%; background:rgba(0,0,0,0.7); position:fixed; left:0; top:0; z-index:1000;">
</div>
<div class="simple_info simple_info_unaffect hidden">
    <p class="title">
        <span>友情提示</span>
    </p>
    <p class="abolish">
        已经帮你处理了
        <span class="pink">12</span>封信！<br>
        是否记录你的选择？
    </p>
    <div class="col2_btn">
        <a class="btn3" href="###">暂不记录</a>
        <a class="btn4" href="###">记录当前选择</a>
    </div>
</div>
<div class="simple_info simple_info_guolv hidden">
    <p class="title">
        <span>友情提示</span>
    </p>
    <p class="abolish_yellow">
        亲，你收到的<span>2222</span>封未读信中，有<span>22</span><span>封是无头像的异性发来的。</span>为提高未读私信的处理能力，你是否愿意使用有缘小助手批量处理这些信件？
    </p>
    <p class="btttom">
        有缘小助手服务介绍：
        <br>
        1.帮助你将招呼信件全部设为已读；
        <br>
        2.我们将你的征友条件转变成问题代你向满足征友要求的异性发出相关的问题；
        <br>
        3.为了不错过美丽的缘分，当你不在线时会代你至多响应一次男用户发来的招呼信。
        <br>
        注：有缘小助手是用户自主设置的服务功能，如不满意可随时取消设置，请放心使用。
    </p>
    <div class="col2_btn">
        <a class="btn1" href="###">暂不使用</a>
        <a class="btn2" href="###">确定使用</a>
    </div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template(menu, TEMPLATE_INCLUDEPATH)) : (include template(menu, TEMPLATE_INCLUDEPATH));?>
<input id="filterType" type="hidden" value="2">
<script src="../addons/jy_ppp/js/zepto.min.js"></script>
<script src="../addons/jy_ppp/js/public.js"></script>
<script src="../addons/jy_ppp/js/waiting.js"></script>
<script>
function moreMsg(obj){
    var str = "";
    for(var i=0;i<$(".mailid").length;++i){
        str += $(".mailid").eq(i).data("id")+",";
    }
    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('mail',array('op'=>'more')),2)?>"+"&str="+str,function(data){
        if(data.status==1)
        {
            $("#mail_list").append(data.log);
        }else if(data.status==2)
        {
            $("#mail_list").append(data.log);
            $(".see_more").hide();
            $(".loading").show();
        }
        else
        {
            alert("网络出错，请重试!"+data);
        }
    },'json');
}
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</body>
</html>