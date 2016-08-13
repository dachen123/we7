<?php

global $_W, $_GPC;
		$weid=$_W['uniacid'];


		$qrcode=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_rule')." WHERE uniacid=".$weid);
		foreach ($qrcode as $key => $value) {
			$qrcode[$key]['num']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type!=3 AND status=1 AND  qrcode_id=".$value['ruleid']);
		}

		$moren=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND  qrcode_id=0 AND type!=3 AND status=1 ");

		include $this->template('web/qrcode');