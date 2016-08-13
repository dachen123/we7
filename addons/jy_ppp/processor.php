<?php
/**
 * 粉丝啪啪啪模块处理程序
 *
 * @author Michael Hu
 * @url http://bbs.9ye.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Jy_pppModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('jy_ppp_rule') . " WHERE ruleid = :id ";
		$reply = pdo_fetch($sql, array(':id'=>$rid));
		$sql = "SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid = :weid ";
		$sitem = pdo_fetch($sql, array(':weid'=>$reply['uniacid']));
		$news[] = array(
				'title' => $sitem['zhuce_text'],
				'description' => $sitem['sharedescription'],
				'picurl' => '../addons/jy_ppp/images/notice.jpg',
				'url' => $this->createMobileUrl('index', array('rid' => $rid)),
			);
		return $this->respNews($news);
	}
}