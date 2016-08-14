<?php
global $_W,$_GPC;
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			echo "请用微信打开该页面！";
			exit;
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];  //微擎后台用来标识公众号的id,以绑定时的顺序为准，从1开始
			$id=$_GPC['id'];
			$from_user=$_SESSION['jy_openid'];
			if(empty($id))
			{
				$mid=$_GPC['mid'];
				$op=$_GPC['op'];
				$fee=$_GPC['fee'];

				if(empty($mid))
				{
					//自己给自己充值
					if(empty($from_user))
					{
						echo "<script>
							window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'doubi'))."';
						</script>";
					}
					else
					{
						$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
						if(empty($member_temp['nickname']) || $member_temp['uid']==0)
						{
							unset($uid);
						}
						else
						{
							$uid=$member_temp['uid'];
							unset($member_temp);
						}
						if(empty($uid))
						{
							echo "<script>
								window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'doubi'))."';
							</script>";
						}
						else
						{
							$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
							$mid=$member['id'];
						}
					}

					if(!empty($member))
					{
						$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
						if(!empty($from_user))
						{

							if(!empty($op) )
							{
								if($op=='doubi')
								{
									$log=1;
									$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE weid = ".$weid." AND log=1 ORDER BY displayorder DESC,id ASC" );
									if(empty($category))
									{
										if($fee!='30' && $fee!='50' && $fee!='100')
										{
											echo "操作非法！请返回重试!";
											exit;
										}
									}
									else
									{
										$temp= 0;
										foreach ($category as $key => $c) {
											if($fee==$c['fee'])
											{
												$temp=1;
												$doubi=$c['credit'];
												break;
											}
										}
										if(empty($temp))
										{
											echo "操作非法！请返回重试!";
											exit;
										}
									}
								}
								if($op=='baoyue')
								{
									$now=time();
									$log=2;
									$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE weid = ".$weid." AND log=2 ORDER BY displayorder DESC,id ASC" );
									if(empty($category))
									{
										if($fee!='50' && $fee!='100')
										{
											echo "操作非法！请返回重试!";
											exit;
										}
									}
									else
									{
										$temp= 0;
										foreach ($category as $key => $c) {
											if($fee==$c['fee'])
											{
												$temp=1;
												if($member['baoyue']>$now)
												{
													$baoyue=86400*$c['baoyue']+$member['baoyue'];
													$starttime=$member['baoyue'];
												}
												else
												{
													$baoyue=86400*$c['baoyue']+$now;
													$starttime=$now;
												}
												$by_time=$c['baoyue'];
												break;
											}
										}
										if(empty($temp))
										{
											echo "操作非法！请返回重试!";
											exit;
										}
									}
								}
								if($op=='shouxin')
								{
									$now=time();
									$log=3;
									$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE weid = ".$weid." AND log=3 ORDER BY displayorder DESC,id ASC" );
									if(empty($category))
									{
										if($fee!='10' && $fee!='30' && $fee!='50' && $fee!='100')
										{
											echo "操作非法！请返回重试!";
											exit;
										}
									}
									else
									{
										$temp= 0;
										foreach ($category as $key => $c) {
											if($fee==$c['fee'])
											{
												$temp=1;
												if($member['shouxin']>$now)
												{
													$shouxin=86400*$c['shouxin']+$member['shouxin'];
													$starttime=$member['shouxin'];
												}
												else
												{
													$shouxin=86400*$c['shouxin']+$now;
													$starttime=$now;
												}
												$by_time=$c['shouxin'];
												break;
											}
										}
										if(empty($temp))
										{
											echo "操作非法！请返回重试!";
											exit;
										}
									}
								}
								$data=array(
										'weid'=>$weid,
										'mid'=>$mid,
										'fee'=>$fee,
										'log'=>$log,
										'status'=>0,
										'createtime'=>TIMESTAMP,
									);
								if(empty($fee))
								{
									$data['status']=1;
									$data['paytime']=TIMESTAMP;
									pdo_insert("jy_ppp_pay_log",$data);
									$id=pdo_insertid();
									if($op=='doubi')
									{
										$data2=array(
											'weid'=>$weid,
											'mid'=>$mid,
											'type'=>'add',
											'log'=>4,
											'logid'=>$id,
											'credit'=>$doubi,
											'createtime'=>TIMESTAMP,
										);
										pdo_insert("jy_ppp_credit_log",$data2);
										pdo_update('jy_ppp_member',array('credit'=>$member['credit']+$data2['credit']),array('id'=>$mid));
										if(empty($sitem['unit']))
										{
											$sitem['unit']='豆';
										}

										if(empty($sitem['tel']))
										{
											$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值".$data2['credit'].$sitem['unit']."，已经打到您的账号上，欢迎您再次使用!";
											$xinxi=$text2;
										}
										else
										{
											$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值".$data2['credit'].$sitem['unit']."，已经打到您的账号上，欢迎您再次使用，如有问题，请拨打充值热线:<a href='tel:".$sitem['tel']."'>".$sitem['tel']."</a>";
											$xinxi="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值".$data2['credit'].$sitem['unit']."，已经打到您的账号上，欢迎您再次使用，如有问题，请拨打充值热线:".$sitem['tel'];
										}
										$text=urlencode($text2);
										if(!empty($from_user))
										{
											$access_token = WeAccount::token();
											$data = array(
											  "touser"=>$from_user,
											  "msgtype"=>"text",
											  "text"=>array("content"=>$text)
											);
											$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
											load()->func('communication');
											$response = ihttp_request($url, urldecode(json_encode($data)));
											$errcode=json_decode($response['content'],true);
											$data3=array(
													'weid'=>$weid,
													'mid'=>$mid,
													'type'=>'text',
													'content'=>$text2,
													'status'=>$errcode['errcode'],
													'createtime'=>TIMESTAMP,
												);
											pdo_insert("jy_ppp_kefu",$data3);
										}

										$data4=array(
											'weid'=>$weid,
											'mid'=>$mid,
											'sendid'=>0,
											'content'=>$xinxi,
											'type'=>3,
											'yuedu'=>0,
											'createtime'=>TIMESTAMP,
										);
										pdo_insert("jy_ppp_xinxi",$data4);
									}
									if($op=='baoyue')
									{
										$data2=array(
											'weid'=>$weid,
											'mid'=>$mid,
											'logid'=>$id,
											'createtime'=>TIMESTAMP,
										);
										$data2['starttime']=$starttime;
										$data2['endtime']=$baoyue;
										pdo_insert("jy_ppp_baoyue_log",$data2);

										pdo_update('jy_ppp_member',array('baoyue'=>$baoyue),array('id'=>$mid));
										if(empty($sitem['tel']))
										{
											$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的写信包月服务，欢迎您再次使用!";
											$xinxi=$text2;
										}
										else
										{
											$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的写信包月服务，欢迎您再次使用，如有问题，请拨打充值热线:<a href='tel:".$sitem['tel']."'>".$sitem['tel']."</a>";
											$xinxi="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的写信包月服务，如有问题，请拨打充值热线:".$sitem['tel'];
										}
										$text=urlencode($text2);
										if(!empty($from_user))
										{
											$access_token = WeAccount::token();
											$data = array(
											  "touser"=>$from_user,
											  "msgtype"=>"text",
											  "text"=>array("content"=>$text)
											);
											$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
											load()->func('communication');
											$response = ihttp_request($url, urldecode(json_encode($data)));
											$errcode=json_decode($response['content'],true);
											$data3=array(
													'weid'=>$weid,
													'mid'=>$mid,
													'type'=>'text',
													'content'=>$text2,
													'status'=>$errcode['errcode'],
													'createtime'=>TIMESTAMP,
												);
											pdo_insert("jy_ppp_kefu",$data3);
										}
										$data4=array(
											'weid'=>$weid,
											'mid'=>$mid,
											'sendid'=>0,
											'content'=>$xinxi,
											'type'=>3,
											'yuedu'=>0,
											'createtime'=>TIMESTAMP,
										);
										pdo_insert("jy_ppp_xinxi",$data4);
									}
									if($op=='shouxin')
									{
										$data2=array(
											'weid'=>$weid,
											'mid'=>$mid,
											'logid'=>$id,
											'createtime'=>TIMESTAMP,
										);
										$data2['starttime']=$starttime;
										$data2['endtime']=$shouxin;
										pdo_insert("jy_ppp_shouxin_log",$data2);

										pdo_update('jy_ppp_member',array('shouxin'=>$shouxin),array('id'=>$mid));
										if(empty($sitem['tel']))
										{
											$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的收信宝服务，欢迎您再次使用!";
											$xinxi=$text2;
										}
										else
										{
											$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的收信宝服务，欢迎您再次使用，如有问题，请拨打充值热线:<a href='tel:".$sitem['tel']."'>".$sitem['tel']."</a>";
											$xinxi="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的收信宝服务，如有问题，请拨打充值热线:".$sitem['tel'];
										}
										$text=urlencode($text2);
										if(!empty($from_user))
										{
											$access_token = WeAccount::token();
											$data = array(
											  "touser"=>$from_user,
											  "msgtype"=>"text",
											  "text"=>array("content"=>$text)
											);
											$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
											load()->func('communication');
											$response = ihttp_request($url, urldecode(json_encode($data)));
											$errcode=json_decode($response['content'],true);
											$data3=array(
													'weid'=>$weid,
													'mid'=>$mid,
													'type'=>'text',
													'content'=>$text2,
													'status'=>$errcode['errcode'],
													'createtime'=>TIMESTAMP,
												);
											pdo_insert("jy_ppp_kefu",$data3);
										}
										$data4=array(
											'weid'=>$weid,
											'mid'=>$mid,
											'sendid'=>0,
											'content'=>$xinxi,
											'type'=>3,
											'yuedu'=>0,
											'createtime'=>TIMESTAMP,
										);
										pdo_insert("jy_ppp_xinxi",$data4);
									}
									echo "<script>
											window.location.href = '".$this->createMobileUrl('geren')."';
										</script>";
								}
								else
								{
									pdo_insert("jy_ppp_pay_log",$data);
									$id=pdo_insertid();

									$tid=$data['createtime'].$id;

									$params['tid'] = $tid;
									$params['fee'] = $fee;
									$params['user'] = $from_user;
									$params['title'] = "支付";
									$params['ordersn'] = random(8);
									$params['virtual'] =  true;

									if(!$this->inMobile) {
										message('支付功能只能在手机上使用');
									}

									$params['module'] = $this->module['name'];
									$pars = array();
									$pars[':uniacid'] = $_W['uniacid'];
									$pars[':module'] = $params['module'];
									$pars[':tid'] = $params['tid'];
									$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
									$log = pdo_fetch($sql, $pars);
									if(!empty($log) && $log['status'] == '1') {
										message('这个订单已经支付成功, 不需要重复支付.');
									}

									if (empty($log)) {
								        $log = array(
								                'uniacid' => $_W['uniacid'],
								                'acid' => $_W['acid'],
								                'openid' => $from_user,
								                'module' => $this->module['name'], //模块名称，请保证$this可用
								                'tid' => $tid,
								                'fee' => $params['fee'],
								                'card_fee' => $params['fee'],
								                'status' => '0',
								                'is_usecard' => '0',
								        );
								        pdo_insert('core_paylog', $log);
									}

									$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));

									if(!is_array($setting['payment'])) {
										// message('没有有效的支付方式, 请联系网站管理员.');
									}
									$params=base64_encode(json_encode($params));
									echo "<script>
											window.location.href = '".url('mc/cash/wechat')."&params=".$params."';
										</script>";
								}

							}
							else
							{
								echo "操作错误！请返回重试~";
								exit;
							}
						}
						else{
							echo "用户授权错误，请返回重试！";
						}
					}
					else
					{

						echo "<script>
								window.location.href = '".$this->createMobileUrl('zhuce')."';
							</script>";
					}
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id='".$mid."'");
					//要别人给自己充值
					if(!empty($op))
					{
						if($op=='doubi')
						{
							$log=1;
							$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE weid = ".$weid." AND log=1 ORDER BY displayorder DESC,id ASC" );
							if(empty($category))
							{
								if($fee!='30' && $fee!='50' && $fee!='100')
								{
									echo "操作非法！请返回重试!";
									exit;
								}
							}
							else
							{
								$temp= 0;
								foreach ($category as $key => $c) {
									if($fee==$c['fee'])
									{
										$temp=1;
										$doubi=$c['credit'];
										break;
									}
								}
								if(empty($temp))
								{
									echo "操作非法！请返回重试!";
									exit;
								}
							}
						}
						if($op=='baoyue')
						{
							$now=time();
							$log=2;
							$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE weid = ".$weid." AND log=2 ORDER BY displayorder DESC,id ASC" );
							if(empty($category))
							{
								if($fee!='50' && $fee!='100')
								{
									echo "操作非法！请返回重试!";
									exit;
								}
							}
							else
							{
								$temp= 0;
								foreach ($category as $key => $c) {
									if($fee==$c['fee'])
									{
										$temp=1;
										if($member['baoyue']>$now)
										{
											$baoyue=86400*$c['baoyue']+$member['baoyue'];
											$starttime=$member['baoyue'];
										}
										else
										{
											$baoyue=86400*$c['baoyue']+$now;
											$starttime=$now;
										}
										$by_time=$c['baoyue'];
										break;
									}
								}
								if(empty($temp))
								{
									echo "操作非法！请返回重试!";
									exit;
								}
							}
						}
						if($op=='shouxin')
						{
							$now=time();
							$log=3;
							$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE weid = ".$weid." AND log=3 ORDER BY displayorder DESC,id ASC" );
							if(empty($category))
							{
								if($fee!='10' && $fee!='30' && $fee!='50' && $fee!='100')
								{
									echo "操作非法！请返回重试!";
									exit;
								}
							}
							else
							{
								$temp= 0;
								foreach ($category as $key => $c) {
									if($fee==$c['fee'])
									{
										$temp=1;
										if($member['shouxin']>$now)
										{
											$shouxin=86400*$c['shouxin']+$member['shouxin'];
											$starttime=$member['shouxin'];
										}
										else
										{
											$shouxin=86400*$c['shouxin']+$now;
											$starttime=$now;
										}
										$by_time=$c['shouxin'];
										break;
									}
								}
								if(empty($temp))
								{
									echo "操作非法！请返回重试!";
									exit;
								}
							}
						}
						$data=array(
								'weid'=>$weid,
								'mid'=>$mid,
								'fee'=>$fee,
								'log'=>$log,
								'status'=>0,
								'createtime'=>TIMESTAMP,
							);

						if(empty($fee))
						{
							$data['status']=1;
							$data['paytime']=TIMESTAMP;
							pdo_insert("jy_ppp_pay_log",$data);
							$id=pdo_insertid();
							if($op=='doubi')
							{
								$data2=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'type'=>'add',
									'log'=>4,
									'logid'=>$id,
									'credit'=>$doubi,
									'createtime'=>TIMESTAMP,
								);
								pdo_insert("jy_ppp_credit_log",$data2);
								pdo_update('jy_ppp_member',array('credit'=>$member['credit']+$data2['credit']),array('id'=>$mid));
								if(empty($sitem['unit']))
								{
									$sitem['unit']='豆';
								}

								if(empty($sitem['tel']))
								{
									$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值".$data2['credit'].$sitem['unit']."，已经打到您的账号上，欢迎您再次使用!";
									$xinxi=$text2;
								}
								else
								{
									$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值".$data2['credit'].$sitem['unit']."，已经打到您的账号上，欢迎您再次使用，如有问题，请拨打充值热线:<a href='tel:".$sitem['tel']."'>".$sitem['tel']."</a>";
									$xinxi="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值".$data2['credit'].$sitem['unit']."，已经打到您的账号上，欢迎您再次使用，如有问题，请拨打充值热线:".$sitem['tel'];
								}
								$text=urlencode($text2);

								$data4=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'sendid'=>0,
									'content'=>$xinxi,
									'type'=>3,
									'yuedu'=>0,
									'createtime'=>TIMESTAMP,
								);
								pdo_insert("jy_ppp_xinxi",$data4);
							}
							if($op=='baoyue')
							{
								$data2=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'logid'=>$id,
									'createtime'=>TIMESTAMP,
								);
								$data2['starttime']=$starttime;
								$data2['endtime']=$baoyue;
								pdo_insert("jy_ppp_baoyue_log",$data2);

								pdo_update('jy_ppp_member',array('baoyue'=>$baoyue),array('id'=>$mid));
								if(empty($sitem['tel']))
								{
									$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的写信包月服务，欢迎您再次使用!";
									$xinxi=$text2;
								}
								else
								{
									$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的写信包月服务，欢迎您再次使用，如有问题，请拨打充值热线:<a href='tel:".$sitem['tel']."'>".$sitem['tel']."</a>";
									$xinxi="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的写信包月服务，如有问题，请拨打充值热线:".$sitem['tel'];
								}
								$text=urlencode($text2);
								$data4=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'sendid'=>0,
									'content'=>$xinxi,
									'type'=>3,
									'yuedu'=>0,
									'createtime'=>TIMESTAMP,
								);
								pdo_insert("jy_ppp_xinxi",$data4);
							}
							if($op=='shouxin')
							{
								$data2=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'logid'=>$id,
									'createtime'=>TIMESTAMP,
								);
								$data2['starttime']=$starttime;
								$data2['endtime']=$shouxin;
								pdo_insert("jy_ppp_shouxin_log",$data2);

								pdo_update('jy_ppp_member',array('shouxin'=>$shouxin),array('id'=>$mid));
								if(empty($sitem['tel']))
								{
									$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的收信宝服务，欢迎您再次使用!";
									$xinxi=$text2;
								}
								else
								{
									$text2="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的收信宝服务，欢迎您再次使用，如有问题，请拨打充值热线:<a href='tel:".$sitem['tel']."'>".$sitem['tel']."</a>";
									$xinxi="您在".date('Y-m-d G:i:s',$data2['createtime'])."的充值已经成功，本次充值获得".$by_time."天的收信宝服务，如有问题，请拨打充值热线:".$sitem['tel'];
								}
								$text=urlencode($text2);

								$data4=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'sendid'=>0,
									'content'=>$xinxi,
									'type'=>3,
									'yuedu'=>0,
									'createtime'=>TIMESTAMP,
								);
								pdo_insert("jy_ppp_xinxi",$data4);
							}
							echo "<script>
									window.location.href = '".$this->createMobileUrl('geren')."';
								</script>";
						}
						else
						{
							pdo_insert("jy_ppp_pay_log",$data);
							$id=pdo_insertid();
							if(empty($from_user))
							{
								$id=$data['createtime'].$id;
								echo "<script>
									window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'pay','id'=>$id))."';
								</script>";
							}
							else
							{
								//别人已经授权过
								$tid=$data['createtime'].$id;

								$params['tid'] = $tid;
								$params['fee'] = $fee;
								$params['user'] = $from_user;
								$params['title'] = "支付";
								$params['ordersn'] = random(8);
								$params['virtual'] =  true;

								if(!$this->inMobile) {
									message('支付功能只能在手机上使用');
								}

								$params['module'] = $this->module['name'];
								$pars = array();
								$pars[':uniacid'] = $_W['uniacid'];
								$pars[':module'] = $params['module'];
								$pars[':tid'] = $params['tid'];
								$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
								$log = pdo_fetch($sql, $pars);
								if(!empty($log) && $log['status'] == '1') {
									message('这个订单已经支付成功, 不需要重复支付.');
								}

								$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));

								if(!is_array($setting['payment'])) {
									// message('没有有效的支付方式, 请联系网站管理员.');
								}

								if (empty($log)) {
							        $log = array(
							                'uniacid' => $_W['uniacid'],
							                'acid' => $_W['acid'],
							                'openid' => $from_user,
							                'module' => $this->module['name'], //模块名称，请保证$this可用
							                'tid' => $tid,
							                'fee' => $params['fee'],
							                'card_fee' => $params['fee'],
							                'status' => '0',
							                'is_usecard' => '0',
							        );
							        pdo_insert('core_paylog', $log);
								}
								$params=base64_encode(json_encode($params));
								echo "<script>
										window.location.href = '".url('mc/cash/wechat')."&params=".$params."';
									</script>";
							}
						}

					}
					else
					{
						echo "操作错误！，请返回重试~~";
						exit;
					}
				}
			}
			else
			{
				$temp_time=substr($id, 0, 10);
				$temp_id=substr($id, 10);
				$temp=pdo_fetch("SELECT a.*,b.credit,b.from_user FROM ".tablename('jy_ppp_pay_log')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id  WHERE a.weid=".$weid." AND a.id=".$temp_id);
				if($temp_time!=$temp['createtime'])
				{
					echo "操作非法！请返回重试";
					exit;
				}
				else
				{
					if(empty($from_user) || empty($temp['log']) || empty($temp['fee']) )
					{
						echo "操作非法！请返回重试!!";
						exit;
					}
					else
					{

						$params['tid'] = $id;
						$params['fee'] = $fee;
						$params['user'] = $from_user;
						$params['title'] = "支付";
						$params['ordersn'] = random(8);
						$params['virtual'] =  true;

						if(!$this->inMobile) {
							message('支付功能只能在手机上使用');
						}

						$params['module'] = $this->module['name'];
						$pars = array();
						$pars[':uniacid'] = $_W['uniacid'];
						$pars[':module'] = $params['module'];
						$pars[':tid'] = $params['tid'];
						$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
						$log = pdo_fetch($sql, $pars);
						if(!empty($log) && $log['status'] == '1') {
							message('这个订单已经支付成功, 不需要重复支付.');
						}

						$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));

						if(!is_array($setting['payment'])) {
							// message('没有有效的支付方式, 请联系网站管理员.');
						}
						if (empty($log)) {
					        $log = array(
					                'uniacid' => $_W['uniacid'],
					                'acid' => $_W['acid'],
					                'openid' => $from_user,
					                'module' => $this->module['name'], //模块名称，请保证$this可用
					                'tid' => $tid,
					                'fee' => $params['fee'],
					                'card_fee' => $params['fee'],
					                'status' => '0',
					                'is_usecard' => '0',
					        );
					        pdo_insert('core_paylog', $log);
						}
						$params=base64_encode(json_encode($params));
						echo "<script>
								window.location.href = '".url('mc/cash/wechat')."&params=".$params."';
							</script>";
					}
				}
			}
		}
