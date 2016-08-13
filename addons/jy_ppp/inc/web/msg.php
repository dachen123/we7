<?php
		global $_W, $_GPC;
		$weid = $_W ['uniacid'];
		checklogin ();
		$operation = ! empty ( $_GPC ['op'] ) ? $_GPC ['op'] : 'display';
		if ($operation == 'display') {
			if (! empty ( $_GPC ['displayorder'] )) {
				foreach ( $_GPC ['displayorder'] as $id => $displayorder ) {
					pdo_update ( 'jy_ppp_msg', array ('displayorder' => $displayorder ), array ('id' => $id) );
				}
				message ( '消息库更新成功！', $this->createWebUrl ( 'msg', array ('op' => 'display') ), 'success' );
			}
			$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_msg' ) . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC,id ASC" );

			$children = array();

	        foreach ($category as $index => $row) {
	            if (!empty($row['parentid'])) {
	                $children[$row['parentid']][] = $row;
	                unset($category[$index]);
	            }
	        }
			include $this->template ( 'web/msg' );
		} elseif ($operation == 'post') {
			$id = intval ( $_GPC ['id'] );
			$parentid = intval ( $_GPC ['parentid'] );
			if(!empty($parentid))
			{
				$parent = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_msg' ) . " WHERE id = '$parentid'" );
				if($parent['type']!=3)
				{
					message("该消息的类型不允许有下级消息！");
				}
			}
			load()->func('tpl');
			if (! empty ( $id )) {
				$category = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_msg' ) . " WHERE id = '$id'" );
			} else {
				$category = array (
						'displayorder' => 0,
						'enabled' => 1
				);
			}
			if (checksubmit ( 'submit' )) {
				if (empty ( $_GPC ['catename'] )) {
					message ( '抱歉，请输入消息库！' );
				}

				
				$data = array (
						'weid' => $_W ['weid'],
						'name' => $_GPC ['catename'],
						'type' => $type,
						'parentid' => $parentid,
						'description' => $_GPC ['description'],
						'enabled' => intval ( $_GPC ['enabled'] ),
						'displayorder' => intval ( $_GPC ['displayorder'] )
				);
				
				if(!empty($parentid))
				{
					if($parent['type']==3)
					{
						$data['picurl']=$_GPC['picurl'];
						$data['url']=$_GPC['url'];
						$data['type']=3;
					}
				}
				else
				{
					$type=intval($_GPC['type']);
					$data['type']=$type;
					if($type==1)
					{
						$data['media_id']=$_GPC['media_id'];
					}
					if( $type==2)
					{
						$data['picurl']=$_GPC['picurl'];
						$data['url']=$_GPC['url'];
					}
					if($type==4)
					{
						$data['media']=$_GPC['media'];
					}
				}
				if (! empty ( $id )) {
					if(empty($parentid) && $type!=3)
					{
						pdo_delete('jy_ppp_msg',array('parentid'=>$id));
					}
					pdo_update ( 'jy_ppp_msg', $data, array ('id' => $id ) );
				} else {
					pdo_insert ( 'jy_ppp_msg', $data );
					$id = pdo_insertid ();
				}
				message ( '更新消息库设置成功！', $this->createWebUrl ( 'msg', array ('op' => 'display' ) ), 'success' );
			}
			include $this->template ( 'web/msg' );
		} elseif ($operation == 'delete') {
			$id = intval ( $_GPC ['id'] );
			$category = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_msg' ) . " WHERE id = '$id'" );
			if (empty ( $category )) {
				message ( '抱歉，消息库设置不存在或是已经被删除！', $this->createWebUrl ( 'msg', array ('op' => 'display' ) ), 'error' );
			}
			pdo_delete ( 'jy_ppp_msg', array ('id' => $id ) );
			message ( '消息库设置删除成功！', $this->createWebUrl ( 'msg', array ('op' => 'display' ) ), 'success' );
		}
