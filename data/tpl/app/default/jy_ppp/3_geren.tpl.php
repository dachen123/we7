<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
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
<link href="../addons/jy_ppp/css/public_headmessage.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/head_nav.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/user-list.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/user_box.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/myspace.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/home_page.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/herd_hello.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .app-follow{position:fixed;bottom:55px;width: 100%;right:25px;bottom:25px;z-index:9999;
      -moz-animation: scale-animation 1s linear;     /* Firefox */
      -webkit-animation: scale-animation 1s linear;     /* Safari 和 Chrome */
      -o-animation: scale-animation 1s linear;     /* Opera */
      animation: scale-animation 1s linear;
      -webkit-animation-fill-mode: forwards;
      -moz-animation-iteration-count: infinite;
      -webkit-animation-iteration-count: infinite;
      -o-animation-iteration-count: infinite;
      animation-iteration-count: infinite;
    }
    .app-follow a{max-width: 480px;margin: 0 auto;text-align:right;}
    .app-follow img{width:45px;}


    @keyframes scale-animation{0%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
    50%{-ms-transform:scale(1.05);-moz-transform:scale(1.05);-webkit-transform:scale(1.05);-o-transform:scale(1.05);transform:scale(1.05)}
    100%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
    }
    @-moz-keyframes scale-animation{0%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
    50%{-ms-transform:scale(1.05);-moz-transform:scale(1.05);-webkit-transform:scale(1.05);-o-transform:scale(1.05);transform:scale(1.05)}
    100%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
    }
    @-webkit-keyframes scale-animation{0%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
    50%{-ms-transform:scale(1.05);-moz-transform:scale(1.05);-webkit-transform:scale(1.05);-o-transform:scale(1.05);transform:scale(1.05)}
    100%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
    }
    @-o-keyframes scale-animation{0%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
    50%{-ms-transform:scale(1.05);-moz-transform:scale(1.05);-webkit-transform:scale(1.05);-o-transform:scale(1.05);transform:scale(1.05)}
    100%{-ms-transform:scale(1);-moz-transform:scale(1);-webkit-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
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
<body yy="no">
<div>
    <div class="loading"><i></i>加载中...</div>
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
        <section class="content wxcontent" id="myspace_section">
        <section class="my_space">
        <div class="user_box">
            <dl class="user">
                <?php  if(empty($_GPC['xuniid']) || $member['type']!=3) { ?>
                <a href="<?php  echo $this->createMobileUrl('upload')?>">
                <?php  } else { ?>
                <a href="<?php  echo $this->createMobileUrl('upload',array('xuniid'=>$_GPC['xuniid']))?>">
                <?php  } ?>
                <dt id="change_icon">
                    <?php  if(!empty($member['avatar'])) { ?>
                        <img src="<?php  echo tomedia($member['avatar'])?>"/>
                    <?php  } else { ?>
                        <?php  if($member['sex']==1) { ?>
                        <img src="../addons/jy_ppp/images/boy.png"/>
                        <?php  } else { ?>
                        <img src="../addons/jy_ppp/images/girl.png"/>
                        <?php  } ?>
                        <div class="checking">点击上传</div>
                    <?php  } ?>
                </dt></a>
                <dd><b class="name"><?php  echo $member['nicheng'];?></b>
                <p id="desc"></p>
                <p>
                    <?php  if($member['sex']==1 && $member['type']!=3) { ?>
                    <?php  if(empty($baoyue)) { ?>
                    <a href="<?php  echo $this->createMobileUrl('baoyue')?>" class="vip noCheck">VIP</a>
                    <?php  } else { ?>
                    <a href="<?php  echo $this->createMobileUrl('baoyue')?>" class="vip Check">VIP</a>
                    <?php  } ?>
                    <?php  } ?>
                    <?php  if($member['type']!=3) { ?>
                        <?php  if($sitem['sms_type']!=2) { ?>
                            <?php  if($member['mobile_auth']==1) { ?>
                            <a href="<?php  echo $this->createMobileUrl('mobile')?>" class="phone Check">手机认证</a>
                            <?php  } else { ?>
                            <a href="<?php  echo $this->createMobileUrl('mobile')?>" class="phone noCheck">手机认证</a>
                            <?php  } ?>
                        <?php  } ?>
                        <?php  if($member['card_auth']==1) { ?>
                        <a href="<?php  echo $this->createMobileUrl('idcard')?>" class="identity Check">身份认证</a>
                        <?php  } else { ?>
                        <a href="<?php  echo $this->createMobileUrl('idcard')?>" class="identity noCheck">身份认证</a>
                        <?php  } ?>
                    <?php  } ?>
                </p>
                </dd>
            </dl>
        </div>
        <?php  if(empty($_GPC['xuniid']) || $member['type']!=3) { ?>
        <div class="btn">
            <a href="<?php  echo $this->createMobileUrl('mylove')?>" class="disbox-center">我关注的</a>
            <a href="<?php  echo $this->createMobileUrl('loveme')?>" class="disbox-center">关注我的</a>
        </div>
        <?php  } ?>

        <?php  if($member['sex']==1 && $member['type']!=3) { ?>

            <?php  if(empty($sitem['doubi'])) { ?><?php  $sitem['doubi']='豆币'?><?php  } ?>
            <?php  if(empty($sitem['unit'])) { ?><?php  $sitem['unit']='豆'?><?php  } ?>
            <div class="section_1">
                <a href="<?php  echo $this->createMobileUrl('doubi')?>"><ul class="disbox-hor user-list">
                    <li class="disbox-flex"><?php  echo $sitem['doubi'];?>账户</li>
                    <?php  if(empty($member['credit'])) { ?>
                    <li class="disbox-center pink">购买<div class="right_trg"></div></li>
                    <?php  } else { ?>
                    <li class="disbox-center pink"><?php  echo $member['credit'];?><div class="right_trg"><?php  echo $sitem['unit'];?></div></li>
                    <?php  } ?>
                </ul></a>
                <a href="<?php  echo $this->createMobileUrl('baoyue')?>" style="margin-top:1px"><ul class="disbox-hor user-list">
                    <li class="disbox-flex">写信包月</li>
                    <?php  if(empty($baoyue)) { ?>
                    <li class="disbox-center pink">开通<div class="right_trg"></div></li>
                    <?php  } else { ?>
                    <li class="disbox-center pink">剩余<?php  echo $baoyue;?><div class="right_trg">天</div></li>
                    <?php  } ?>
                </ul></a>
                <?php  if(!empty($baoyue) && $member['sex']==1) { ?>
                <a href="<?php  echo $this->createMobileUrl('shouxin')?>" style="margin-top:1px"><ul class="disbox-hor user-list">
                    <li class="disbox-flex">收信宝</li>
                    <?php  if(empty($shouxin)) { ?>
                    <li class="disbox-center pink">开通<div class="right_trg"></div></li>
                    <?php  } else { ?>
                    <li class="disbox-center pink">剩余<?php  echo $shouxin;?><div class="right_trg">天</div></li>
                    <?php  } ?>
                </ul></a>
                <?php  } ?>
            </div>

        <?php  } ?>
        <?php  if(empty($_GPC['xuniid']) || $member['type']!=3) { ?>
        <div class="section_1">
            <a href="<?php  echo $this->createMobileUrl('upload')?>"><ul class="disbox-hor user-list">
                <li class="disbox-flex">我的相册</li>
                <li class="disbox-center">
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
            <a href="<?php  echo $this->createMobileUrl('beizhu')?>" style="margin-top:1px"><ul class="disbox-hor user-list">
                <li class="disbox-flex">内心独白</li>
                <li class="disbox-center">
                <?php  if(empty($member['beizhu'])) { ?>
                <span class="no_finish">未完成</span>
                <?php  } ?>
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
        </div>
        <div class="section_1">
            <a href="<?php  echo $this->createMobileUrl('basic')?>"><ul class="disbox-hor user-list">
                <li class="disbox-flex">基本资料</li>
                <li class="disbox-center">
                <?php  if(empty($basic) || !empty($basic['blank'])) { ?>
                <span class="no_finish">未完成</span>
                <?php  } ?>
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
            <a href="<?php  echo $this->createMobileUrl('desc')?>" style="margin-top:1px"><ul class="disbox-hor user-list">
                <li class="disbox-flex">详细资料</li>
                <li class="disbox-center">
                <?php  if(empty($desc) || !empty($desc['blank'])) { ?>
                <span class="no_finish">未完成</span>
                <?php  } ?>
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
            <?php  if($member['type']!=3) { ?>
            <a href="<?php  echo $this->createMobileUrl('match')?>" style="margin-top:1px"><ul class="disbox-hor user-list">
                <li class="disbox-flex">征友条件</li>
                <li class="disbox-center">
                <?php  if(empty($match) || !empty($match['blank'])) { ?>
                <span class="no_finish">未完成</span>
                <?php  } ?>
                <div class="re_trg">
                </div>
            </ul></a>
            <?php  } ?>
        </div>
        <div class="section_1">
            <a href="<?php  echo $this->createMobileUrl('setting')?>"><ul class="disbox-hor user-list">
                <li class="disbox-flex">设置中心</li>
                <li class="disbox-center">
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
        </div>
        <?php  } else { ?>
        <div class="section_1">
            <a href="<?php  echo $this->createMobileUrl('upload',array('xuniid'=>$_GPC['xuniid']))?>"><ul class="disbox-hor user-list">
                <li class="disbox-flex">我的相册</li>
                <li class="disbox-center">
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
            <a href="<?php  echo $this->createMobileUrl('beizhu',array('xuniid'=>$_GPC['xuniid']))?>" style="margin-top:1px"><ul class="disbox-hor user-list">
                <li class="disbox-flex">内心独白</li>
                <li class="disbox-center">
                <?php  if(empty($member['beizhu'])) { ?>
                <span class="no_finish">未完成</span>
                <?php  } ?>
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
        </div>
        <div class="section_1">
            <a href="<?php  echo $this->createMobileUrl('basic',array('xuniid'=>$_GPC['xuniid']))?>"><ul class="disbox-hor user-list">
                <li class="disbox-flex">基本资料</li>
                <li class="disbox-center">
                <?php  if(empty($basic) || !empty($basic['blank'])) { ?>
                <span class="no_finish">未完成</span>
                <?php  } ?>
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
            <a href="<?php  echo $this->createMobileUrl('desc',array('xuniid'=>$_GPC['xuniid']))?>" style="margin-top:1px"><ul class="disbox-hor user-list">
                <li class="disbox-flex">详细资料</li>
                <li class="disbox-center">
                <?php  if(empty($desc) || !empty($desc['blank'])) { ?>
                <span class="no_finish">未完成</span>
                <?php  } ?>
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
        </div>
        <div class="section_1">
            <a href="<?php  echo $this->createMobileUrl('account',array('xuniid'=>$_GPC['xuniid']))?>"><ul class="disbox-hor user-list">
                <li class="disbox-flex">账号中心</li>
                <li class="disbox-center">
                <div class="re_trg">
                </div>
                </li>
            </ul></a>
        </div>

        <?php  } ?>
        <div style="height:70px"></div>
        </section>
        </section>
    </div>
</div>

<!--弹窗-->
<div id="identity" class="simple_info hidden">
    <p class="title">
        <span>勋章简介</span>
    </p>
    <dl class="examine">
        <dt><img src="../addons/jy_ppp/images/sample_identity.jpg"/></dt>
        <dd class="tit">身份验证</dd>
        <dd>身份验证特别有诚意、让Ta安心</dd>
    </dl>
</div>
<div id="vip" class="simple_info hidden">
    <p class="title">
        <span>勋章简介</span>
    </p>
    <dl class="examine">
        <dt><img src="../addons/jy_ppp/images/sample_vip.jpg"/></dt>
        <dd class="tit">vip用户</dd>
        <dd>拥有免费写信等11大特权</dd>
    </dl>
</div>
<div id="phone" class="simple_info hidden">
    <p class="title">
        <span>勋章简介</span>
    </p>
    <dl class="examine">
        <dt><img src="../addons/jy_ppp/images/sample_phone.jpg"/></dt>
        <dd class="tit">手机验证用户</dd>
        <dd>忘记密码可第一时间找回</dd>
    </dl>
</div>
<!--关注-->
<?php  if($member['type']!=3) { ?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template(menu, TEMPLATE_INCLUDEPATH)) : (include template(menu, TEMPLATE_INCLUDEPATH));?>
<?php  } else { ?>
<div class="app-follow" >
    <a href="<?php  echo $this->createMobileUrl('dy_user')?>"><img style="background: none" src="../addons/jy_ppp/images/dy_main.png" /></a>
</div>
<?php  } ?>
<!-- h5menu end -->
<script type="text/javascript" src="http://libs.baidu.com/jquery/1.9.0/jquery.min.js"></script>
<script src="../addons/jy_ppp/js/area_id.js"></script>
<script type="text/javascript">
    if ($(".top_mess").length > 0) {
        setTimeout(function () {
            $(".mess_show").addClass('none');
            $(".mess_hide").removeClass('none').show();
        }, 1000);

    }
    <?php  if(empty($sitem['user_addr'])) { ?>
        var str=<?php  echo $nianlin;?>+'岁·'+area_array[<?php  echo $member['province'];?>]+sub_array[<?php  echo $member['province'];?>][<?php  echo $member['city'];?>];
    <?php  } else { ?>
        <?php  if($sitem['user_addr']==2) { ?>
            var str=<?php  echo $nianlin;?>+'岁·'+sub_array[<?php  echo $member['province'];?>][<?php  echo $member['city'];?>];
        <?php  } else { ?>
            var str=<?php  echo $nianlin;?>+'岁·'+area_array[<?php  echo $member['province'];?>];
        <?php  } ?>
    <?php  } ?>
    
    document.getElementById("desc").innerHTML=str;
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</body>
</html>