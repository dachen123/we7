<?php defined('IN_IA') or exit('Access Denied');?>﻿<!doctype html> 
<html>
<head>
<link href="../addons/jy_ppp/css/speed_2015114.css" rel="stylesheet" type="text/css" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=no" name="format-detection" />
<title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
</head>
<style type="text/css">
	<?php  if(empty($sitem['zhuce_bg'])) { ?>
	.speed_2015114{ background:url(../addons/jy_ppp/images/speed_2015114.jpg) center top no-repeat; background-size:100%;}
	<?php  } else { ?>
	.speed_2015114{ background:url(<?php  echo tomedia($sitem['zhuce_bg'])?>) center top no-repeat; background-size:100%;}
	<?php  } ?>
</style>
<body class="speed_2015114_body">
<div class="speed_2015114">
	<?php  if(empty($sitem['zhuce_text'])) { ?>
    <p class="artical">1亿9836万美女帅哥在这里等你哦~</p>
    <?php  } else { ?>
    <p class="artical"><?php  echo $sitem['zhuce_text'];?></p>
    <?php  } ?>
    <div class="col_btn col_btn_2">
    	<a href="<?php  echo $this->createMobileUrl('zhuce2',array('sex'=>1,'rid'=>$rid))?>" class="boy_friend">我是男</a>
        <a href="<?php  echo $this->createMobileUrl('zhuce2',array('sex'=>2,'rid'=>$rid))?>" class="girl_friend">我是女</a>
    </div>
    <div class="foot">已有账号，<a href="<?php  echo $this->createMobileUrl('login',array('rid'=>$rid))?>" class="log_in">直接登录&gt;</a> <br />我已阅读并同意<a href="<?php  echo $this->createMobileUrl('rule')?>" class="blue">会员注册协议</a></div>
</div>
</body>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>

</html>
