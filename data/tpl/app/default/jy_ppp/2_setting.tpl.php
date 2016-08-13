<?php defined('IN_IA') or exit('Access Denied');?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
<link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/public.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/public_list.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="top_blank">
</div>
<nav class="nav" style="position: absolute;">
<h2>设置中心</h2>
<a class="left" href="<?php  echo $this->createMobileUrl('geren')?>">
    <i class="le_trg"></i>返回
</a>
</nav>
<section class="content">
<div class="box-shadow">
    <a href="<?php  echo $this->createMobileUrl('renzheng')?>"><ul class="p_list">
        <li>
        <label>诚信认证</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a>
    <!-- <a href="./vip_center.html"><ul class="p_list">
        <li>
        <label>红娘设置</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a> -->
    <?php  if($member['sex']==2) { ?>
    <a href="<?php  echo $this->createMobileUrl('mianrao')?>"><ul class="p_list">
        <li>
        <label>免扰设置</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a>
    <?php  } ?>
    <?php  if($member['sex']==1 && $member['type']!=3) { ?>
    <a href="<?php  echo $this->createMobileUrl('vip')?>"><ul class="p_list">
        <li>
        <label>会员中心</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a>
    <?php  } ?>
    <a href="<?php  echo $this->createMobileUrl('myblack')?>"><ul class="p_list">
        <li>
        <label>黑名单</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a>
</div>
<div class="p_space">
</div>
<div class="box-shadow">
    <a href="<?php  echo $this->createMobileUrl('help')?>"><ul class="p_list">
        <li>
        <label>帮助中心</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a>
    <a href="<?php  echo $this->createMobileUrl('safe')?>"><ul class="p_list">
        <li>
        <label>安全中心</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a>
</div>
<div class="p_space">
</div>
<div class="box-shadow">
    <a href="<?php  echo $this->createMobileUrl('account')?>"><ul class="p_list">
        <li>
        <label>账号管理</label>
        </li>
        <div class="re_trg">
        </div>
    </ul></a>
</div>
</section>
</body>
<script src="../addons/jy_ppp/js/zepto.min.js"></script>
<script src="../addons/jy_ppp/js/public.js"></script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</html>