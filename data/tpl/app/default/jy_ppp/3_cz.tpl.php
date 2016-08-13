<?php defined('IN_IA') or exit('Access Denied');?><html> 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
    
        <link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet" type="text/css" />
        <link href="../addons/jy_ppp/css/public.css" rel="stylesheet" type="text/css" />
        <link href="../addons/jy_ppp/css/pay_intercept.css" rel="stylesheet" type="text/css" />
        <link href="../addons/jy_ppp/css/swiper.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../addons/jy_ppp/js/swiper.min.js"></script>
    
</head>
<body>
<div class="top_blank"></div>
<nav class="nav">
    <h2>开通后即可与美女无限畅聊</h2>

    <div class="left" onclick="history.go(-1)">
        <i class="le_trg"></i>返回
    </div>
</nav>
<?php  if(!empty($sitem['huafei']) && !empty($sitem['huafei_thumb'])) { ?>
<img src="<?php  echo tomedia($sitem['huafei_thumb'])?>" style="width:100%">
<div class="content">
<?php  } else { ?>
<div class="content">
    <p class="pink" style="text-align: center;line-height: 30px">
        <b style="font-size: 20px">【让你轻松谈恋爱】</b><br/>
    </p>
<?php  } ?>

    <div style="clear: both"></div>

    <section data-type="baoyue">
        <p class="click_op_top"><i class="write"></i>包月写信<span>和所有心仪MM无限制沟通</span></p>

        <div class="box-shadow">
            <?php  if(empty($category)) { ?>
                <?php  if($weixin==1) { ?>
                <a href="<?php  echo $this->createMobileUrl('pay',array('op'=>'baoyue','fee'=>100))?>"><ul class="click_op" style="margin:0">
                <?php  } else { ?>
                <a href="<?php  echo $this->createMobileUrl('pcpay',array('op'=>'baoyue','fee'=>100))?>"><ul class="click_op" style="margin:0">
                <?php  } ?>
                    <li>
                        <p class="tit blue"><label style="width:100px">90天</label><span>￥100</span><span></span></p>
                    </li>
                    <li style="color:#52A4CA">购买<i class="re_trg"></i></li>
                </ul></a>
                <?php  if($weixin==1) { ?>
                <a href="<?php  echo $this->createMobileUrl('pay',array('op'=>'baoyue','fee'=>50))?>"><ul class="click_op" style="margin:0">
                <?php  } else { ?>
                <a href="<?php  echo $this->createMobileUrl('pcpay',array('op'=>'baoyue','fee'=>50))?>"><ul class="click_op" style="margin:0">
                <?php  } ?>
                    <li>
                        <p class="tit blue"><label style="width:100px">30天</label><span>￥50</span></p>
                    </li>
                    <li style="color:#52A4CA">购买<i class="re_trg"></i></li>
                </ul></a>
            <?php  } else { ?>
                <?php  if(is_array($category)) { foreach($category as $c) { ?>
                    <?php  if($weixin==1) { ?>
                    <a href="<?php  echo $this->createMobileUrl('pay',array('op'=>'baoyue','fee'=>$c['fee']))?>"><ul class="click_op" style="margin:0">
                    <?php  } else { ?>
                    <a href="<?php  echo $this->createMobileUrl('pcpay',array('op'=>'baoyue','fee'=>$c['fee']))?>"><ul class="click_op" style="margin:0">
                    <?php  } ?>
                        <li>
                            <p class="tit blue"><label style="width:100px"><?php  echo $c['baoyue'];?>天</label><span><?php  if($c['fee']>0) { ?>￥<?php  echo $c['fee'];?><?php  } else { ?>免费<?php  } ?><?php  if(!empty($sitem['huafei']) && !empty($c['huafei'])) { ?>,送<?php  echo $c['huafei'];?>元话费<?php  } ?></span></p>
                        </li>
                        <li style="color:#52A4CA">购买<i class="re_trg"></i></li>
                    </ul></a>
                <?php  } } ?>
            <?php  } ?>
        </div>
    </section>
    <?php  if(empty($sitem['doubi'])) { ?><?php  $sitem['doubi']=='豆币'?><?php  } ?>
    <?php  if(empty($sitem['unit'])) { ?><?php  $sitem['unit']=='豆'?><?php  } ?>
    <section data-type="doubi" >
        <p class="click_op_top"><i class="bean"></i><?php  echo $sitem['doubi'];?>服务<span>20<?php  echo $sitem['doubi'];?>可与一位美女无限制沟通</span></p>

        <div class="box-shadow" >
            <?php  if(empty($category2)) { ?>
                <?php  if($weixin==1) { ?>
                <a href="<?php  echo $this->createMobileUrl('pay',array('op'=>'doubi','fee'=>100))?>"><ul class="click_op">
                <?php  } else { ?>
                <a href="<?php  echo $this->createMobileUrl('pcpay',array('op'=>'doubi','fee'=>100))?>"><ul class="click_op">
                <?php  } ?>
                    <li>
                        <p class="tit yellow"><label style="width:100px">1300<?php  echo $sitem['unit'];?></label><span>￥100</span><span></span></p>
                    </li>
                    <li style="color:#E2A41F">购买<i class="re_trg"></i></li>
                </ul></a>
                <?php  if($weixin==1) { ?>
                <a href="<?php  echo $this->createMobileUrl('pay',array('op'=>'doubi','fee'=>50))?>"><ul class="click_op">
                <?php  } else { ?>
                <a href="<?php  echo $this->createMobileUrl('pcpay',array('op'=>'doubi','fee'=>50))?>"><ul class="click_op">
                <?php  } ?>
                    <li>
                        <p class="tit yellow"><label style="width:100px">600<?php  echo $sitem['unit'];?></label><span>￥50</span></p>
                    </li>
                    <li style="color:#E2A41F">购买<i class="re_trg"></i></li>
                </ul></a>

                <?php  if($weixin==1) { ?>
                <a href="<?php  echo $this->createMobileUrl('pay',array('op'=>'doubi','fee'=>30))?>"><ul class="click_op">
                <?php  } else { ?>
                <a href="<?php  echo $this->createMobileUrl('pcpay',array('op'=>'doubi','fee'=>30))?>"><ul class="click_op">
                <?php  } ?>
                    <li>
                        <p class="tit yellow"><label style="width:100px">300<?php  echo $sitem['unit'];?></label><span>￥30</span></p>
                    </li>
                    <li style="color:#E2A41F">购买<i class="re_trg"></i></li>

                </ul></a>
            <?php  } else { ?>
                <?php  if(is_array($category2)) { foreach($category2 as $c) { ?>
                    <?php  if($weixin==1) { ?>
                    <a href="<?php  echo $this->createMobileUrl('pay',array('op'=>'doubi','fee'=>$c['fee']))?>"><ul class="click_op" style="margin:0">
                    <?php  } else { ?>
                    <a href="<?php  echo $this->createMobileUrl('pcpay',array('op'=>'doubi','fee'=>$c['fee']))?>"><ul class="click_op" style="margin:0">
                    <?php  } ?>
                        <li>
                            <p class="tit yellow"><label style="width:100px"><?php  echo $c['credit'];?><?php  echo $sitem['unit'];?></label><span><?php  if($c['fee']>0) { ?>￥<?php  echo $c['fee'];?><?php  } else { ?>免费<?php  } ?><?php  if(!empty($sitem['huafei']) && !empty($c['huafei'])) { ?>,送<?php  echo $c['huafei'];?>元话费<?php  } ?></span></p>
                        </li>
                        <li style="color:#E2A41F">购买<i class="re_trg"></i></li>
                    </ul></a>
                <?php  } } ?>
            <?php  } ?>
        </div>
    </section>
    <?php  if(!empty($cz_list)) { ?>
    <div class="swiper-container swiper-container-vertical" style="height:250px;margin-top:20px;border-top: 1px #e0e0e0 solid;padding-top:5px">
            <div class="swiper-wrapper">
            <?php  if(is_array($cz_list)) { foreach($cz_list as $c) { ?>
                <div class="swiper-slide stop-swiping"><span style="color:#fd6b8f">[<?php  echo $c['user'];?>]</span><?php  echo $c['name'];?></div>
            <?php  } } ?>
            </div>
        </div>
    <?php  } ?>
</div>
</body>
    <script src="../addons/jy_ppp/js/zepto.min.js"></script>
    <script src="../addons/jy_ppp/js/public.js"></script>
    <script type="text/javascript">
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 7,
        direction: 'vertical',
        speed: 3000,
        autoplay: 1,
        loop: true,
        loopedSlides: 100,
        noSwiping: true,
        noSwipingClass: 'stop-swiping',
        spaceBetween: 1
    });
    </script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</html>
