<?php defined('IN_IA') or exit('Access Denied');?><link rel="stylesheet" href="../addons/jy_ppp/css/h5menu.css">
<style type="text/css">
    .
</style>
<div class="h5menu"><div>
    <?php  if($menu==1) { ?>
    <div class="myy myy1 myy1on">
    <?php  } else { ?>
    <div class="myy myy1 ">
    <?php  } ?>
        <a href="<?php  echo $this->createMobileUrl('index')?>">
        <div class="icon_">
        </div>
        <div class="txt_">
            缘分
        </div>
        </a>
    </div>
    <?php  if($menu==2) { ?>
    <div class="myy myy2 myy2on"  style="position:relative;">
    <?php  } else { ?>
    <div class="myy myy2"  style="position:relative;">
    <?php  } ?>
        <a href="<?php  echo $this->createMobileUrl('mail')?>">
        <div class="icon_">
        </div>
        <div class="txt_">
            消息
        </div>
        </a>
        <?php  if(!empty($weidu_num)) { ?>
        <span style="min-width:20px;min-height:15px;border-radius:50%;background:red;position:absolute;top:5px;right:25%;color:#fff;z-index:101;padding:1px;font-size:14px;display:block;"><?php  echo $weidu_num;?></span>
        <?php  } ?>
    </div>
    
    <?php  if($menu==3) { ?>
    <div class="myy myy3 myy3on" >
    <?php  } else { ?>
    <div class="myy myy3" style="">
    <?php  } ?>
        <a href="<?php  echo $this->createMobileUrl('luck')?>">
        <div class="icon_">
        </div>
        <div class="txt_">
            附近
        </div>
        </a>
    </div>
    <?php  if($menu==4) { ?>
    <div class="myy myy4 myy4on">
    <?php  } else { ?>
    <div class="myy myy4">
    <?php  } ?>
        <a href="<?php  echo $this->createMobileUrl('geren')?>">
        <div class="icon_">
        </div>
        <div class="txt_">
            我
        </div>
        </a>
    </div></div>
</div>