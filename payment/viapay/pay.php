<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
require '../../app/common/bootstrap.app.inc.php';
load()->app('common');
load()->app('template');
load()->func('logging');

$sl = $_GPC['ps'];
$params = @json_decode(base64_decode($sl), true);
/*请求完成才执行*/
// if($_GPC['done'] == '1') {
// 	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';
// 	$pars = array();
// 	$pars[':plid'] = $params['tid'];
// 	$log = pdo_fetch($sql, $pars);
// 	if(!empty($log) && !empty($log['status'])) {
// 		if (!empty($log['tag'])) {
// 			$tag = iunserializer($log['tag']);
// 			$log['uid'] = $tag['uid'];
// 		}
// 		$site = WeUtility::createModuleSite($log['module']);
// 		if(!is_error($site)) {
// 			$method = 'payResult';
// 			if (method_exists($site, $method)) {
// 				$ret = array();
// 				$ret['weid'] = $log['uniacid'];
// 				$ret['uniacid'] = $log['uniacid'];
// 				$ret['result'] = 'success';
// 				$ret['type'] = $log['type'];
// 				$ret['from'] = 'return';
// 				$ret['tid'] = $log['tid'];
// 				$ret['uniontid'] = $log['uniontid'];
// 				$ret['user'] = $log['openid'];
// 				$ret['fee'] = $log['fee'];
// 				$ret['tag'] = $tag;
// 				$ret['is_usecard'] = $log['is_usecard'];
// 				$ret['card_type'] = $log['card_type'];
// 				$ret['card_fee'] = $log['card_fee'];
// 				$ret['card_id'] = $log['card_id'];
// 				exit($site->$method($ret));
// 			}
// 		}
// 	}
// }

$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';
$log = pdo_fetch($sql, array(':plid' => $params['tid']));
if(!empty($log) && $log['status'] != '0') {
	exit('这个订单已经支付成功, 不需要重复支付.');
}
/*$auth = sha1($sl . $log['uniacid'] . $_W['config']['setting']['authkey']);
if($auth != $_GPC['auth']) {
	exit('参数传输错误.');
}*/
/*
select * from ims_qrcode_stat where openid='o1yOoxPj6EykkgflAMsKISP4rRtI' and uniacid=3 order by createtime desc limit 1;
*/
load()->model('payment');
$_W['uniacid'] = intval($log['uniacid']);
$_W['openid'] = intval($log['openid']);
$setting = uni_setting($_W['uniacid'], array('payment'));
if(!is_array($setting['payment'])) {
//	exit('没有设定支付参数.');
}
// $wechat = $setting['payment']['wechat'];
// $sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
// $row = pdo_fetch($sql, array(':acid' => $wechat['account']));
// $wechat['appid'] = $row['key'];
// $wechat['secret'] = $row['secret'];
// $params = array(
// 	'tid' => $log['tid'],
// 	'fee' => $log['card_fee'],
// 	'user' => $log['openid'],
// 	'title' => urldecode($params['title']),
// 	'uniontid' => $log['uniontid'],
// );
// $wOpt = wechat_build($params, $wechat);
// if (is_error($wOpt)) {
// 	if ($wOpt['message'] == 'invalid out_trade_no' || $wOpt['message'] == 'OUT_TRADE_NO_USED') {
// 		$id = date('YmdH');
// 		pdo_update('core_paylog', array('plid' => $id), array('plid' => $log['plid']));
// 		pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");
// 		message("抱歉，发起支付失败，系统已经修复此问题，请重新尝试支付。");
// 	}
// 	message("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。");
// 	exit;
// }
$sql_temp = 'SELECT * FROM '.tablename('qrcode_stat').' WHERE `openid`=:openid AND `uniacid`=:uniacid ORDER BY `createtime` DESC LIMIT 1';
//echo json_encode($sql_temp);
echo $log['openid'].'xxx'.$_W['uniacid'];
$qrcode_stat = pdo_fetch($sql_temp, array(':openid' => $log['openid'],':uniacid' => $_W['uniacid']));
echo json_encode($qrcode_stat);
//die;

if(empty($qrcode_stat)){
	$package_id = '18167';	
}else{
	
	$package_id = $qrcode_stat['name'];	
}
$fee = intval(floatval($params['fee']) * 100 );
$fee_1 = 1;
//$sign_str = sprintf("%s%s%s",'310dcbbf4cce62f762a2aaa148d556bd','17067',$fee_1);
$sign_str = sprintf("%s%s%s",'310dcbbf4cce62f762a2aaa148d556bd',$package_id,$fee);
//logging_run($sign_str);
echo $sign_str;
$sign = md5($sign_str);
//logging_run('哈哈');
//$req_str = "location:http://www.wosdk.cn/wosdk/wxPay/wxgzzf?packageId=17067&fee=" . $fee_1 ."&feeName=" .$params['title'] . "&feeDesp=" . $params['title'] . "&returnUrl=http://www.xyyhqhs8520.com/payment/viapay/return.php&channelOrderId=" .$params['tid'] . "&sign=" . $sign;
$req_str = "location:http://www.wosdk.cn/wosdk/wxPay/wxgzzf?packageId=".$package_id."&fee=" . $fee ."&feeName=" .$params['title'] . "&feeDesp=" . $params['title'] . "&returnUrl=http://".$_SERVER['HTTP_HOST']."/payment/viapay/return.php&channelOrderId=" .$params['tid'] . "&sign=" . $sign;
echo $req_str;
header($req_str);
exit();

?>
