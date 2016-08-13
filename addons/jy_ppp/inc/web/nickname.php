<?php
global $_W, $_GPC;
		$weid = $_W ['uniacid'];
		checklogin ();
		$operation = ! empty ( $_GPC ['op'] ) ? $_GPC ['op'] : 'display';
		if ($operation == 'display') {
			if (! empty ( $_GPC ['displayorder'] )) {
				foreach ( $_GPC ['displayorder'] as $id => $displayorder ) {
					pdo_update ( 'jy_ppp_nickname', array ('displayorder' => $displayorder ), array ('id' => $id) );
				}
				message ( '昵称库管理更新成功！', $this->createWebUrl ( 'nickname', array ('op' => 'display') ), 'success' );
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_nickname' ) . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC,id ASC LIMIT " . ($pindex - 1) * $psize . ",{$psize}" );
			$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_nickname') . " WHERE weid = ".$weid);

			$pager = pagination($total, $pindex, $psize);
			include $this->template ( 'web/nickname' );
		} elseif ($operation == 'post') {
			$id = intval ( $_GPC ['id'] );
			load()->func('tpl');
			if (! empty ( $id )) {
				$category = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_nickname' ) . " WHERE id = '$id'" );
			} else {
				$category = array (
						'displayorder' => 0,
						
						
						'enabled' => 1
				);
			}
			if (checksubmit ( 'submit' )) {
				if (empty ( $_GPC ['catename'] )) {
					message ( '抱歉，请输入昵称库管理！' );
				}
				$temp = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_nickname' ) . " WHERE weid=" . $weid . " AND name='" . $_GPC ['catename'] . "'" );
				if (! empty ( $temp ) && $id != $temp ['id']) {
					message ( '已存在该昵称库管理！', $this->createWebUrl ( 'nickname', array ('op' => 'display' ) ), 'error' );
				}
				$data = array (
						'weid' => $_W ['weid'],
						'name' => $_GPC ['catename'],
						'description' => $_GPC ['description'],
						'enabled' => intval ( $_GPC ['enabled'] ),
						
						
						'displayorder' => intval ( $_GPC ['displayorder'] ) 
				);
				if (! empty ( $id )) {
					pdo_update ( 'jy_ppp_nickname', $data, array ('id' => $id ) );
				} else {
					pdo_insert ( 'jy_ppp_nickname', $data );
					$id = pdo_insertid ();
				}
				message ( '更新昵称库管理设置成功！', $this->createWebUrl ( 'nickname', array ('op' => 'display' ) ), 'success' );
			}
			include $this->template ( 'web/nickname' );
		} elseif ($operation == 'delete') {
			$id = intval ( $_GPC ['id'] );
			$category = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_nickname' ) . " WHERE id = '$id'" );
			if (empty ( $category )) {
				message ( '抱歉，昵称库管理设置不存在或是已经被删除！', $this->createWebUrl ( 'nickname', array ('op' => 'display' ) ), 'error' );
			}
			pdo_delete ( 'jy_ppp_nickname', array ('id' => $id ) );
			message ( '昵称库管理设置删除成功！', $this->createWebUrl ( 'nickname', array ('op' => 'display' ) ), 'success' );
		}