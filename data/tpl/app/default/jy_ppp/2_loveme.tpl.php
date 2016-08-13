<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html lang="zh" class="pu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
    <link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet"/>
    <link href="../addons/jy_ppp/css/public.css" rel="stylesheet"/>
    <link href="../addons/jy_ppp/css/public_disbox.css" rel="stylesheet"/>
    <link href="../addons/jy_ppp/css/public_headmessage.css" rel="stylesheet"/>
    <link href="../addons/jy_ppp/css/user-list.css" rel="stylesheet"/>
    <link href="../addons/jy_ppp/css/herd_hello.css" rel="stylesheet"/>
</head>
<body>
<div class="top_blank"></div>
<nav class="nav">
    <h2>关注我的</h2>
    <div class="left" onClick="history.go(-1)">
        <i class="le_trg"></i>返回
    </div>
</nav>
<div class="content">
	<section class="herd_hello">
    <!-- 无人关注 -->
    <?php  $now=time();?>
    <?php  if(empty($love)) { ?>
    <div >
        <div class="pro">还没有人关注你呢~<br />不是魅力不足，是不够积极主动哦~</div>
        <?php  if(!empty($tuijian)) { ?>
        <div class="all_com">
            <h2 class="title">为你推荐</h2>
            <ol class="pto">
                <?php  if(is_array($tuijian)) { foreach($tuijian as $t) { ?>
                <a href="<?php  echo $this->createMobileUrl('detail',array('id'=>$t['id']))?>"><li>
                    <?php  if(!empty($t['avatar'])) { ?>
                        <img src="<?php  echo tomedia($t['avatar'])?>" />
                    <?php  } else { ?>
                        <?php  if($t['sex']==1) { ?>
                            <img src="../addons/jy_ppp/images/boy.png"/>
                        <?php  } else { ?>
                            <img src="../addons/jy_ppp/images/girl.png"/>
                        <?php  } ?>
                    <?php  } ?>
                    <p><?php  echo $province[$t['province']];?>  <?php  if(!empty($t['brith'])) { ?>
                        <?php  $birthday=$t['brith'];$month=0;if(date('m', $now)>date('m', $birthday))$month=1;if(date('m', $now)==date('m', $birthday))if(date('d', $now)>=date('d', $birthday))$month=1;$nianlin=date('Y', $now)-date('Y', $birthday)+$month;?>
                        <?php  echo $nianlin;?>岁<?php  } ?></p>
                </li></a>
                <?php  } } ?>
            </ol>
            <div class="btn_box"><a class="btn" onclick="sayPeople(this)" data-id="<?php  echo $zhaohu;?>">群打招呼</a></div>
        </div>
        <?php  } ?>
    </div>
    <?php  } else { ?>
    <!-- 有关注 -->
    <!-- begin -->
    <?php  if(is_array($love)) { foreach($love as $l) { ?>
    <ul class="disbox-hor user-list" data-id="<?php  echo $l['id'];?>">
        <li class="foot-icon disbox-center"><a href="<?php  echo $this->createMobileUrl('detail',array('id'=>$l['mid']))?>">
            <?php  if(!empty($l['avatar'])) { ?>
                <img src="<?php  echo tomedia($l['avatar'])?>" />
            <?php  } else { ?>
                <?php  if($l['sex']==1) { ?>
                    <img src="../addons/jy_ppp/images/boy.png"/>
                <?php  } else { ?>
                    <img src="../addons/jy_ppp/images/girl.png"/>
                <?php  } ?>
            <?php  } ?>
        </a></li>
        <li class="disbox-flex user_mession"><a href="<?php  echo $this->createMobileUrl('detail',array('id'=>$l['mid']))?>">
            <b class="tit"><?php  echo $l['nicheng'];?></b>
            <p class="bot"><?php  if(!empty($l['brith'])) { ?>
                    <?php  $birthday=$l['brith'];$month=0;if(date('m', $now)>date('m', $birthday))$month=1;if(date('m', $now)==date('m', $birthday))if(date('d', $now)>=date('d', $birthday))$month=1;$nianlin=date('Y', $now)-date('Y', $birthday)+$month;?>
                    <?php  echo $nianlin;?>岁·<?php  } ?><?php  echo $province[$l['province']];?><?php  if(!empty($l['height'])) { ?>·<?php  echo $l['height'];?>cm<?php  } ?></p>
        </a></li>
        <div class="right"><?php  if(empty($l['zh'])) { ?><span class="hello" onclick="sayPeople2(this,<?php  echo $l['mid'];?>)" >打招呼</span><?php  } ?><?php  if(!empty($l['zh'])) { ?><span class="hello_out" >已打招呼</span><?php  } ?></div>
    </ul>
    <?php  } } ?>
    <!-- end -->
   <?php  } ?>

</section>
 <?php  if(count($love)>9) { ?>
        <div class="see_more" onclick="moreMsg(this)">点击查看更多</div>
    <?php  } ?>
        <div class="loading" >没有更多了</div>
    
</div>
</body>
<script src="../addons/jy_ppp/js/zepto.min.js"></script>
<script src="../addons/jy_ppp/js/public.js"></script>
<script src="../addons/jy_ppp/js/waiting.js"></script>
<script>
function moreMsg(obj){
    var lastid=$(".disbox-hor:last").data("id");
    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('mylove',array('op'=>'more')),2)?>"+"&lastid="+lastid,function(data){
        if(data.status==1)
        {
            $(".herd_hello").append(data.log);
        }else if(data.status==2)
        {
            $(".herd_hello").append(data.log);
            $(".see_more").hide();
            $(".loading").show();
        }
        else
        {
            alert("网络出错，请重试!");
        }
    },'json');
}

function sayPeople(obj){
    var str=$(obj).data("id");
    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('mylove',array('op'=>'zhaohu')),2)?>"+"&str="+str,function(data){
        if(data.status==1)
        {
            $(obj).removeClass("hello").addClass("hello_out").html("已打招呼");
            $.tips("招呼已发出，请耐心等待Ta的回复");
            $(".all_com").html('');
            $(".all_com").append(data.log);
        }
        else
        {
            alert("网络出错，请重试!");
        }
    },'json');
}

function sayPeople2(obj,id){
    $.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('mylove',array('op'=>'zhaohu2')),2)?>"+"&id="+id,function(data){
        if(data==1)
        {
            $(obj).removeClass("hello").addClass("hello_out").html("已打招呼");
            $.tips("招呼已发出，请耐心等待Ta的回复");
        }else if(data==2)
        {
            $(obj).removeClass("hello").addClass("hello_out").html("已打招呼");
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
</html>