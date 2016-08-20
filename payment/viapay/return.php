<?php
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
require '../../app/common/bootstrap.app.inc.php';
//include '/home/ijiazixun/chromephp/ChromePhp.php';
load()->app('common');
load()->app('template');
global $_W;
$tid = $_GET['channelOrderId'];
$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `plid`=:plid';
$pars = array();
$pars[':plid'] = $tid;
$log = pdo_fetch($sql, $pars);

WeUtility::logging('pay-viachat', var_export($_GET, true));
function curPageURL() 
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") 
    {
        $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
//if(!empty($log) && $log['status'] == '0' && (($_GET['fee'] / 100) == $log['card_fee'])) {
//if(!empty($log) && $log['status'] == '0' ) {
if($_GET['result'] == 1){
	message('交易失败，请重试!');
}
//$_W['siteroot'] = 'http://www.xyyhqhs8520.com/';
//		echo json_encode($_W);
//		die;
if(!empty($log)) {
	//$log['tag'] = iunserializer($log['tag']);
	//$log['tag']['transaction_id'] = $get['transaction_id'];
	//$log['uid'] = $log['tag']['uid'];
	//$log['transaction_id'] = $get['transaction_id'];
		//echo json_encode($_W);
		//die;
	//if(empty($_GET['i'])){
	//	//echo "<script>
	//	//	window.location.href += '&i=" . $log['uniacid'] . "';
	//	//     </script>";	
	//	//echo curPageURL();
	//	//die;
	//	//header('location:'. curPageURL().'&i='.$log['uniacid']); 
	//	header('location:'. $_SERVER["REQUEST_URI"].'&i='.$log['uniacid']); 
	//	exit();
	//}
	$record = array();
	$record['status'] = '1';
	//$record['tag'] = iserializer($log['tag']);
	pdo_update('core_paylog', $record, array('plid' => $log['plid']));
	if($log['is_usecard'] == 1 && $log['card_type'] == 1 &&  !empty($log['encrypt_code']) && $log['acid']) {
		load()->classs('coupon');
		$acc = new coupon($log['acid']);
		$codearr['encrypt_code'] = $log['encrypt_code'];
		$codearr['module'] = $log['module'];
		$codearr['card_id'] = $log['card_id'];
		$acc->PayConsumeCode($codearr);
	}

	if($log['is_usecard'] == 1 && $log['card_type'] == 2) {
		$log['card_id'] = intval($log['card_id']);
		pdo_update('activity_coupon_record', array('status' => '2', 'usetime' => time(), 'usemodule' => $log['module']), array('uniacid' => $_W['uniacid'], 'recid' => $log['card_id'], 'status' => '1'));
	}
	//static $file;
	//$classname = ucfirst($log['module']) . 'ModuleSite';
	//if(!class_exists($classname)) {
	//	$file = IA_ROOT . "/addons/{$log['module']}/site.php";
	//	if(!is_file($file)) {
	//		$file = IA_ROOT . "/framework/builtin/{$log['module']}/site.php";
	//	}
	//	if(!is_file($file)) {
	//		trigger_error('Module Definition File Not Found', E_USER_WARNING);
	//		return null;
	//	}
	//	require $file;
	//}
	//if(!class_exists($classname)) {
	//	trigger_error('Module Definition Class Not Found', E_USER_WARNING);
	//	return null;
	//}
	//$site = new $classname();

	//$site = WeUtility::createModuleSite($log['module']);
	////ChromePhp::log($_W['uniacid']);
	//if(!is_error($site)) {
	//	$method = 'payResult';
	//	if (method_exists($site, $method)) {
	//		$ret = array();
	//		$ret['weid'] = $log['weid'];
	//		$ret['uniacid'] = $log['uniacid'];
	//		$ret['acid'] = $log['acid'];
	//		$ret['result'] = 'success';
	//		$ret['type'] = $log['type'];
	//		$ret['from'] = 'return';
	//		$ret['tid'] = $log['tid'];
	//		$ret['uniontid'] = $log['uniontid'];
	//		$ret['user'] = empty($_GET['openid']) ? $log['openid'] : $_GET['openid'];
	//		$ret['fee'] = $log['fee'];
	//		$ret['tag'] = $log['tag'];
	//		$ret['is_usecard'] = $log['is_usecard'];
	//		$ret['card_type'] = $log['card_type'];
	//		$ret['card_fee'] = $log['card_fee'];
	//		$ret['card_id'] = $log['card_id'];
	//		//if(!empty($get['time_end'])) {
	//		//	$ret['paytime'] = strtotime($get['time_end']);
	//		//}
	//		$site->$method($ret);
	//	}
	//}
			$ret = array();
			$ret['weid'] = $log['weid'];
			$ret['uniacid'] = $log['uniacid'];
			$ret['acid'] = $log['acid'];
			$ret['result'] = 'success';
			$ret['type'] = $log['type'];
			$ret['from'] = 'return';
			$ret['tid'] = $log['tid'];
			$ret['uniontid'] = $log['uniontid'];
			$ret['user'] = empty($_GET['openid']) ? $log['openid'] : $_GET['openid'];
			$ret['fee'] = $log['fee'];
			$ret['tag'] = $log['tag'];
			$ret['is_usecard'] = $log['is_usecard'];
			$ret['card_type'] = $log['card_type'];
			$ret['card_fee'] = $log['card_fee'];
			$ret['card_id'] = $log['card_id'];
			$ret_str = base64_encode(json_encode($ret));
		//header('location:http://www.xyyhqhs8520.com/app/index.php?i='.$log['uniacid'].'&c=entry&do=viapay&m='.$log['module'].'&ps='.$ret_str); 
		header('location:http://'.$_SERVER['HTTP_HOST'].'/app/index.php?i='.$log['uniacid'].'&c=entry&do=viapay&m='.$log['module'].'&ps='.$ret_str); 
		exit();

}else{
     message('return');
//     ChromePhp::log('hhhhhh');
}

?>
