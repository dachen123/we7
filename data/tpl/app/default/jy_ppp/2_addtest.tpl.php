<?php defined('IN_IA') or exit('Access Denied');?><html>
	
	<head>

		<title><?php  echo $sitem['aname'];?></title>

		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

		<meta charset="utf-8">

		<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,width=device-width" >

		<meta name="apple-mobile-web-app-capable" content="yes">

		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<meta name="format-detection" content="telephone=no">
		<link rel="stylesheet" href="../addons/jy_ppp/css/weui.min.css" type="text/css"/>

		<?php  echo register_jssdk(false);?> <!-- 注册调用微信js_api 放到其他引入的 js 之前 -->

	</head>

	<body>
		<div id="toast_loading" class="weui_loading_toast" style="z-index:99999;position:fixed;">
			<div class="weui_mask_transparent"></div>
			<div class="weui_toast">
				<div class="weui_loading">
					<div class="weui_loading_leaf weui_loading_leaf_0"></div>
					<div class="weui_loading_leaf weui_loading_leaf_1"></div>
					<div class="weui_loading_leaf weui_loading_leaf_2"></div>
					<div class="weui_loading_leaf weui_loading_leaf_3"></div>
					<div class="weui_loading_leaf weui_loading_leaf_4"></div>
					<div class="weui_loading_leaf weui_loading_leaf_5"></div>
					<div class="weui_loading_leaf weui_loading_leaf_6"></div>
					<div class="weui_loading_leaf weui_loading_leaf_7"></div>
					<div class="weui_loading_leaf weui_loading_leaf_8"></div>
					<div class="weui_loading_leaf weui_loading_leaf_9"></div>
					<div class="weui_loading_leaf weui_loading_leaf_10"></div>
					<div class="weui_loading_leaf weui_loading_leaf_11"></div>
				</div>
				<p class="weui_toast_content">正寻找您位置...</p>
			</div>
		</div>
		<script src="http://libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript">
	        wx.ready(function(){

		            wx.getLocation({

		                success: function (res) {
		                	//alert(JSON.stringify(res));

		                    // 根据坐标获取所在城市并跳转

		                    $.post("<?php  echo $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('index',array('op'=>'addtest')), 2)?>",{

		                        latitude:res.latitude,

		                        longitude:res.longitude

		                    },function( data ) {

		                        if(data==1){

		                            location.href = "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl($op_foo),2)?>";

		                        }else{

		                            alert(data);

		                        }

		                    });

		                },

		                fail: function(res2){
		                		alert(JSON.stringify(res2));
		                },


		            });

	        });

	        
	</script>
	</body>
</html>