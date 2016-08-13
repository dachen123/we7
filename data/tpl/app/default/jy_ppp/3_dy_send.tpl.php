<?php defined('IN_IA') or exit('Access Denied');?><!Doctype html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
<link rel="stylesheet" href="../addons/jy_ppp/css/common2.css">

<style>
.conDiv{width: 100%;margin: 0 auto;max-width: 640px;background-color: #fff}
.conLeft{padding: 10px}
.leftDiv{width: 75%}
.leftDiv>img{width: 60px;height: 60px;border-radius: 5px}
.leftDiv>div{margin:5px 0}
.rightDiv{width: 23%;margin-top: 20px}
.rightDiv>a{padding: 5px 10px;border: 1px solid #f55a8e;color:#f55a8e;border-radius: 5px}
.tipDiv{width: 100%;padding: 10px 0;background-color: #f8f6f7}
.tipDiv>a{width: 49%;display: inline-block;color: #000}
.tipDiv>a:first-child{border-right:1px solid #f55a8e}
.addUser{position: fixed;bottom: 60px;width: 100%;text-align: center;max-width: 640px}
.addUser>a{border: 1px solid #f55a8e;padding: 5px 15px;color: #fff;background-color: #f55a8e;border-radius: 15px}
@media screen and (max-width: 320px){
	.leftDiv>div{width: 60%}
}
@media screen and (min-width: 321px){
	.leftDiv>div{width: 70%}
}
@media screen and (min-width: 500px){
	.leftDiv>div{width: 80%}
	.leftDiv{padding-left: 10px}
}
.tipsDiv{position: fixed;width: 100%;top: 0;z-index: 999}
.tips{width: 80%;min-height: 25px;word-wrap: break-word;word-break: break-all; padding:10px 0; overflow: hidden;background: rgba(0,0,0,0.5);border-radius: 10px;color: #fff;text-align: center;display: none;margin:20% auto;z-index: 1000;border:1px solid #fff;max-width: 480px}

</style>
</head>

<body>
<div class="bodybox">
    <div class="selectedDiv">
        <a href="<?php  echo $this->createMobileUrl('dycenter')?>" class="rBor black"><img style="width:22px" src="../addons/jy_ppp/images/main.png"><span>主页</span></a>
        <a href="<?php  echo $this->createMobileUrl('dy_user')?>" class="rBor black"><img src="../addons/jy_ppp/images/user.png"><span>用户</span></a>
        <a href="<?php  echo $this->createMobileUrl('dy_msg')?>" class="rBor black"><img src="../addons/jy_ppp/images/msg.png"><span>消息</span></a>
        <a href="<?php  echo $this->createMobileUrl('dy_man')?>" class="black"><img src="../addons/jy_ppp/images/manager.png"><span>管理</span></a>
    </div>

    <div class="head mainBg">
        <!-- <a href="javascript:history.go(-1)" class="bn pull-left"><img class="icon" src="../addons/jy_ppp/images/back.png"></a> -->
        <div class="title">新用户(<?php  echo $yh['nicheng'];?>)【<?php  if($yh['sex']==1) { ?>男<?php  } else { ?>女<?php  } ?>】</div>
        <!-- <a href="#" class="bn pull-right"><img class="icon" src="../addons/jy_ppp/images/ren.png"/></a> -->
    </div>
	<div class="topZhan"></div>
	<!-- begin -->
	<?php  $now=time();?>
	<?php  if(is_array($xuni)) { foreach($xuni as $x) { ?>
		<div class="conDiv">
			<div class="ovfl conLeft">
				<div class="floL leftDiv">
					<?php  if(!empty($x['avatar'])) { ?>
                        <img class="floL" src="<?php  echo tomedia($x['avatar'])?>"/>
                    <?php  } else { ?>
                        <?php  if($x['sex']==1) { ?>
                        <img class="floL" src="../addons/jy_ppp/images/boy.png"/>
                        <?php  } else { ?>
                        <img class="floL" src="../addons/jy_ppp/images/girl.png"/>
                        <?php  } ?>
                    <?php  } ?>
					<div class="floR nowrap mainColor"><?php  echo $x['nicheng'];?></div>
					<div class="floR nowrap mainColor"><?php  if(!empty($x['brith'])) { ?>
                <?php  $birthday=$x['brith'];$month=0;if(date('m', $now)>date('m', $birthday))$month=1;if(date('m', $now)==date('m', $birthday))if(date('d', $now)>=date('d', $birthday))$month=1;$nianlin=date('Y', $now)-date('Y', $birthday)+$month;?><?php  echo $nianlin;?>岁<?php  } ?> <?php  echo $province[$x['province']];?></div>
				</div>
				<div class="floR rightDiv txtAC"><a href="<?php  echo $this->createMobileUrl('geren',array('xuniid'=>$x['id']))?>">详情</a></div>
			</div>
			<div class="ovfl tipDiv">
				<a href="javascript:void(0)" onclick="send2(<?php  echo $yh['id'];?>,<?php  echo $x['id'];?>)" class="floL txtAC">快捷发消息</a>
				<a href="<?php  echo $this->createMobileUrl('dy_chat',array('sendid'=>$yhid,'mid'=>$x['id']))?>" class="floL txtAC">用Ta聊天</a>
			</div>
			<div class="zhanCol"></div>
		</div>
	<?php  } } ?>
	<!-- end -->

    <div class="zhanwei"></div>
    <div class="addUser"><a href="javascript:void(0)" onclick="send(<?php  echo $yh['id'];?>)">一键调戏</a></div>
</div>
<script src="../addons/jy_ppp/js/zepto.min.js"></script>
<script src="../addons/jy_ppp/js/public.js"></script>
<script src="../addons/jy_ppp/js/waiting.js"></script>
<script type="text/javascript">
	function send(yhid)
	{
		if(yhid)
		{
			$.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('dy_kjmsg'),2)?>"+"&yhid="+yhid,function(data){
	            if (data == 1) {
	            	$.tips("发送成功！");

	            }
	            else
	            {
	                alert(data);
	            }
	        });
		}
		else
		{
			alert("操作非法！刷新重试！");
		}
	}
	function send2(yhid,id)
	{
		if(yhid)
		{
			$.post("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('dy_kjmsg'),2)?>"+"&yhid="+yhid+"&id="+id,function(data){
	            if (data == 1) {
	            	$.tips("发送成功！");

	            }
	            else
	            {
	                alert(data);
	            }
	        });
		}
		else
		{
			alert("操作非法！刷新重试！");
		}
	}
</script>
</body>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>


</html>