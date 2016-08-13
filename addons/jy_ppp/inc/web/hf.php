<?php
	global $_W, $_GPC;
		$weid = $_W ['uniacid'];
		checklogin ();
		$operation = ! empty ( $_GPC ['op'] ) ? $_GPC ['op'] : 'display';
		if ($operation == 'display') {
			if (! empty ( $_GPC ['displayorder'] )) {
				foreach ( $_GPC ['displayorder'] as $id => $displayorder ) {
					pdo_update ( 'jy_ppp_hf', array ('displayorder' => $displayorder ), array ('id' => $id) );
				}
				message ( '回复库更新成功！', $this->createWebUrl ( 'hf', array ('op' => 'display') ), 'success' );
			}
			$condition = '';

			if (!empty($_GPC['keyword'])) {
				$condition .= " AND name LIKE '%{$_GPC['keyword']}%'";
			}
			$sex=intval($_GPC['sex']);
			if(!empty($sex))
			{
				if($sex==3)
				{
					$sex=0;
				}
				$condition.=" AND sex=".$sex;
			}
			$type=intval($_GPC['type']);
			if(!empty($type))
			{
				if($type==4)
				{
					$type=0;
				}
				$condition.=" AND type=".$type;
			}
			$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_hf' ) . " WHERE weid = '{$_W['weid']}' ".$condition." AND parentid=0 ORDER BY displayorder DESC,id ASC" );

			$children = array();

	        foreach ($category as $index => $row) {
	            if ($row['type']==3) {
	                $children[$row['id']] = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_hf' ) . " WHERE weid = '{$_W['weid']}'  AND parentid=".$row['id']." ORDER BY displayorder DESC,id ASC" );

	            }
	        }
			include $this->template ( 'web/hf' );
		} elseif ($operation == 'post') {
			$id = intval ( $_GPC ['id'] );
			$parentid = intval ( $_GPC ['parentid'] );
			if(!empty($parentid))
			{
				$parent = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_hf' ) . " WHERE id = '$parentid'" );
				if($parent['type']!=3)
				{

					message("该回复的类型不允许有下级回复！");
				}
			}
			load()->func('tpl');
			if (! empty ( $id )) {
				$category = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_hf' ) . " WHERE id = '$id'" );
			} else {
				$category = array (
						'displayorder' => 0,
						'enabled' => 1
				);
			}
			if (checksubmit ( 'submit' )) {
				if (empty ( $_GPC ['catename'] )) {
					message ( '抱歉，请输入回复库！' );
				}
				$temp = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_hf' ) . " WHERE weid=" . $weid . " AND name='" . $_GPC ['catename'] . "'" );
				if (! empty ( $temp ) && $id != $temp ['id']) {
					message ( '已存在该回复库！', $this->createWebUrl ( 'hf', array ('op' => 'display' ) ), 'error' );
				}
				$data = array (
						'weid' => $_W ['weid'],
						'name' => $_GPC ['catename'],
						'description' => $_GPC ['description'],
						'enabled' => intval ( $_GPC ['enabled'] ),
						'displayorder' => intval ( $_GPC ['displayorder'] )
				);
				$type=intval($_GPC['type']);

				if($type==1)
				{
					$data['picurl']=$_GPC['thumb'];
				}
				if($type==2)
				{
					$data['media_id']=$_GPC['media'];
				}
				if(!empty($type))
				{
					$data['type']=$type;
				}
				$data['parentid']=$parentid;
				if(empty($parentid))
				{
					$data['sex']=$_GPC['sex'];
				}

				if (! empty ( $id )) {
					pdo_update ( 'jy_ppp_hf', $data, array ('id' => $id ) );
				} else {
					pdo_insert ( 'jy_ppp_hf', $data );
					$id = pdo_insertid ();
				}
				message ( '更新回复库设置成功！', $this->createWebUrl ( 'hf', array ('op' => 'display' ) ), 'success' );
			}
			include $this->template ( 'web/hf' );
		} elseif ($operation == 'delete') {
			$id = intval ( $_GPC ['id'] );
			$category = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_hf' ) . " WHERE id = '$id'" );
			if (empty ( $category )) {
				message ( '抱歉，回复库设置不存在或是已经被删除！', $this->createWebUrl ( 'hf', array ('op' => 'display' ) ), 'error' );
			}
			pdo_delete ( 'jy_ppp_hf', array ('id' => $id ) );
			message ( '回复库设置删除成功！', $this->createWebUrl ( 'hf', array ('op' => 'display' ) ), 'success' );
		}