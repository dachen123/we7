<?php defined('IN_IA') or exit('Access Denied');?>﻿<!doctype html>
<?php  if($weixin==1) { ?>
<html lang="zh" class="wx">
<?php  } else { ?>
<html lang="zh" class="pu">
<?php  } ?>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <meta content="black" name="apple-mobile-web-app-status-bar-style" />
  <meta content="telephone=no" name="format-detection" />
  <title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
  <link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet" type="text/css" />
  <link href="../addons/jy_ppp/css/public.css" rel="stylesheet" type="text/css" />
  <link href="../addons/jy_ppp/css/public_disbox.css" rel="stylesheet" type="text/css" />
  <link href="../addons/jy_ppp/css/public_headmessage.css" rel="stylesheet" type="text/css" />
  <link href="../addons/jy_ppp/css/user_box.css" rel="stylesheet" type="text/css" />
  <link href="../addons/jy_ppp/css/bottom_call.css" rel="stylesheet" type="text/css" />
  <link href="../addons/jy_ppp/css/user_info.css" rel="stylesheet" type="text/css" />
  <link href="../addons/jy_ppp/css/foot.css" rel="stylesheet" type="text/css" />
 </head>
 <body>
  <div class="top_blank"></div>
  <nav class="nav">
   <h2><?php  echo $detail['nicheng'];?></h2>
   <a class="left" onclick="javascript:history.go(-1)">
    <i class="le_trg"></i>返回
   </a>
   <a class="right" href="<?php  echo $this->createMobileUrl('index')?>">
    <i class="house_icon"></i>
   </a>
  </nav>
  <div class="content">
   <section class="user_space">
    <div class="user_box">
     <dl class="user">
      <dt class="smallShow">
        <?php  if(!empty($detail['avatar'])) { ?>
            <img src="<?php  echo tomedia($detail['avatar'])?>"/>
        <?php  } else { ?>
            <?php  if($detail['sex']==1) { ?>
            <img src="../addons/jy_ppp/images/boy.png"/>
            <?php  } else { ?>
            <img src="../addons/jy_ppp/images/girl.png"/>
            <?php  } ?>
        <?php  } ?>
      </dt>
      <dd>
       <b class="name"><?php  echo $detail['nicheng'];?><span class="personIcon"></span></b>
       <p><?php  $now=time();?><?php  if(!empty($detail['brith'])) { ?><?php  $birthday=$detail['brith'];$month=0;if(date('m', $now)>date('m', $birthday))$month=1;if(date('m', $now)==date('m', $birthday))if(date('d', $now)>=date('d', $birthday))$month=1;$nianlin=date('Y', $now)-date('Y', $birthday)+$month;?><?php  echo $nianlin;?>岁<?php  } ?>&middot;
       <?php  if(!empty($moni_province) && $detail['type2']==3) { ?>
          <?php  echo $moni_province;?>
        <?php  } else { ?>
          <?php  if(empty($sitem['user_addr'])) { ?>
              <?php  if(!empty($detail['city'])) { ?>
                  <?php  echo $province[$detail['province']];?><?php  echo $sub_array[$detail['province']][$detail['city']];?>
              <?php  } else { ?>
                  <?php  echo $province[$detail['province']];?>
              <?php  } ?>
          <?php  } else { ?>
              <?php  if($sitem['user_addr']==2) { ?>
                  <?php  if(!empty($detail['city'])) { ?>
                      <?php  echo $sub_array[$detail['province']][$detail['city']];?>
                  <?php  } else { ?>
                      <?php  echo $province[$detail['province']];?>
                  <?php  } ?>
              <?php  } else { ?>
                  <?php  echo $province[$detail['province']];?>
              <?php  } ?>
          <?php  } ?>
        <?php  } ?>
       <?php  if(!empty($detail['height'])) { ?>&middot;<?php  echo $detail['height'];?>cm<?php  } ?></p>
       <p> <?php  if(!empty($detail['mobile_auth'])) { ?><span class="phone Check" id="phone">手机认证</span><?php  } else { ?><span class="phone noCheck" id="phone">手机认证</span><?php  } ?> <?php  if(!empty($detail['card_auth'])) { ?><span class="identity Check">身份认证</span><?php  } else { ?><span class="identity noCheck">身份认证</span><?php  } ?> <?php  if($sitem['youhuo_pay']) { ?><span class="phone noCheck" style="background-color:rgba(236,32,88,0.75)" onclick="window.location.href='<?php  echo $this->createMobileUrl('cz')?>'">微信手机</span><?php  } ?></p>
      </dd>
     </dl>
    </div>
    <div class="scroll_photo">
     <ul class="need_photo" style="width:100%">
       <?php  $ti=1;?>
       <?php  if(is_array($thumb)) { foreach($thumb as $t) { ?>
       <li><a href="<?php  echo $this->createMobileUrl('userthumb',array('id'=>$t['mid'],'thumbid'=>$ti))?>"><img src="<?php  echo tomedia($t['thumb'])?>"/></a></li>
       <?php  $ti++;?>
       <?php  } } ?>
      <li class="require disbox-center" onclick="toWantPic()">索要<br />照片</li>
     </ul>
    </div>
    <!--心情展示start-->
    <!--心情展示end-->
    <?php  if(!empty($detail['beizhu'])) { ?>
    <dl class="heart">
     <dt>
      内心独白
     </dt>
     <dd>
      <?php  echo $detail['beizhu'];?>
     </dd>
    </dl>
    <?php  } ?>
    <div class="date_list">
     <ul class="date_nav">
      <li class="disbox-center active">基本资料</li>
     </ul>
     <ol class="user_date">
      <li> <span>学历</span> <span><?php  if(!empty($detail['education'])) { ?><?php  echo $detail['education'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>月收入</span> <span><?php  if(!empty($detail['income'])) { ?><?php  echo $detail['income'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>职业</span> <span><?php  if(!empty($detail['job'])) { ?><?php  echo $detail['job'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>体重</span> <span> <?php  if(!empty($detail['weight'])) { ?><?php  echo $detail['weight'];?>斤<?php  } else { ?>保密<?php  } ?> </span> </li>
      <li> <span>身高</span> <span> <?php  if(!empty($detail['height'])) { ?><?php  echo $detail['height'];?>cm<?php  } else { ?>保密<?php  } ?> </span> </li>
      <li> <span>血型</span> <span><?php  if(!empty($detail['blood'])) { ?><?php  echo $detail['blood'];?>型<?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>婚姻状况</span> <span><?php  if(!empty($detail['marriage'])) { ?><?php  echo $detail['marriage'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>是否有房</span> <span><?php  if(!empty($detail['house'])) { ?><?php  echo $detail['house'];?><?php  } else { ?>保密<?php  } ?></span> </li>
     </ol>
    </div>
    <?php  if($sitem['youhuo_pay']) { ?>
    <div class="date_list">
     <ul class="date_nav">
      <li class="disbox-center active">联系方式</li>
     </ul>
     <ol class="user_date">
     <?php  if(!empty($detail['mobile'])) { ?>
      <li> <span>手机</span> <span><?php  echo $detail['mobile'];?></span> </li>
     <?php  } ?>
     <?php  if(!empty($detail['qq'])) { ?>
      <li> <span>QQ</span> <span><?php  echo $detail['qq'];?></span> </li>
     <?php  } ?>
     <?php  if(!empty($detail['wechat'])) { ?>
      <li> <span>微信</span> <span><?php  echo $detail['wechat'];?></span> </li>
     <?php  } ?>
     </ol>
    </div>
    <?php  } ?>
    <div class="date_list">
     <ul class="date_nav">
      <li class="disbox-center active">详细资料</li>
     </ul>
     <ol class="user_date">
      <li> <span>是否想要小孩</span> <span><?php  if(!empty($detail['child'])) { ?><?php  echo $detail['child'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>能否接受异地恋</span> <span><?php  if(!empty($detail['yidi'])) { ?><?php  echo $detail['yidi'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>喜欢的异性类型</span> <span><?php  if(!empty($detail['leixin'])) { ?><?php  echo $detail['leixin'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>能否接受婚前性行为</span> <span><?php  if(!empty($detail['sex2'])) { ?><?php  echo $detail['sex2'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>是否愿意与父母同住</span> <span><?php  if(!empty($detail['fumu'])) { ?><?php  echo $detail['fumu'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <!-- 详细资料页添加魅力部位，兴趣爱好，个人特征-->
      <li> <span>魅力部位</span> <span><?php  if(!empty($detail['meili'])) { ?><?php  echo $detail['meili'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>兴趣爱好</span> <span><?php  if(!empty($detail['aihao'])) { ?><?php  echo $detail['aihao'];?><?php  } else { ?>保密<?php  } ?></span> </li>
      <li> <span>个人特征</span> <span><?php  if(!empty($detail['tezheng'])) { ?><?php  echo $detail['tezheng'];?><?php  } else { ?>保密<?php  } ?></span> </li>
     </ol>
    </div>
    <div class="date_list">
     <ul class="date_nav">
      <li class="disbox-center active">征友条件</li>
     </ul>
     <ol class="user_date">
      <li> <span>常出没地</span> <span><?php  echo $province[$detail['province2']];?></span> </li>
      <li> <span>年龄范围</span> <span><?php  if($detail['age2']==0) { ?>不限<?php  } ?><?php  if($detail['age2']==1) { ?>18-25岁<?php  } ?><?php  if($detail['age2']==2) { ?>26-35岁<?php  } ?><?php  if($detail['age2']==3) { ?>36-45岁<?php  } ?><?php  if($detail['age2']==4) { ?>46-55岁<?php  } ?><?php  if($detail['age2']==5) { ?>55岁以上<?php  } ?></span> </li>
      <li> <span>身高范围</span> <span><?php  if($detail['height2']==0) { ?>不限<?php  } ?><?php  if($detail['height2']==1) { ?>160cm以下<?php  } ?><?php  if($detail['height2']==2) { ?>161-165cm<?php  } ?><?php  if($detail['height2']==3) { ?>166-170cm<?php  } ?><?php  if($detail['height2']==4) { ?>170以上<?php  } ?></span> </li>
      <li> <span>最低学历</span> <span><?php  if($detail['education2']==0) { ?>不限<?php  } ?><?php  if($detail['education2']==1) { ?>高中,中专及以上<?php  } ?><?php  if($detail['education2']==2) { ?>大专及以上<?php  } ?><?php  if($detail['education2']==3) { ?>本科及以上<?php  } ?></span> </li>
      <li> <span>最低收入</span> <span><?php  if($detail['income2']==0) { ?>不限<?php  } ?><?php  if($detail['income2']==1) { ?>2000元以上<?php  } ?><?php  if($detail['income2']==2) { ?>5000元以上<?php  } ?><?php  if($detail['income2']==3) { ?>10000元以上<?php  } ?></span> </li>
     </ol>
    </div>
   </section>
  </div>
  <div class="bottom">
  <?php  if(!empty($attent)) { ?>
   <span onclick="attentionFunc(this)">取消关注</span>
  <?php  } else { ?>
   <span onclick="attentionFunc(this)">关注</span>
  <?php  } ?>
  <?php  if(!empty($black)) { ?>
   <span onclick="blackFunc(this)">取消拉黑</span>
  <?php  } else { ?>
   <span onclick="blackFunc(this)">拉黑</span>
  <?php  } ?>
   <a href="<?php  echo $this->createMobileUrl('report',array('id'=>$id))?>">举报</a>
  </div>
  <?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template(footer, TEMPLATE_INCLUDEPATH)) : (include template(footer, TEMPLATE_INCLUDEPATH));?>
  <a class="another" href="<?php  echo $this->createMobileUrl('detail')?>" data-next=""><i class="re_trg"></i></a>
  <div class="bottomNext"><div>
    <?php  if($ltlx==1) { ?>
    <div><span onclick="sayHello(this)"><i class="icon-bt-hello"></i>打招呼</span></div>
    <?php  } ?>
    <?php  if($ltlx==2) { ?>
    <div><span onclick="chat(this)"><i class="icon-bt-hello"></i>写信</span></div>
    <?php  } ?>
    <?php  if($ltlx==3) { ?>
    <div><span onclick="huifu(this)"><i class="icon-bt-hello"></i>回复并索要联系方式</span></div>
    <?php  } ?>
  </div></div>

  <!--弹窗-->
  <div id="identitymask" class="mask hidden" style="width:100%; height:100%; background:rgba(0,0,0,0.7); position:fixed; left:0; top:0; z-index:1000;">
  <div id="identity" class="simple_info">
   <p class="title"><span>勋章简介</span></p>
   <dl class="examine">
    <dt>
     <img src="../addons/jy_ppp/images/sample_identity.jpg" />
    </dt>
    <dd class="tit">
     身份验证
    </dd>
    <dd>
     身份验证特别有诚意、让Ta安心
    </dd>
   </dl>
   <?php  if(empty($member['card_auth'])) { ?>
   <a href="<?php  echo $this->createMobileUrl('idcard')?>"><span class="btn">我也去验证</span></a>
   <?php  } ?>
  </div></div>
  <div id="vip" class="simple_info hidden">
   <p class="title"><span>勋章简介</span></p>
   <dl class="examine">
    <dt>
     <img src="../addons/jy_ppp/images/sample_vip.jpg" />
    </dt>
    <dd class="tit">
     vip用户
    </dd>
    <dd>
     拥有免费写信等11大特权
    </dd>
   </dl>
  </div>
  <div id="phonemask" class="mask hidden" style="width:100%; height:100%; background:rgba(0,0,0,0.7); position:fixed; left:0; top:0; z-index:1000;">
  <div id="phone" class="simple_info">
   <p class="title"><span>勋章简介</span></p>
   <dl class="examine">
    <dt>
     <img src="../addons/jy_ppp/images/sample_phone.jpg" />
    </dt>
    <dd class="tit">
     手机验证用户
    </dd>
    <dd>
     忘记密码可第一时间找回
    </dd>
   </dl>
   <?php  if(empty($member['mobile_auth'])) { ?>
   <a href="<?php  echo $this->createMobileUrl('mobile')?>"><span class="btn">我也去验证</span></a>
   <?php  } ?>
  </div></div>
  <div class="shadowBanner">
   <div><div>
      <?php  if(!empty($detail['avatar'])) { ?>
          <img style="max-width:220px;max-height:275px" src="<?php  echo tomedia($detail['avatar'])?>"/>
      <?php  } else { ?>
          <?php  if($detail['sex']==1) { ?>
          <img  style="max-width:220px;max-height:275px" src="../addons/jy_ppp/images/boy.png"/>
          <?php  } else { ?>
          <img  style="max-width:220px;max-height:275px" src="../addons/jy_ppp/images/girl.png"/>
          <?php  } ?>
      <?php  } ?>
   </div></div>
  </div>

  <script src="../addons/jy_ppp/js/zepto.min.js"></script>
  <script src="../addons/jy_ppp/js/public.js"></script>
  <script src="../addons/jy_ppp/js/waiting.js"></script>
  <script>
  function attentionFunc(obj){
    $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('op'=>'attent','id'=>$id)),2);?>", data: {}, dataType: 'text', type: 'post', success: function (re) {
          if (re == 1) {
              $.tips("你关注了Ta");
              $(obj).html("取消关注");
          }else if (re == 2) {
              $.tips("你取消了对Ta的关注");
              $(obj).html("关注");
          } else {
              $.tips("删除失败");
          }
      }, error: function () {
          $.tips("网络问题,请稍后重试");
      }
      });
  }
  function blackFunc(obj){
    $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('op'=>'black','id'=>$id)),2);?>", data: {}, dataType: 'text', type: 'post', success: function (re) {
          if (re == 1) {
              $.tips("你拉黑了Ta");
              $(obj).html("取消拉黑");
          }else if (re == 2) {
              $.tips("你取消了对Ta的拉黑");
              $(obj).html("拉黑");
          } else {
              $.tips("删除失败");
          }
      }, error: function () {
          $.tips("网络问题,请稍后重试");
      }
      });
  }
  function sayHello(obj){
    $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('userthumb',array('op'=>'zhaohu','id'=>$id)),2);?>", data: {}, dataType: 'text', type: 'post', success: function (re) {
        if (re == 1) {
            $(obj).removeClass("hello").addClass("hello_out").html('<i class="icon-bt-hello"></i>已打招呼');
            $.tips("招呼已发出，请耐心等待Ta的回复");
        }else if (re == 2) {
            $(obj).removeClass("hello").addClass("hello_out").html('<i class="icon-bt-hello"></i>已打招呼');
            $.tips("你今天已经向Ta打过招呼了。");
        } else {
            $.tips("网络问题,请稍后重试");
        }
    }, error: function () {
        $.tips("网络问题,请稍后重试");
    }
    });
  }
  function huifu(obj){
      $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('userthumb',array('op'=>'huifu','id'=>$id)),2);?>", data: {}, dataType: 'text', type: 'post', success: function (re) {
          if (re == 1) {
              window.location.href="<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('cz',array('id'=>$id)),2)?>";
          }else if (re == 2) {
              window.location.href="<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('xhdoubi',array('id'=>$id)),2)?>";
          }
          else if (re == 3) {
              window.location.href="<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('chat',array('id'=>$id)),2)?>";
          } else {
              $.tips("网络问题,请稍后重试");
          }
      }, error: function () {
          $.tips("网络问题,请稍后重试");
      }
      });
  }
  function chat(obj){
      window.location.href="<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('chat',array('id'=>$id)),2)?>";
  }
 $(".smallShow").bind("click",function(){
    $(".shadowBanner").show();
    var a=$(".shadowBanner img").height();
    $(".shadowBanner img").css({position:"relative",top:"50%",marginTop:-a/2-50});
    });
   $(".shadowBanner").bind("click",function(){
        $(this).hide();
    });
   $("#phone").bind("click",function(){
    $("#phonemask").show();
   });
   $("#phonemask").bind("click",function(event){
    if(event.target == this){
      $(this).hide();
    }
   });
   $(".identity").bind("click",function(){
    $("#identitymask").show();
   });
   $("#identitymask").bind("click",function(event){
    if(event.target == this){
      $(this).hide();
    }
   });
   function toWantPic(){
      $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('op'=>'pic','id'=>$id)),2);?>", data: {}, dataType: 'text', type: 'post', success: function (re) {
          if (re == 1) {
              $.tips("已发送上传照片邀请");
          }else if (re == 2) {
              $.tips("已经索要过照片了");
          } else {
              $.tips("删除失败");
          }
          MyAlbum.hideAlert();
      }, error: function () {
          $.tips("网络问题,请稍后重试");
          MyAlbum.hideAlert();
      }
      });
   }
  </script>
  <?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
 </body>
</html>