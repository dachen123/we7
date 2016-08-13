<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li<?php  if(empty($mid)) { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('xinxi');?>">发送消息</a></li>
	<?php  if(!empty($mid) ) { ?><li class="active"><a href="<?php  echo $this->createWebUrl('xinxi',array('mid'=>$mid));?>">给用户<span>[<?php  echo $member['nicheng'];?>]</span>发送消息</a></li><?php  } ?>
</ul>

<div class="main">
	<div class="main">

			<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
			<div class="panel panel-info">
				<div class="panel-heading">
					添加消息
				</div>
				<div class="panel-body">
					<?php  if(!empty($mid)) { ?>
					<div class="form-group" >
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">收消息用户ID</label>
						<div class="col-sm-7 col-xs-12">
							<label><?php  echo $mid;?></label>
						</div>
					</div>
					<div class="form-group" >
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户姓名</label>
						<div class="col-sm-7 col-xs-12">
							<label><?php  echo $member['nicheng'];?></label>
						</div>
					</div>
					<div class="form-group" >
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户手机号</label>
						<div class="col-sm-7 col-xs-12">
							<label><?php  echo $member['mobile'];?></label>
						</div>
					</div>

					<?php  if(!empty($member['nickname'])) { ?>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户微信昵称</label>
							<div class="col-sm-7 col-xs-12">
								<label for=""><?php  echo $member['nickname'];?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户微信昵称</label>
							<div class="col-sm-7 col-xs-12">
								<img style="width:100px" src="<?php  echo $member['avatar'];?>"/>
							</div>
						</div>
					<?php  } else { ?>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户绑定状态</label>
							<div class="col-sm-7 col-xs-12">
								<span class="btn btn-info btn-sm">未绑定</span>
							</div>
						</div>
					<?php  } ?>

					<?php  } else { ?>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">请选择消息发送类型</label>
							<div class="col-sm-7 col-xs-12">
								<label for="enabled1" class="radio-inline"><input type="radio" name="send_type" value="1" id="enabled1" onclick="user_hide()" checked /> 群发</label>
			                    &nbsp;&nbsp;&nbsp;
			                    <label for="enabled2" class="radio-inline"><input type="radio" name="send_type" value="0" id="enabled2"  onclick="user_show()" /> 单用户</label>
							</div>
						</div>
						

						<div id="user" class="form-group" style="display: none">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">请输入用户的id</label>
							<div class="col-sm-7 col-xs-12">
								<input type="text" name="mid" class="form-control" placeholder="请输入用户的id" />
							</div>
						</div>
					<?php  } ?>

					<div class="form-group" id="sex">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">请选择用户性别</label>
						<div class="col-sm-7 col-xs-12">
							<select class="form-control" name="sex">
								<option value="0">不限</option>
								<option value="1">男</option>
								<option value="2">女</option>
							</select>
						</div>
					</div>

					<div class="form-group" id="type">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">请选择消息类型</label>
						<div class="col-sm-7 col-xs-12">
							<select class="form-control" name="type" onchange="type_c(this[selectedIndex].value);">
								<option value="0">文字</option>
								<option value="1">图片</option>
								<option value="2">单图文</option>
								<option value="3">多图文</option>
								<option value="4">语音</option>
								<option value="5">系统预设</option>
							</select>
						</div>
					</div>

					<div class="form-group" >
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">请选择消息内容</label>
						<div class="col-sm-7 col-xs-12">
							<select class="form-control" name="msg" id="msg">
								<option value="0">请选择消息内容</option>
								<?php  if(is_array($list)) { foreach($list as $l) { ?>
									<option value="<?php  echo $l['id'];?>"><?php  echo $l['name'];?></option>
								<?php  } } ?>
							</select>
						</div>
					</div>




					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
						<div class="col-sm-9 col-xs-12">
							<input onclick="return confirm('确认要发送消息吗？此操作不可恢复，确认吗？'); return false;" name="submit" type="submit" value="发送消息" class="btn btn-primary span3">
							<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
							<?php  if(!empty($mid)) { ?>
							<input type="hidden" name="mid" value="<?php  echo $mid;?>" />
							<?php  } ?>
						</div>
					</div>

				</div>
			</div>
			</form>
		</div>
		<script type="text/javascript">
			function user_show()
			{
				$("#user").show();
				$("#sex").hide();
			}
			function user_hide()
			{
				$("#user").hide();
				$("#sex").show();
			}
			function type_c(val)
			{
				$.post("<?php  echo $_W['siteroot'].'web/'.substr($this->createWebUrl('xinxi',array('op'=>'list')),2)?>"+"&type="+val,function(data){
                    if (data == 1) {
                    	alert("网络错误！请刷新重试！");
                    }
                    else
                    {
                        $("#msg").html(data);
                    }
                });
			}
		</script>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>