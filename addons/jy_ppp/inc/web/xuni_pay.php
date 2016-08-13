<?php
		global $_W, $_GPC;
		$weid = $_W ['uniacid'];
		checklogin ();
		$operation = ! empty ( $_GPC ['op'] ) ? $_GPC ['op'] : 'display';
		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
		if ($operation == 'display') {
			if (! empty ( $_GPC ['displayorder'] )) {
				foreach ( $_GPC ['displayorder'] as $id => $displayorder ) {
					pdo_update ( 'jy_ppp_xuni_pay', array ('displayorder' => $displayorder ), array ('id' => $id) );
				}
				message ( '虚拟充值记录更新成功！', $this->createWebUrl ( 'xuni_pay', array ('op' => 'display') ), 'success' );
			}
			$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_xuni_pay' ) . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC,id ASC" );
			include $this->template ( 'web/xuni_pay' );
		} elseif ($operation == 'post') {
			$id = intval ( $_GPC ['id'] );
			load()->func('tpl');
			if (! empty ( $id )) {
				$category = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_xuni_pay' ) . " WHERE id = '$id'" );
			} else {
				$category = array (
						'displayorder' => 0,
						'enabled' => 1
				);
			}
			if (checksubmit ( 'submit' )) {
				if (empty ( $_GPC ['catename'] )) {
					message ( '抱歉，请输入虚拟充值金额！' );
				}
				if (empty ( $_GPC ['user'] )) {
					message ( '抱歉，请输入虚拟充值用户名！' );
				}
				$type=intval($_GPC['type']);
				if(empty($type))
				{
					$type='包月服务';
				}
				else if($type==1)
				{
					if (empty($sitem['doubi']))
					{
						$sitem['doubi']='豆币';
					}
					$type=$sitem['doubi']."服务";
				}
				else if($type==2)
				{
					$type='收信宝服务';
				}
				else{
					message("您选择的充值类型有误，请返回重试！");
				}

				$name="刚刚充值了".$_GPC['catename']."元".$type."!";
				$data = array (
						'weid' => $_W ['weid'],
						'user' => $_GPC ['user'],
						'price' => $_GPC ['catename'],
						'name' => $name,
						'description' => $_GPC ['description'],
						'enabled' => intval ( $_GPC ['enabled'] ),
						'type' => $_GPC ['type'],
						'displayorder' => intval ( $_GPC ['displayorder'] ) 
				);
				if (! empty ( $id )) {
					pdo_update ( 'jy_ppp_xuni_pay', $data, array ('id' => $id ) );
				} else {
					pdo_insert ( 'jy_ppp_xuni_pay', $data );
					$id = pdo_insertid ();
				}
				message ( '更新虚拟充值记录设置成功！', $this->createWebUrl ( 'xuni_pay', array ('op' => 'display' ) ), 'success' );
			}
			include $this->template ( 'web/xuni_pay' );
		} elseif ($operation == 'delete') {
			$id = intval ( $_GPC ['id'] );
			$category = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_xuni_pay' ) . " WHERE id = '$id'" );
			if (empty ( $category )) {
				message ( '抱歉，虚拟充值记录设置不存在或是已经被删除！', $this->createWebUrl ( 'xuni_pay', array ('op' => 'display' ) ), 'error' );
			}
			pdo_delete ( 'jy_ppp_xuni_pay', array ('id' => $id ) );
			message ( '虚拟充值记录设置删除成功！', $this->createWebUrl ( 'xuni_pay', array ('op' => 'display' ) ), 'success' );
		}