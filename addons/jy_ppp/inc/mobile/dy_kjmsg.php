<?php
global $_W,$_GPC;

		$id=$_GPC['id'];
		$yhid=$_GPC['yhid'];
		$day=$_GPC['day'];
		if(empty($id) && empty($yhid) && empty($day))
		{
			message("操作非法！请返回重试！");
		}
		

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$dyid=$_SESSION['dyid'];
			if(!empty($dyid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND id=".$dyid);
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "请刷新页面重新授权";
				exit;
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
					echo "请刷新页面重新授权";
					exit;
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$dyid=$member['id'];
				}
			}
		}


		if(!empty($member))
		{

				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);

				if(!empty($day))
				{
					$today=strtotime('today');
					$twoday=$today-3600*24*2;
					$threeday=$today-3600*24*3;
					$sevenday=$today-3600*24*7;

					if($day==1)
					{
						$condition.=" AND createtime>".$today;
					}
					elseif ($day==3) {
						$condition.=" AND createtime<".$today." AND createtime>=".$threeday;
					}
					elseif ($day==2) {
						$condition.=" AND createtime<".$today." AND createtime>=".$twoday;
					}
					elseif ($day==6) {
						$condition.=" AND createtime<".$twoday." AND createtime>=".$sevenday;
					}
					elseif ($day==7) {
						$condition.=" AND createtime<".$threeday." AND createtime>=".$sevenday;
					}
					else
					{
						echo "参数有误！";
						exit;
					}
					$newyh=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type!=3 AND status=1 ".$condition);
					if(empty($newyh))
					{
						echo "用户数据不存在";
						exit;
					}
				}
				else
				{
					if(empty($yhid))
					{
						echo "参数有误！";
						exit;
					}
					else
					{
						$yh=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type!=3 AND status=1 AND id=".$yhid);
						if(empty($yh))
						{
							echo "用户数据不存在";
							exit;
						}
						$newyh=array();
						$newyh[]=$yh;
					}
				}
				if(!empty($id))
				{
					//用某个具体的虚拟用户发消息给某个真实用户
					$xuni_yh=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=3 AND status=1 AND id=".$id);
					if(empty($xuni_yh))
					{
						echo "虚拟用户数据不存在";
						exit;
					}
					else
					{
						$xuni_man=$xuni_woman=array();
						if($xuni_yh['sex']==2)
						{
							$xuni_man[]=array();
							$xuni_woman[]=$xuni_yh;
						}
						else
						{
							$xuni_woman[]=array();
							$xuni_man[]=$xuni_yh;
						}
					}
				}

					if(empty($sitem['kjmsg_num']))
					{
						$sitem['kjmsg_num']=10;
					}
					if(empty($sitem['kjmsg_jiange']))
					{
						$sitem['kjmsg_jiange']=60;
					}
					if(empty($sitem['kjmsg_jiange2']))
					{
						$sitem['kjmsg_jiange2']=80;
					}
					if($sitem['kjmsg_jiange']==$sitem['kjmsg_jiange2'])
					{
						$kjmsg_jiange2=$sitem['kjmsg_jiange'];
					}
					if(empty($id))
					{
						$xuni_man=pdo_fetchall("SELECT b.* FROM ".tablename('jy_ppp_xuni_member')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id  WHERE a.weid=".$weid." AND a.dyid=".$dyid." AND b.sex=1  ORDER BY b.id DESC ");
						$xuni_woman=pdo_fetchall("SELECT b.* FROM ".tablename('jy_ppp_xuni_member')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id  WHERE a.weid=".$weid." AND a.dyid=".$dyid." AND b.sex=2  ORDER BY b.id DESC ");
					}
					$hf_arr_man=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_hf')." WHERE weid=".$weid." AND enabled=1 AND sex!=1 AND parentid=0 ");
					$hf_arr_woman=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_hf')." WHERE weid=".$weid." AND enabled=1 AND sex!=2 AND parentid=0 ");
					if(empty($hf_arr_man))
					{
						$hf_arr_man=array();
						$hf_arr_man[]=array('type'=>0,'name'=>'hello,你好哈~');
						$hf_arr_man[]=array('type'=>0,'name'=>'在干嘛呐？');
						$hf_arr_man[]=array('type'=>0,'name'=>'无聊ing，聊聊天呗');
						$hf_arr_man[]=array('type'=>0,'name'=>'hey');
						$hf_arr_man[]=array('type'=>0,'name'=>'我刚刚看到你在线了哦~');
						$hf_arr_man[]=array('type'=>0,'name'=>'可以交个朋友吗?');
					}
					if(empty($hf_arr_woman))
					{
						$hf_arr_woman=array();
						$hf_arr_woman[]=array('type'=>0,'name'=>'hello,你好哈~');
						$hf_arr_woman[]=array('type'=>0,'name'=>'在干嘛呐？');
						$hf_arr_woman[]=array('type'=>0,'name'=>'无聊ing，聊聊天呗');
						$hf_arr_woman[]=array('type'=>0,'name'=>'hey');
						$hf_arr_woman[]=array('type'=>0,'name'=>'我刚刚看到你在线了哦~');
						$hf_arr_woman[]=array('type'=>0,'name'=>'可以交个朋友吗?');
					}
					load()->func('communication');
					foreach ($newyh as $key => $yh) {
						$sex=$yh['sex'];
						if($sex==1)
						{
							$xuni_arr=$xuni_woman;
							$hufu_arr=$hf_arr_man;
						}
						else
						{
							$xuni_arr=$xuni_man;
							$hufu_arr=$hf_arr_woman;
						}
						$xuni_count=count($xuni_arr);
						if(empty($xuni_arr))
						{
							continue;
						}
						if($xuni_count>=$sitem['kjmsg_num'])
						{
							$xuni=array_rand($xuni_arr,$sitem['kjmsg_num']);
							if($sitem['kjmsg_num']==1)
							{
								$xuni=array('0'=>$xuni);
							}
						}
						else
						{
							if($xuni_count==1)
							{
								$xuni=array();
								$xuni[]=0;
							}
							else
							{
								$xuni=array_rand($xuni_arr,$xuni_count);
							}
							
						}
						$sendnum=1;
						$sendtime=time();
						foreach ($xuni as $key2 => $value) {
							//print_r($xuni_arr[$value]);
							$value=$xuni_arr[$value];
							$hufu=array_rand($hufu_arr);
							$hufu=$hufu_arr[$hufu];
							if($hufu['type']==3)
							{
								$hufu_zh=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_hf')." WHERE weid=".$weid." AND parentid=".$hufu['id']." AND enabled=1 ORDER BY displayorder DESC,id ASC");
								if(empty($hufu_zh))
								{
									continue;
								}
								else
								{
									foreach ($hufu_zh as $key3 => $hufu) {
										$data=array(
											'weid'=>$weid,
											'mid'=>$yh['id'],
											'sendid'=>$value['id'],
											'type'=>2,
											'yuedu'=>0,
										);
										$data['createtime']=$sendtime;
										if($hufu['type']==0)
										{
											$data['leixing']=0;
											$data['content']=$hufu['name'];

											if(empty($data['content']))
											{
												$data['content']='hello!';
											}
											if($sendnum==1)
											{
												pdo_insert("jy_ppp_xinxi",$data);
												if(!empty($yh['from_user']) && $day!=6)
												{
													$text2="附近的'".$value['nicheng']."'给你发了一封表白信";
													$text=urlencode($text2);
													$desc2="点击下方【信箱】按钮查看所有来信";
													$desc=urlencode($desc2);
													$pic2=$this->mail();
													$pic=urlencode($pic2);
													$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['id'])), 2);
													$url=urlencode($url2);
													$access_token = WeAccount::token();
													$data2 = array(
													  "touser"=>$yh['from_user'],
													  "msgtype"=>"news",
													  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$pic))))
													);
													$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
													$response = ihttp_request($url, urldecode(json_encode($data2)));
													$errcode=json_decode($response['content'],true);
													$data3=array(
															'weid'=>$weid,
															'mid'=>$yh['id'],
															'type'=>'news',
															'content'=>$text2,
															'desc'=>$desc2,
															'url'=>$url2,
															'picurl'=>$pic2,
															'status'=>$errcode['errcode'],
															'createtime'=>TIMESTAMP,
														);
													pdo_insert("jy_ppp_kefu",$data3);
												}
											}
											else
											{
												pdo_insert("jy_ppp_xinxi_temp",$data);
											}
										}
										elseif ($hufu['type']==1) {
											$data['leixing']=1;
											$data['content']="您有一张新的图片消息！";
											$data['picurl']=tomedia($hufu['picurl']);
											if($sendnum==1)
											{
												pdo_insert("jy_ppp_xinxi",$data);
												if(!empty($yh['from_user']) && $day!=6)
												{
													$text2="附近的'".$value['nicheng']."'给你发了一封表白信";
													$text=urlencode($text2);
													$desc2="点击下方【信箱】按钮查看所有来信";
													$desc=urlencode($desc2);
													$pic2=$this->mail();
													$pic=urlencode($pic2);
													$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['id'])), 2);
													$url=urlencode($url2);
													$access_token = WeAccount::token();
													$data2 = array(
													  "touser"=>$yh['from_user'],
													  "msgtype"=>"news",
													  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$pic))))
													);
													$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
													$response = ihttp_request($url, urldecode(json_encode($data2)));
													$errcode=json_decode($response['content'],true);
													$data3=array(
															'weid'=>$weid,
															'mid'=>$yh['id'],
															'type'=>'news',
															'content'=>$text2,
															'desc'=>$desc2,
															'url'=>$url2,
															'picurl'=>$pic2,
															'status'=>$errcode['errcode'],
															'createtime'=>TIMESTAMP,
														);
													pdo_insert("jy_ppp_kefu",$data3);
												}
											}
											else
											{
												pdo_insert("jy_ppp_xinxi_temp",$data);
											}
										}
										elseif ($hufu['type']==2) {
											$data['leixing']=2;
											$data['content']="您有一条新的语音消息！";
											$data['media_id']=tomedia($hufu['media_id']);
											if($sendnum==1)
											{
												pdo_insert("jy_ppp_xinxi",$data);
												if(!empty($yh['from_user']) && $day!=6)
												{
													$text2="附近的'".$value['nicheng']."'给你发了一封表白信";
													$text=urlencode($text2);
													$desc2="点击下方【信箱】按钮查看所有来信";
													$desc=urlencode($desc2);
													$pic2=$this->mail();
													$pic=urlencode($pic2);
													$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['id'])), 2);
													$url=urlencode($url2);
													$access_token = WeAccount::token();
													$data2 = array(
													  "touser"=>$yh['from_user'],
													  "msgtype"=>"news",
													  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$pic))))
													);
													$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
													$response = ihttp_request($url, urldecode(json_encode($data2)));
													$errcode=json_decode($response['content'],true);
													$data3=array(
															'weid'=>$weid,
															'mid'=>$yh['id'],
															'type'=>'news',
															'content'=>$text2,
															'desc'=>$desc2,
															'url'=>$url2,
															'picurl'=>$pic2,
															'status'=>$errcode['errcode'],
															'createtime'=>TIMESTAMP,
														);
													pdo_insert("jy_ppp_kefu",$data3);
												}
											}
											else
											{
												pdo_insert("jy_ppp_xinxi_temp",$data);
											}
										}
										$sendnum++;
										if(empty($kjmsg_jiange2))
										{
											$kjmsg_jiange=mt_rand($sitem['kjmsg_jiange'],$sitem['kjmsg_jiange2']);
											$sendtime+=$kjmsg_jiange;
										}
										else
										{
											$sendtime+=$kjmsg_jiange2;
										}
									}
								}
							}
							else
							{

								$data=array(
											'weid'=>$weid,
											'mid'=>$yh['id'],
											'sendid'=>$value['id'],
											'type'=>2,
											'yuedu'=>0,
										);
								$data['createtime']=$sendtime;
								if($hufu['type']==0)
								{
									$data['leixing']=0;
									$data['content']=$hufu['name'];

									if(empty($data['content']))
									{
										$data['content']='hello!';
									}
									if($sendnum==1)
									{
										pdo_insert("jy_ppp_xinxi",$data);
										if(!empty($yh['from_user']) && $day!=6)
										{
											$text2="附近的'".$value['nicheng']."'给你发了一封表白信";
											$text=urlencode($text2);
											$desc2="点击下方【信箱】按钮查看所有来信";
											$desc=urlencode($desc2);
											$pic2=$this->mail();
											$pic=urlencode($pic2);
											$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['id'])), 2);
											$url=urlencode($url2);
											$access_token = WeAccount::token();
											$data2 = array(
											  "touser"=>$yh['from_user'],
											  "msgtype"=>"news",
											  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$pic))))
											);
											$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
											$response = ihttp_request($url, urldecode(json_encode($data2)));
											$errcode=json_decode($response['content'],true);
											$data3=array(
													'weid'=>$weid,
													'mid'=>$yh['id'],
													'type'=>'news',
													'content'=>$text2,
													'desc'=>$desc2,
													'url'=>$url2,
													'picurl'=>$pic2,
													'status'=>$errcode['errcode'],
													'createtime'=>TIMESTAMP,
												);
											pdo_insert("jy_ppp_kefu",$data3);
										}
									}
									else
									{
										pdo_insert("jy_ppp_xinxi_temp",$data);
									}
								}
								elseif ($hufu['type']==1) {
									$data['leixing']=1;
									$data['content']="您有一张新的图片消息！";
									$data['picurl']=tomedia($hufu['picurl']);
									if($sendnum==1)
									{
										pdo_insert("jy_ppp_xinxi",$data);
										if(!empty($yh['from_user']) && $day!=6)
										{
											$text2="附近的'".$value['nicheng']."'给你发了一封表白信";
											$text=urlencode($text2);
											$desc2="点击下方【信箱】按钮查看所有来信";
											$desc=urlencode($desc2);
											$pic2=$this->mail();
											$pic=urlencode($pic2);
											$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['id'])), 2);
											$url=urlencode($url2);
											$access_token = WeAccount::token();
											$data2 = array(
											  "touser"=>$yh['from_user'],
											  "msgtype"=>"news",
											  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$pic))))
											);
											$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
											$response = ihttp_request($url, urldecode(json_encode($data2)));
											$errcode=json_decode($response['content'],true);
											$data3=array(
													'weid'=>$weid,
													'mid'=>$yh['id'],
													'type'=>'news',
													'content'=>$text2,
													'desc'=>$desc2,
													'url'=>$url2,
													'picurl'=>$pic2,
													'status'=>$errcode['errcode'],
													'createtime'=>TIMESTAMP,
												);
											pdo_insert("jy_ppp_kefu",$data3);
										}
									}
									else
									{
										pdo_insert("jy_ppp_xinxi_temp",$data);
									}
								}
								elseif ($hufu['type']==2) {
									$data['leixing']=2;
									$data['content']="您有一条新的语音消息！";
									$data['media_id']=tomedia($hufu['media_id']);
									if($sendnum==1)
									{
										pdo_insert("jy_ppp_xinxi",$data);
										if(!empty($yh['from_user']) && $day!=6)
										{
											$text2="附近的'".$value['nicheng']."'给你发了一封表白信";
											$text=urlencode($text2);
											$desc2="点击下方【信箱】按钮查看所有来信";
											$desc=urlencode($desc2);
											$pic2=$this->mail();
											$pic=urlencode($pic2);
											$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['id'])), 2);
											$url=urlencode($url2);
											$access_token = WeAccount::token();
											$data2 = array(
											  "touser"=>$yh['from_user'],
											  "msgtype"=>"news",
											  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$pic))))
											);
											$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
											$response = ihttp_request($url, urldecode(json_encode($data2)));
											$errcode=json_decode($response['content'],true);
											$data3=array(
													'weid'=>$weid,
													'mid'=>$yh['id'],
													'type'=>'news',
													'content'=>$text2,
													'desc'=>$desc2,
													'url'=>$url2,
													'picurl'=>$pic2,
													'status'=>$errcode['errcode'],
													'createtime'=>TIMESTAMP,
												);
											pdo_insert("jy_ppp_kefu",$data3);
										}
									}
									else
									{
										pdo_insert("jy_ppp_xinxi_temp",$data);
									}
								}
							}

							$sendnum++;
							if(empty($kjmsg_jiange2))
							{
								$kjmsg_jiange=mt_rand($sitem['kjmsg_jiange'],$sitem['kjmsg_jiange2']);
								$sendtime+=$kjmsg_jiange;
							}
							else
							{
								$sendtime+=$kjmsg_jiange2;
							}
						}
					}
				
				echo 1;
				exit;

		}
		else
		{

			echo "您不是店员或还未登陆";
			exit;
		}