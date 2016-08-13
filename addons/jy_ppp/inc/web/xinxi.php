<?php

//信息
		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		

		$op=$_GPC['op'];
		if($op=='list')
		{
			$type=intval($_GPC['type']);
			$html='<option value="0">请选择消息内容</option>';
			if($type>=6)
			{
				echo 1;
				exit;
			}
			if($type==5)
			{
				$html.='<option value="1">未读消息列表</option>';
			}
			else
			{
				$list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_msg')." WHERE weid=".$weid." AND type=".$type." AND enabled=1 AND parentid=0 ");
				foreach ($list as $key => $value) {
					$html.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
				}
			}
			echo $html;
			exit;
		}

		checklogin();

		$op=$_GPC['op'];
		load()->func('tpl');

		$mid=intval($_GPC['mid']);
		if(!empty($mid))
		{
			$member=pdo_fetch("SELECT a.*,b.avatar,b.nickname FROM ".tablename('jy_ppp_member')." as a left join ".tablename('mc_members')." as b on a.uid=b.uid WHERE a.weid=".$weid." AND a.id=".$mid);
			if(empty($member))
			{
				message("该用户已不存在！");
			}
			else
			{
				if($member['status']!=1)
				{
					message("该用户已经被封号了！");
				}
				else
				{
					if($member['type']!=1)
					{
						message("该用户不是微信账户，不可以发送客服消息！");
					}
				}
			}
		}

		$list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_msg')." WHERE weid=".$weid." AND type=0 AND enabled=1 AND parentid=0 ");



		if(checksubmit()) {
			$send_type=intval($_GPC['send_type']);
			$sex=intval($_GPC['sex']);
			if($sex==1)
			{
				$sex=" AND sex=1 ";
			}
			if($sex==2)
			{
				$sex=" AND sex=2 ";
			}
			if(empty($sex))
			{
				$sex='';
			}
			if(empty($send_type))
			{
				if(empty($mid))
				{
					message("未选择对应的用户id！");
				}
			}
			elseif ($send_type==1) {
			}
			else
			{
				message("您选择的消息发送类型不正确！");
			}

			$type=intval($_GPC['type']);
			if($type>=6)
			{
				message("您选择的消息类型不正确！");
			}
			else
			{
				$msg=intval($_GPC['msg']);
				if(empty($msg))
				{
					message("请选择消息内容！");
				}
				if($type==5)
				{
					if($msg==1)
					{
						load()->func('communication');
						$access_token = WeAccount::token();

						if(empty($mid))
						{
							$yh_list=pdo_fetchall("SELECT id,from_user FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=1 AND status=1 ".$sex);
							foreach ($yh_list as $key => $value2) {
								$news_list_num=pdo_fetchcolumn(" SELECT count(id) FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$value2['id']." AND sendid>0 AND yuedu=0 ");
								$news_list=pdo_fetchall("SELECT a.sendid,a.content,b.avatar FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.sendid=b.id WHERE a.weid=".$weid." AND a.mid=".$value2['id']." AND a.sendid>0 AND a.yuedu=0 AND b.avatar <> ''  GROUP BY a.sendid order by a.createtime desc LIMIT 7");
								$news_arr=array();
								$news_arr['articles']=array();
								$title2="你收到".$news_list_num."封表白信，请点击查看";
								$title=urlencode($title2);
								$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
								$url=urlencode($url2);
								$image=$this->mailui();
								$temp_arr=array("title"=>$title,"url"=>$url,'picurl'=>$image);
								array_push($news_arr['articles'], $temp_arr);


								foreach ($news_list as $key => $value) {

									$image=tomedia($value['avatar']);
									$text2=$value['content'];
									$text=urlencode($text2);
									$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['sendid'])), 2);
									$url=urlencode($url2);
									$temp_arr=array("title"=>$text,"url"=>$url,'picurl'=>$image);
									array_push($news_arr['articles'], $temp_arr);
								}
								$data = array(
								  "touser"=>$value2['from_user'],
								  "msgtype"=>"news",
								  "news"=>$news_arr
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'type'=>'system',
										'content'=>$title2,
										'desc'=>'',
										'picurl'=>'',
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
								);
								$data3['mid']=$value2['id'];
								pdo_insert("jy_ppp_kefu",$data3);
							}
						}
						else
						{
							$news_list_num=pdo_fetchcolumn(" SELECT count(id) FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid>0 AND yuedu=0 ");
							$news_list=pdo_fetchall("SELECT a.sendid,a.content,b.avatar FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.sendid=b.id WHERE a.weid=".$weid." AND a.mid=".$mid." AND a.sendid>0 AND a.yuedu=0 AND b.avatar <> '' GROUP BY a.sendid LIMIT 7");
							$news_arr=array();
							$news_arr['articles']=array();
							$title2="你收到".$news_list_num."封表白信，请点击查看";
							$title=urlencode($title2);
							$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
							$url=urlencode($url2);
							$image=$this->mailui();
							$temp_arr=array("title"=>$title,"url"=>$url,'picurl'=>$image);
							array_push($news_arr['articles'], $temp_arr);


							foreach ($news_list as $key => $value) {

								$image=tomedia($value['avatar']);
								$text2=$value['content'];
								$text=urlencode($text2);
								$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('chat',array('id'=>$value['sendid'])), 2);
								$url=urlencode($url2);
								$temp_arr=array("title"=>$text,"url"=>$url,'picurl'=>$image);
								array_push($news_arr['articles'], $temp_arr);
							}
							$data = array(
							  "touser"=>$member['from_user'],
							  "msgtype"=>"news",
							  "news"=>$news_arr
							);
							$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
							$response = ihttp_request($url, urldecode(json_encode($data)));
							$errcode=json_decode($response['content'],true);
							$data3=array(
									'weid'=>$weid,
									'type'=>'system',
									'content'=>$title2,
									'desc'=>'',
									'picurl'=>'',
									'status'=>$errcode['errcode'],
									'createtime'=>TIMESTAMP,
							);
							$data3['mid']=$mid;
							pdo_insert("jy_ppp_kefu",$data3);
						}
						message("发送系统预设消息成功！",$this->createWebUrl('xinxi'),'success');
				}
					else
					{
						message("操作非法！！！");
					}
				}
				else
				{
					$temp=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_msg')." WHERE weid=".$weid." AND id=".$msg." AND type=".$type." AND enabled=1 ");
					if(empty($temp))
					{
						message("您选择的消息不存在,请刷新重试！");
					}
					else
					{
						if($type==0)
						{

							$text2=$temp['name'];
							$text=urlencode($text2);
							$access_token = WeAccount::token();
							
							if(empty($mid))
							{
								$yh_list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=1 AND status=1 ".$sex);
								foreach ($yh_list as $key => $value) {
									$data = array(
									  "touser"=>$value['from_user'],
									  "msgtype"=>"text",
									  "text"=>array("content"=>$text)
									);
									$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
									load()->func('communication');
									$response = ihttp_request($url, urldecode(json_encode($data)));
									$errcode=json_decode($response['content'],true);
									$data3=array(
											'weid'=>$weid,
											'type'=>'text',
											'content'=>$text2,
											'status'=>$errcode['errcode'],
											'createtime'=>TIMESTAMP,
									);
									$data3['mid']=$value['id'];
									pdo_insert("jy_ppp_kefu",$data3);
								}
							}
							else
							{
								$data = array(
								  "touser"=>$member['from_user'],
								  "msgtype"=>"text",
								  "text"=>array("content"=>$text)
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								load()->func('communication');
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'type'=>'text',
										'content'=>$text2,
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
								);
								$data3['mid']=$mid;
								pdo_insert("jy_ppp_kefu",$data3);
							}
							message("发送文本消息成功！",$this->createWebUrl('xinxi'),'success');

						}
						elseif ($type==1) {
							load()->func('communication');
							$image=$temp['media_id'];
							$access_token = WeAccount::token();

							if(empty($mid))
							{
								$yh_list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=1 AND status=1 ".$sex);
								foreach ($yh_list as $key => $value) {
									$data = array(
									  "touser"=>$value['from_user'],
									  "msgtype"=>"image",
									  "image"=>array("media_id"=>$image)
									);
									$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
									$response = ihttp_request($url, urldecode(json_encode($data)));
									$errcode=json_decode($response['content'],true);
									$data3=array(
											'weid'=>$weid,
											'type'=>'image',
											'content'=>$temp['name'],
											'picurl'=>$temp['media_id'],
											'status'=>$errcode['errcode'],
											'createtime'=>TIMESTAMP,
									);
									$data3['mid']=$value['id'];
									pdo_insert("jy_ppp_kefu",$data3);
								}
							}
							else
							{
								$data = array(
								  "touser"=>$member['from_user'],
								  "msgtype"=>"image",
								  "image"=>array("media_id"=>$image)
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'type'=>'image',
										'content'=>$temp['name'],
										'picurl'=>$temp['media_id'],
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
								);
								$data3['mid']=$mid;
								pdo_insert("jy_ppp_kefu",$data3);
							}
							message("发送图片消息成功！",$this->createWebUrl('xinxi'),'success');
						}
						elseif ($type==2) {
							load()->func('communication');
							$image=tomedia($temp['picurl']);
							$text2=$temp['name'];
							$text=urlencode($text2);
							$url2=$temp['url'];
							$url=urlencode($url2);
							$description2=$temp['description'];
							$description=urlencode($description2);
							$access_token = WeAccount::token();

							if(empty($mid))
							{
								$yh_list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=1 AND status=1 ".$sex);
								foreach ($yh_list as $key => $value) {
									$data = array(
									  "touser"=>$value['from_user'],
									  "msgtype"=>"news",
									  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$image))))
									);
									$url3 = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
									$response = ihttp_request($url3, urldecode(json_encode($data)));
									$errcode=json_decode($response['content'],true);
									$data3=array(
											'weid'=>$weid,
											'type'=>'news',
											'content'=>$temp['name'],
											'desc'=>$temp['description'],
											'picurl'=>$temp['picurl'],
											'status'=>$errcode['errcode'],
											'createtime'=>TIMESTAMP,
									);
									$data3['mid']=$value['id'];
									pdo_insert("jy_ppp_kefu",$data3);
								}
							}
							else
							{
								$data = array(
								  "touser"=>$member['from_user'],
								  "msgtype"=>"news",
								  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$image))))
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'type'=>'news',
										'content'=>$temp['name'],
										'desc'=>$temp['description'],
										'picurl'=>$temp['picurl'],
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
								);
								$data3['mid']=$mid;
								pdo_insert("jy_ppp_kefu",$data3);
							}
							message("发送单图文消息成功！",$this->createWebUrl('xinxi'),'success');
						}
						elseif ($type==3) {
							load()->func('communication');
							$news_list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_msg')." WHERE weid=".$weid." AND parentid=".$temp['id']." AND type=3 AND enabled=1 LIMIT 8");
							$news_arr=array();
							$news_arr['articles']=array();

							foreach ($news_list as $key => $value) {
								$image=tomedia($value['picurl']);
								$text2=$value['name'];
								$text=urlencode($text2);
								$url2=$value['url'];
								$url=urlencode($url2);
								$description2=$value['description'];
								$description=urlencode($description2);
								$temp_arr=array("title"=>$text,"description"=>$description,"url"=>$url,'picurl'=>$image);
								array_push($news_arr['articles'], $temp_arr);
							}
							// print_r($news_arr);
							// message("SD");
							$access_token = WeAccount::token();

							if(empty($mid))
							{
								$yh_list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=1 AND status=1 ".$sex);
								foreach ($yh_list as $key => $value) {
									$data = array(
									  "touser"=>$value['from_user'],
									  "msgtype"=>"news",
									  "news"=>$news_arr
									);
									$url3 = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
									$response = ihttp_request($url3, urldecode(json_encode($data)));
									$errcode=json_decode($response['content'],true);
									$data3=array(
											'weid'=>$weid,
											'type'=>'news',
											'content'=>$temp['name'],
											'desc'=>$temp['description'],
											'picurl'=>$temp['picurl'],
											'status'=>$errcode['errcode'],
											'createtime'=>TIMESTAMP,
									);
									$data3['mid']=$value['id'];
									pdo_insert("jy_ppp_kefu",$data3);
								}
							}
							else
							{
								$data = array(
								  "touser"=>$member['from_user'],
								  "msgtype"=>"news",
								  "news"=>$news_arr
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'type'=>'news',
										'content'=>$temp['name'],
										'desc'=>$temp['description'],
										'picurl'=>$temp['picurl'],
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
								);
								$data3['mid']=$mid;
								pdo_insert("jy_ppp_kefu",$data3);
							}
							message("发送多图文消息成功！",$this->createWebUrl('xinxi'),'success');
						}
						elseif ($type==4) {
							load()->func('communication');
							$voice=$temp['media'];
							$access_token = WeAccount::token();

							if(empty($mid))
							{
								$yh_list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=1 AND status=1 ".$sex);
								foreach ($yh_list as $key => $value) {
									$data = array(
									  "touser"=>$value['from_user'],
									  "msgtype"=>"voice",
									  "voice"=>array("media_id"=>$voice)
									);
									$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
									$response = ihttp_request($url, urldecode(json_encode($data)));
									$errcode=json_decode($response['content'],true);
									$data3=array(
											'weid'=>$weid,
											'type'=>'voice',
											'content'=>$temp['name'],
											'picurl'=>$temp['media_id'],
											'status'=>$errcode['errcode'],
											'createtime'=>TIMESTAMP,
									);
									$data3['mid']=$value['id'];
									pdo_insert("jy_ppp_kefu",$data3);
								}
							}
							else
							{
								$data = array(
								  "touser"=>$member['from_user'],
								  "msgtype"=>"voice",
								  "voice"=>array("media_id"=>$voice)
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'type'=>'voice',
										'content'=>$temp['name'],
										'picurl'=>$temp['media_id'],
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
								);
								$data3['mid']=$mid;
								pdo_insert("jy_ppp_kefu",$data3);
							}
							message("发送音频消息成功！",$this->createWebUrl('xinxi'),'success');
						}
					}
				}
			}

		}

		include $this->template('web/xinxi');