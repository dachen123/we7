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
<link href="../addons/jy_ppp/css/public_disbox.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/write_msg.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/new_Chat.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/face.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/voice.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/common_alert.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/bottom_call.css" rel="stylesheet" type="text/css" />
</head>
<style type="text/css">
	.Material>dt img {
	    display: block;
	    width: 60px;
	    max-height: 73px;
	}
	.go_vip {
	    border-radius: 5px;
	    background: #fff;
	    overflow: hidden;
	    border-left: 1px solid #E9E8E4;
	    border-top: 1px solid #E9E8E4;
	    border-right: 1px solid #E9E8E4;
	    border-bottom: 2px #E9E8E4 solid;
	    width: 80%;
	    position: fixed;
	    left: 10%;
	    top: 25%;
	    z-index: 999999;
	}
	.go_vip h3 {
	    width: 96%;
	    border-bottom: 2px #ff819b solid;
	    height: 40px;
	    line-height: 40px;
	    padding-left: 4%;
	    font-size: 16px;
	    font-weight: bold;
	}
	.go_vip h3 a {
	    float: right;
	    margin-right: 4%;
	    margin-top: 13px;
	    display: block;
	    width: 14px;
	}
	.go_vip h3 a img {
	    width: 100%;
	}
	.go_vip ul {
	    padding: 0 4%;
	}
	.go_vip ul li {
	    margin: 15px 0;
	    font-size: 16px;
	    font-weight: bold;
	    clear: both;
	}
	.go_vip ul li span {
	    color: #f96a88;
	    margin-left: 8px;
	    font-family: Arial, Helvetica, sans-serif;
	}
	.go_vip ul li input {
	    float: left;
	    margin-right: 8px;
	}
	.go_vip .vip_tip {
	    background-color: #ffcc00;
	    color: white;
	    padding-left: 4%;
	    height: 35px;
	    line-height: 35px;
	    font-size: 14px;
	}
	.go_vip .pay_btn {
	    width: 90%;
	    margin-left: 5%;
	}
	.go_vip .pay_btn a.other_pay {
	    color: gray;
	    border: 1px #cbcbcb solid;
	    margin: 10px 0 25px 0;
	    width: 99%;
	}
	.go_vip .pay_btn a {
	    display: block;
	    width: 100%;
	    text-align: center;
	    border-radius: 5px;
	    height: 35px;
	    line-height: 35px;
	}
</style>
<body>
<div class="top_blank">
</div>
<div id="chat_nav" class="nav" style="position: absolute;">
	<h2 class="title" id="title_nav"><?php  if(empty($id)) { ?>管理员<?php  } else { ?><?php  echo $detail['nicheng'];?><?php  } ?></h2>
	<a class="left" href="<?php  echo $this->createMobileUrl('mail')?>">
		<i class="le_trg"></i>返回
	</a>
	<a class="right" href="<?php  echo $this->createMobileUrl('chat',array('op'=>'next'))?>">
		下一封
	</a>
</div>
<div id="chat_detail" class="content">
	
	<?php  if(!empty($id)) { ?>
		<div>
		<dl class="Material">
			<dt>
			<a style="display:block" href="<?php  echo $this->createMobileUrl('detail',array('id'=>$id))?>">
				<?php  if(!empty($detail['avatar'])) { ?>
                      <img src="<?php  echo tomedia($detail['avatar'])?>" />
                  <?php  } else { ?>
                      <?php  if($detail['sex']==1) { ?>
                          <img src="../addons/jy_ppp/images/boy.png"/>
                      <?php  } else { ?>
                          <img src="../addons/jy_ppp/images/girl.png"/>
                      <?php  } ?>
                  <?php  } ?>
            </a>
			</dt>
			
			<dd>
			<?php  $now=time();?>
			<p class="nick_name">
				<?php  if(!empty($detail['brith'])) { ?>
                    <?php  $birthday=$detail['brith'];$month=0;if(date('m', $now)>date('m', $birthday))$month=1;if(date('m', $now)==date('m', $birthday))if(date('d', $now)>=date('d', $birthday))$month=1;$nianlin=date('Y', $now)-date('Y', $birthday)+$month;?>
                    <?php  echo $nianlin;?>岁·<?php  } ?>
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
                    <?php  if(!empty($detail['height'])) { ?>·<?php  echo $detail['height'];?>cm<?php  } ?>
			</p>
			<p class="info">
				<?php  if(!empty($detail['marriage'])) { ?><?php  echo $detail['marriage'];?><?php  } ?> <?php  if(!empty($detail['income'])) { ?><?php  echo $detail['income'];?><?php  } ?>
			</p>
			<p>
				<?php  if(!empty($detail['mobile_auth'])) { ?>
				<span data-value="phone" class="phone ">手机认证</span>
				<?php  } else { ?>
				<span data-value="phone" class="phone noCheck">手机认证</span>
				<?php  } ?>
				<?php  if(!empty($detail['card_auth'])) { ?>
				<span data-value="identity" class="identity">身份认证</span>
				<?php  } else { ?>
				<span data-value="identity" class="identity noCheck">身份认证</span>
				<?php  } ?>
				<?php  if($sitem['youhuo_pay'] && empty($baoyue)) { ?>
				<span data-value="phone" class="phone " style="background-color:rgba(236,32,88,0.75)" onclick="lookwechat()">查看微信</span>
				<?php  } ?>
			</p>
			</dd>
		</dl>
		</div>
	<?php  } ?>
	<?php  if(count($list)>2) { ?>
	<div class="chat_record" id="see_more_record">
		<span>更多聊天记录</span>
	</div>
	<?php  } ?>
	<ul id="chat_list" class="chatList">
		<?php  if(is_array($list)) { foreach($list as $l) { ?>
			<li class="time" data-id="<?php  echo $l['id'];?>"><?php  echo date('Y-m-d G:i',$l['createtime'])?></li>
			<?php  if($l['sendid']==$mid) { ?>
			<li class="me disbox-hor dis_block">
			<div class="f_right" style="position: relative;">
                   <img src="<?php  echo tomedia($avatar)?>">
			</div>
			<div class="disbox-flex f_right no_padding">
				<p class="talk"><?php  echo $l['content'];?></p>
			</div>
			<div class="f_right"></div>
			</li>
			<?php  } else { ?>
			<li class="you disbox-hor dis_block ">
			<div class="f_left" style="position: relative;">
				<?php  if($l['sendid']==0) { ?>
					<?php  if(!empty($sitem['adminthumb'])) { ?>
	                <img src="<?php  echo tomedia($sitem['adminthumb'])?>">
	                <?php  } else { ?>
	                <img src="../addons/jy_ppp/images/adminthumb.png">
	                <?php  } ?>
	            <?php  } else { ?>
					<?php  if(!empty($detail['avatar'])) { ?>
	                    <img src="<?php  echo tomedia($detail['avatar'])?>">
	                <?php  } else { ?>
	                    <?php  if($detail['sex']==1) { ?>
	                        <img src="../addons/jy_ppp/images/boy.png"/>
	                    <?php  } else { ?>
	                        <img src="../addons/jy_ppp/images/girl.png"/>
	                    <?php  } ?>
	                <?php  } ?>
				<?php  } ?>
			</div>
			<div class="disbox-flex f_left no_padding ">
				<?php  if($l['leixing']==2) { ?>
				<p class="talk" onclick="playaudio(this)">
				<?php  } else { ?>
				<p class="talk">
				<?php  } ?>
					<?php  if($l['leixing']==0) { ?><?php  echo $l['content'];?><?php  } ?>
					<?php  if($l['leixing']==1) { ?><img src="<?php  echo $l['picurl'];?>" style="width:120px"> <?php  } ?>
					<?php  if($l['leixing']==2) { ?><audio style="display:none" preload="auto" src="<?php  echo $l['media_id'];?>" controls class="audio"></audio><img src="../addons/jy_ppp/images/voice.png" style="background:#fff;margin-left: 80px;background-image:none;width:20px" ><?php  } ?>
				</p>
			</div>
			<div class="f_left"></div>
			</li>
			<?php  } ?>
		<?php  } } ?>
	</ul>
</div>

<!-- 1.-- 不是会员时的按钮 -->
<?php  if(!empty($id)) { ?>
<!-- <a href="../addons/jy_ppp/pay_intercept.html"><div class="but_send"><div>回复并索要联系方式</div></div></a> -->
  <div class="bottomNext"><div>
    <?php  if($ltlx==1 && empty($wt)) { ?>
    <div><span onclick="sayHello(this)"><i class="icon-bt-hello"></i>打招呼</span></div>
    <?php  } ?>
    <?php  if($ltlx==3) { ?>
    <div><span onclick="huifu(this)"><i class="icon-bt-hello"></i>回复并索要联系方式</span></div>
    <?php  } ?>
  </div></div>
<?php  } ?>
<!--sp 遮罩层-->
<div class="mask" id="mask">
</div>
<div class="alert-tphone " id="alert-tphone">
	<p>
		请填写您的手机号码进行验证
	</p>
	<input type="tel" name="mobilevalue" id="mobilevalue" class="ph-number box-sizing"/>
	<span id="errorMsg"></span>
	<a id="check_phone" class="btn">下一步</a>
	<span id="closed" class="closed"></span>
</div>
<?php  if($sitem['youhuo_pay']  && empty($baoyue)) { ?>
<div class="go_vip" id="go_vip" style="display:none">
    <h3><a hidefocus="#" onclick="$('.go_vip').hide()"><img src="../addons/jy_ppp/images/closed.png" style="background:none;background-color:#fff"></a>非VIP包月用户不能聊天
    </h3>

    <div class="vip_tip">开通包月服务可无限制聊天</div>
    <ul>
    	<?php  if(empty($baoyue_cate)) { ?>
    		<li>90天<span>¥100</span><input name="baoyue_pay" checked="true" type="radio" value="100"></li>
    		<li>30天<span>¥50</span><input name="baoyue_pay" type="radio" value="50"></li>
    	<?php  } else { ?>
    		<?php  $bi=1;?>
    		<?php  if(is_array($baoyue_cate)) { foreach($baoyue_cate as $b) { ?>
    		<li><?php  echo $b['baoyue'];?>天<span>¥<?php  echo $b['fee'];?></span><input name="baoyue_pay" <?php  if($bi==1) { ?> checked="true" <?php  } ?> type="radio" value="<?php  echo $b['fee'];?>"></li>
    		<?php  $bi++;?>
    		<?php  } } ?>
    	<?php  } ?>
    </ul>
    <div class="pay_btn">
        <a class="other_pay" style="background-color:#3dca23; color:white;" href="#">微信安全支付</a>
    </div>
</div>
<?php  } ?>
<!-- 2.-- 选项回答部分-->
<?php  if($member['sex']==1 && !empty($wt) && $ltlx==1) { ?>
<div class="answer">
	<p class="main_tit">回答她的问题:</p>
	<ol>
		<?php  if(is_array($wt)) { foreach($wt as $w) { ?>
		<li class="selAns" data-id="<?php  echo $w['id'];?>"><?php  echo $w['name'];?></li>
		<?php  } } ?>
	</ol>
</div>
<?php  } ?>
<div style="height:80px"></div>
<!-- 3.-- 输入框回答部分 -->
<?php  if($ltlx==2) { ?>
<div class="send_out" id="sendbtn"><div>
    <ul>
        <li><textarea type="text"></textarea>
            <span class="provide hidden">0/240字</span></li>
        <li><button style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);">发送</button></li>
    </ul>
</div></div>
<?php  } ?>
<div id="send_nav" class="nav hidden" style="z-index: 9999">
    <h2 class="title">发消息</h2>
    <div class="left" id="send_cancel">取消</div>
    <div class="right" id="send_ok">
        <span id="btnSendMsg" class="send_btn">发送</span>
    </div>
</div>
<div id="btn_send_msg" class="talk_box hidden"><div>
    <ul>
        <li><textarea type="text" id="talk_area"></textarea>
        <span class="provide" id="provide">0/240字</span></li>
    </ul>
    <div class="face_icon">
        <div class="smile" data-exp=""></div>
        <ul class="face" id="face">
        <li data-express="害羞"><i class="icon face_2"></i></li><li data-express="喜欢"><i class="icon face_3"></i></li><li data-express="快哭了"><i class="icon face_4"></i></li><li data-express="爱心"><i class="icon face_5"></i></li><li data-express="擦汗"><i class="icon face_6"></i></li><li data-express="愤怒"><i class="icon face_7"></i></li><li data-express="可爱"><i class="icon face_8"></i></li><li data-express="小可怜"><i class="icon face_9"></i></li><li data-express="尴尬"><i class="icon face_10"></i></li><li data-express="红唇"><i class="icon face_12"></i></li><li data-express="难过"><i class="icon face_13"></i></li><li data-express="亲亲"><i class="icon face_14"></i></li><li data-express="委屈"><i class="icon face_15"></i></li><li data-express="疑惑"><i class="icon face_16"></i></li><li data-express="拥抱"><i class="icon face_17"></i></li><li data-express="再见"><i class="icon face_18"></i></li><li data-express="咖啡"><i class="icon face_19"></i></li><li data-express="礼物"><i class="icon face_20"></i></li><li data-express="玫瑰"><i class="icon face_21"></i></li></ul>
    </div>
</div></div>
</body>
<script src="../addons/jy_ppp/js/zepto.min.js"></script>
<script src="../addons/jy_ppp/js/public.js"></script>
<script src="../addons/jy_ppp/js/waiting.js"></script>
<script src="../addons/jy_ppp/js/expression.js"></script>
<script>

	$(function ($) {
        $('.other_pay').click(function () {

            var select_fee = $("input[name='baoyue_pay']:checked").val();
            <?php  if($weixin==1) { ?>
            	window.location.href = "<?php  echo $this->createMobileUrl('pay',array('op'=>'baoyue'))?>"+"&fee="+select_fee;
            <?php  } else { ?>
            	window.location.href = "<?php  echo $this->createMobileUrl('pcpay',array('op'=>'baoyue'))?>"+"&fee="+select_fee;
            <?php  } ?>
        });
    });
	function lookwechat(){
		$('#go_vip').show();
	}

	function playaudio(obj){
		var audio=$(obj).find('.audio')[0];
		audio.play();
	}

  function sayHello(obj){
    $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('chat',array('op'=>'zhaohu','id'=>$id)),2);?>", data: {}, dataType: 'text', type: 'post', success: function (re) {
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
      $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('chat',array('op'=>'huifu','id'=>$id)),2);?>", data: {}, dataType: 'text', type: 'post', success: function (re) {
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

function sendMsg(time,str){
	$("#chat_list").append('<li class="time">'+time+'</li>\
		<li class="me disbox-hor dis_block">\
		<div class="f_right" style="position: relative;">\
			<img src="<?php  echo tomedia($avatar)?>"/>\
		</div>\
		<div class="disbox-flex f_right no_padding">\
			<p class="talk">'+str+'</p>\
		</div>\
		<div class="f_right"></div>\
	</li>');
}

$("#see_more_record").bind('click',function(){
	var str = $("#chat_list").find(".time").eq(0).data("id");
	$.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('chat',array('op'=>'more','id'=>$id)),2);?>", data: {lastid:str}, dataType: 'json', type: 'post', success: function (re) {
          if (re.status == 1) {
          	  $("#chat_list").prepend(re.log);
          	  Expression.replaceHtml($('#chat_list'));
          }
          else if (re.status == 2) {
          	  $("#chat_list").prepend(re.log);
          	  $("#see_more_record").hide();
          	  Expression.replaceHtml($('#chat_list'));
          }else {
              $.tips("网络问题,请稍后重试");
          }
      }, error: function () {
          $.tips("网络问题,请稍后重试");
      }
      });

});
// 选项回答
$(".selAns").bind('click',function(){
	var str = $(this).data('id');
	$.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('chat',array('op'=>'ans','wtid'=>$zhaohuid,'id'=>$id)),2);?>", data: {ansid:str}, dataType: 'json', type: 'post', success: function (re) {
          if (re.status == 1) {
              sendMsg(re.time,re.data);
			  $(".answer").addClass("hidden");
          }else {
              $.tips("网络问题,请稍后重试");
          }
      }, error: function () {
          $.tips("网络问题,请稍后重试");
      }
      });
	
});
// 发送按钮
$("#btnSendMsg").bind("click",function(){
	var str = $('#talk_area').val();
	$.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('chat',array('op'=>'ans2','id'=>$id)),2);?>", data: {str:str}, dataType: 'json', type: 'post', success: function (re) {
          if (re.status == 1) {
          	  sendMsg(re.time,str);

			  $("#send_cancel").click();
			  Expression.replaceHtml($('#chat_list'));
          }else {
              $.tips("网络问题,请稍后重试");
          }
          	<?php  if($first_chat==1) { ?>
		 	 window.location.reload();
			<?php  } ?>
      }, error: function () {
          $.tips("网络问题,请稍后重试");
      }
      });

	// ajax-----
});
$("#sendbtn").bind("click", function(){
	$(this).addClass("hidden");
	$("#send_nav").removeClass("hidden");
	$("#btn_send_msg").removeClass("hidden");
	$('textarea').val('').focus();
	window.scrollTo(0,document.body.scrollHeight);
});
$("#send_cancel").bind("click",function(){
	$("#sendbtn").removeClass("hidden");
	$("#send_nav").addClass("hidden");
	$("#btn_send_msg").addClass("hidden");
  window.scrollTo(0,document.body.scrollHeight);
});

$(function () {
	Expression.replaceHtml($('#chat_list'));
    // $('body').bind("click", '[data-express]', function () {
    //     var txt = $(this).data('express'), text = $('#talk_area');
    //     text.val(text.val() + '[' + txt + ']');
    //     var len = text.val().length;
    //     $('#provide').text(len + "/240字");
    //     text.focus();
    // });
    $("#face>li").bind("click",function(){
      var txt = $(this).data('express'), text = $('#talk_area');
        var len = text.val().length;
        $('#provide').text(len + "/240字");
        text.focus().val(text.val() + '[' + txt + ']');
    });
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</html>