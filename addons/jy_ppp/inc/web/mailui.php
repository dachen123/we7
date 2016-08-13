<?php
global $_W, $_GPC;
		$weid = $_W ['uniacid'];
		checklogin ();
		$operation = ! empty ( $_GPC ['op'] ) ? $_GPC ['op'] : 'display';
		if ($operation == 'display') {
			if (! empty ( $_GPC ['displayorder'] )) {
				foreach ( $_GPC ['displayorder'] as $id => $displayorder ) {
					pdo_update ( 'jy_ppp_mailui', array ('displayorder' => $displayorder ), array ('id' => $id) );
				}
				message ( '信封图片更新成功！', $this->createWebUrl ( 'mailui', array ('op' => 'display') ), 'success' );
			}
			$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_mailui' ) . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC,id ASC" );
			include $this->template ( 'web/mailui' );
		} elseif ($operation == 'post') {
			$id = intval ( $_GPC ['id'] );
			load()->func('tpl');
			if (! empty ( $id )) {
				$category = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_mailui' ) . " WHERE id = '$id'" );
			} else {
				$category = array (
						'displayorder' => 0,
						'thumb' => '',
						
						'enabled' => 1
				);
			}
			if (checksubmit ( 'submit' )) {
				if (empty ( $_GPC ['catename'] )) {
					message ( '抱歉，请输入信封图片！' );
				}
				$temp = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_mailui' ) . " WHERE weid=" . $weid . " AND name='" . $_GPC ['catename'] . "'" );
				if (! empty ( $temp ) && $id != $temp ['id']) {
					message ( '已存在该信封图片！', $this->createWebUrl ( 'mailui', array ('op' => 'display' ) ), 'error' );
				}
				$data = array (
						'weid' => $_W ['weid'],
						'name' => $_GPC ['catename'],
						'description' => $_GPC ['description'],
						'enabled' => intval ( $_GPC ['enabled'] ),
						'thumb' => $_GPC ['thumb'],
						
						'displayorder' => intval ( $_GPC ['displayorder'] ) 
				);
				if (! empty ( $id )) {
					pdo_update ( 'jy_ppp_mailui', $data, array ('id' => $id ) );
				} else {
					pdo_insert ( 'jy_ppp_mailui', $data );
					$id = pdo_insertid ();
				}
				message ( '更新信封图片设置成功！', $this->createWebUrl ( 'mailui', array ('op' => 'display' ) ), 'success' );
			}
			include $this->template ( 'web/mailui' );
		} elseif ($operation == 'delete') {
			$id = intval ( $_GPC ['id'] );
			$category = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_mailui' ) . " WHERE id = '$id'" );
			if (empty ( $category )) {
				message ( '抱歉，信封图片设置不存在或是已经被删除！', $this->createWebUrl ( 'mailui', array ('op' => 'display' ) ), 'error' );
			}
			pdo_delete ( 'jy_ppp_mailui', array ('id' => $id ) );
			message ( '信封图片设置删除成功！', $this->createWebUrl ( 'mailui', array ('op' => 'display' ) ), 'success' );
		}