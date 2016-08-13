<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/nav', TEMPLATE_INCLUDEPATH)) : (include template('web/nav', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php  echo $this->createWebUrl('member');?>">用户管理</a></li>
</ul>
<?php  if(empty($sitem['doubi'])) { ?><?php  $sitem['doubi']='豆币'?><?php  } ?>
<?php  if(empty($sitem['unit'])) { ?><?php  $sitem['unit']='豆'?><?php  } ?>
<?php  if($op =="display") { ?>
	<div class="panel panel-info">
	<div class="panel-heading">筛选</div>

	<div class="panel-body">

		<form action="./index.php" method="get" class="form-horizontal" role="form">

			<input type="hidden" name="c" value="site" />
			<input type="hidden" name="a" value="entry" />
        	<input type="hidden" name="m" value="jy_ppp" />
        	<input type="hidden" name="do" value="member" />


			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">关键字</label>
				<div class="col-xs-12 col-sm-8 col-lg-9">
					<input class="form-control" name="keyword" id="" type="text" <?php  if(!empty($_GPC['id'])) { ?>value="<?php  echo $list[0]['nicheng'];?>"<?php  } else { ?>value="<?php  echo $_GPC['keyword'];?>"<?php  } ?> placeholder="用户昵称，手机" >
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">时间筛选</label>
				<div class="col-xs-12 col-sm-8 col-lg-9">
					<?php  echo tpl_form_field_daterange('time', $time);?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">排序</label>
				<div class="col-xs-12 col-sm-8 col-lg-9">
					<select name="sort" class="form-control">
						<option value="0">请选择排序方式</option>
						<option value="time" <?php  if($_GPC['sort']=='time') { ?>selected<?php  } ?>>注册时间排序</option>
						<option value="jifen" <?php  if($_GPC['sort']=='jifen') { ?>selected<?php  } ?>><?php  echo $sitem['doubi'];?>排序</option>
						<option value="baoyue" <?php  if($_GPC['sort']=='baoyue') { ?>selected<?php  } ?>>包月到期排序</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">排序</label>
				<div class="col-xs-12 col-sm-8 col-lg-9">
					<select name="type" class="form-control">
						<option value="0">账户类型</option>
						<option value="2" <?php  if($_GPC['type']=='2') { ?>selected<?php  } ?>>账户用户</option>
						<option value="1" <?php  if($_GPC['type']=='1') { ?>selected<?php  } ?>>微信用户</option>
						<option value="3" <?php  if($_GPC['type']=='3') { ?>selected<?php  } ?>>虚拟用户</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">性别筛选</label>
				<div class="col-xs-12 col-sm-8 col-lg-9">
					<select name="sex" class="form-control">
						<option value="0">用户性别</option>
						<option value="2" <?php  if($_GPC['sex']=='2') { ?>selected<?php  } ?>>女</option>
						<option value="1" <?php  if($_GPC['sex']=='1') { ?>selected<?php  } ?>>男</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">渠道来源</label>
				<div class="col-xs-12 col-sm-8 col-lg-9">
					<select name="qrcode_id" class="form-control">
						<option value="0">请选择渠道来源</option>
						<?php  if(is_array($qrcode)) { foreach($qrcode as $q) { ?>
						<option value="<?php  echo $q['ruleid'];?>" <?php  if($_GPC['qrcode_id']==$q['ruleid']) { ?>selected<?php  } ?>><?php  echo $q['rulename'];?></option>
						<?php  } } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
			 	<div class=" col-xs-12 col-sm-2 col-lg-2">

					<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>

				</div>
			</div>
		</form>
	</div>
	</div>
<?php  } ?>
<?php  if($op=='display') { ?>
	<div class="panel panel-default">

		<div class="panel-heading">用户详细数据  |  总数:<?php  echo $total;?></div>

		<div class="panel-body table-responsive">

		    <form action="" method="post" onsubmit="">

			<table class="table table-hover">

				<thead class="navbar-inner">

					<tr>

						<th style="width:5%;" class="row-first">选择</td>

						<th style="width:5%;">会员ID</th>

						<th style="width:8%;">会员昵称</th>

						<th style="width:7%;">性别 </th>

						<th style="width:7%;">手机账号 </th>

						<th style="width:7%;"><?php  echo $sitem['doubi'];?></th>

						<th style="width:8%;">包月到期</th>

						<th style="width:8%;">区域</th>

						<th style="width:8%;">类型</th>

						<th style="width:10%;">注册时间</th>

						<th style="width:20%;">操作</th>

					</tr>

				</thead>

				<tbody id="main">
					<?php  $time = time();?>

					<?php  if(is_array($list)) { foreach($list as $row) { ?>

					<tr>

					    <td><input type="checkbox" name="select[]" value="<?php  echo $row['id'];?>" /></td>

					    <td><?php  echo $row['id'];?></td>

						<td  style="white-space:normal;word-break:break-all"><?php  echo $row['nicheng'];?></td>

						<td><?php  if($row['sex']==1) { ?>男<?php  } else { ?>女<?php  } ?></td>

						<td  style="white-space:normal;word-break:break-all"><?php  echo $row['mobile'];?></td>

						<td  style="white-space:normal;word-break:break-all"><?php  echo $row['credit'];?> <?php  echo $sitem['unit'];?></td>

						<td  style="white-space:normal;word-break:break-all"><?php  if($row['baoyue']>$time) { ?><?php  echo date('Y年n月j日 G:i', $row['baoyue']);?><?php  } ?></td>

						<td  style="white-space:normal;word-break:break-all"><?php  echo $province[$row['province']];?></td>
						<td  style="white-space:normal;word-break:break-all"><?php  if($row['type']==0) { ?><span style="color:#FF1493">账户</span><?php  } ?><?php  if($row['type']==1) { ?><span style="color:#008B00">微信</span><?php  } ?><?php  if($row['type']==3) { ?><span style="color:#666666">虚拟</span><?php  } ?></td>

						<td  style="white-space:normal;word-break:break-all"><?php  echo date('n月j日 G:i', $row['createtime']);?></td>

						<td style="white-space:normal;word-break:break-all">

						<a href="<?php  echo $this->CreateWebUrl('xinxi',array('mid'=>$row['id']))?>"><div class="btn btn-sm btn-info">私信Ta</div></a>

						<!-- <a href="<?php  echo $this->CreateWebUrl('member',array('op'=>'edit','id'=>$row['id']))?>"><div class="btn btn-default">编辑信息</div></a> -->

						<a href="<?php  echo $this->CreateWebUrl('member',array('op'=>'mingxi','id'=>$row['id']))?>" target="_blank"><div class="btn btn-sm btn-success">用户详情</div></a>

						<?php  if($row['status']==1) { ?>
						<a href="<?php  echo $this->CreateWebUrl('member',array('op'=>'del','id'=>$row['id']))?>"><div class="btn btn-sm btn-primary">封号</div></a>
						<?php  } else { ?>
						<a href="<?php  echo $this->CreateWebUrl('member',array('op'=>'huifu','id'=>$row['id']))?>"><div class="btn btn-sm btn-default">解封</div></a>
						<?php  } ?>

						<a onclick="return confirm('此操作不可恢复，确认吗？'); return false;" href="<?php  echo $this->CreateWebUrl('member',array('op'=>'del2','id'=>$row['id']))?>"><div class="btn btn-danger btn-sm">删除</div></a>


						</td>

					</tr>

					<?php  } } ?>

				</tbody>

			</table>
			<?php  echo $pager;?>

			</form>

	    </div>

	</div>
<?php  } ?>

<?php  if($op=='mingxi') { ?>

	<div class="panel panel-default">

		<div class="panel-heading"><span style="color:red">[<?php  echo $row['nicheng'];?>]</span>用户详细数据</div>

		<div class="panel-body table-responsive">

		    <form action="" method="post" onsubmit="">

			<table class="table table-hover">

				<thead class="navbar-inner">

					<tr>

						<th style="width:5%;" class="row-first">ID</td>

						<th style="width:10%;">用户名</th>
						<th style="width:10%;">手机账号</th>
						<th style="width:10%;">昵称</th>
						<th style="width:10%;">头像</th>
						<th style="width:5%;">性别</th>
						<th style="width:10%;"><?php  echo $sitem['doubi'];?></th>
						<th style="width:10%;">包月到期</th>
						<th style="width:10%;">类型</th>
						<th style="width:10%;">注册时间</th>
						<th style="width:20%;">操作</th>

					</tr>

				</thead>

				<tbody id="main">

					<tr>

					    <td><?php  echo $row['id'];?></td>
						<td><?php  echo $row['nicheng'];?></td>
						<td><?php  echo $row['mobile'];?></td>
						<td><?php  echo $row['nicheng'];?></td>
						<td>
							<?php  if(!empty($row['avatar'])) { ?>
							<img style="width:40px" src="<?php  echo tomedia($row['avatar'])?>">
							<?php  } else { ?>
								<?php  if($row['sex']==1) { ?>
									<img style="width:40px" src="../addons/jy_ppp/images/boy.png">
								<?php  } else { ?>
									<img style="width:40px" src="../addons/jy_ppp/images/girl.png">
								<?php  } ?>
							<?php  } ?>
						</td>
						<td><?php  if($row['sex']==1) { ?>男<?php  } else { ?>女<?php  } ?></td>
						<td style="white-space:normal;word-break:break-all"><?php  echo $row['credit'];?> <?php  echo $sitem['unit'];?></td>
						<td style="white-space:normal;word-break:break-all"><?php  if($row['baoyue']>$time) { ?><?php  echo date('Y年n月j日 G:i', $row['baoyue']);?><?php  } ?></td>
						<td><?php  if($row['type']==0) { ?><span style="color:#FF1493">账户</span><?php  } ?><?php  if($row['type']==1) { ?><span style="color:#008B00">微信</span><?php  } ?><?php  if($row['type']==3) { ?><span style="color:#666666">虚拟</span><?php  } ?></td>
						<td style="white-space:normal;word-break:break-all"><?php  echo date('n月j日 G:i:s', $row['createtime']);?></td>

						<td>
						<!-- <a href="<?php  echo $this->CreateWebUrl('xinxi',array('op'=>'member','id'=>$row['id']))?>"><div class="btn btn-info">私信他</div></a>
							<a href="<?php  echo $this->CreateWebUrl('member',array('op'=>'edit','id'=>$row['id']))?>"><div class="btn btn-default">编辑信息</div></a> -->
							<?php  if($row['status']==1) { ?>
							<a href="<?php  echo $this->CreateWebUrl('member',array('op'=>'del','id'=>$row['id']))?>"><div class="btn btn-primary">封号</div></a>
							<?php  } else { ?>
							<a href="<?php  echo $this->CreateWebUrl('member',array('op'=>'huifu','id'=>$row['id']))?>"><div class="btn btn-default">解封</div></a>
							<?php  } ?>
							<a onclick="return confirm('此操作不可恢复，确认吗？'); return false;" href="<?php  echo $this->CreateWebUrl('member',array('op'=>'del2','id'=>$row['id']))?>"><div class="btn btn-danger">删除</div></a>

						</td>

					</tr>
					<tr>
						<td colspan="2" style="text-align:right">点击链接复制(用户详情页)</td>
						<td colspan="7" style="white-space:normal;word-break:break-all;overflow:hidden;position:relative;">
							<a id="needCopy" href="javascript:"><?php  echo $_W['siteroot'].'app/'.substr($this->CreateMobileUrl('detail',array('id'=>$row['id'])),2)?></a>
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:right">点击链接复制(用户聊天页)</td>
						<td colspan="7" style="white-space:normal;word-break:break-all;overflow:hidden;position:relative;">
							<a id="needCopy2" href="javascript:"><?php  echo $_W['siteroot'].'app/'.substr($this->CreateMobileUrl('chat',array('id'=>$row['id'])),2)?></a>
						</td>
						<td colspan="2"></td>
					</tr>

				</tbody>

			</table>
			

			</form>

	    </div>

	</div>

	<br><br>
	<?php  if($row['type']==3) { ?>
		<style type="text/css">
			.none{display: none}
			.combtn{padding: 5px;margin-top: 20px;display: inline-block;cursor: pointer;border-radius: 5px;font-size: 14px}
		    #codeDiv{position: fixed;left: 0;top: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,.8);text-align: center;padding-top: 10%;z-index: 100}
		    .white{color: #FFF}
		</style>
		<div class="main">
			<form action="" method="post" class="form-horizontal form"
				enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?php  echo $row['id'];?>" />
				<div class="panel panel-default">
					<div class="panel-heading">
						用户账户信息编辑
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户昵称</label>
							<div class="col-sm-7 col-xs-12">
								<input type="text" name="nicheng" class="form-control" value="<?php  echo $row['nicheng'];?>" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
							<div class="col-sm-7 col-xs-12">
								<a href="javascript:void(0)" onclick="showPsw()"><div class="btn btn-info">修改密码</div></a>
							</div>
						</div>

						<div class="form-group" id="pswDiv" style="display:none">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户密码</label>
							<div class="col-sm-7 col-xs-12">
								<input type="text" name="pwd" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户微信</label>
							<div class="col-sm-7 col-xs-12">
								<?php  if(!empty($row['avatar2'])) { ?>
								<img src="<?php  echo $row['avatar2'];?>" style="width:80px" />
								<?php  } ?>
								<label><?php  if(empty($row['nickname'])) { ?>未绑定微信<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?></label>
								<span class="btn btn-success" id="bindwechat" style="margin-left:50px">绑定微信</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户生日</label>
							<div class="col-sm-7 col-xs-12">
								<?php  echo tpl_form_field_calendar('brith', array('year'=>$year, 'month'=>$month, 'day'=>$day));?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户头像</label>
							<div class="col-sm-7 col-xs-12">
								<?php  echo tpl_form_field_image('avatar', $row['avatar']);?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户相册</label>
							<div class="col-sm-7 col-xs-12">
								<?php  echo tpl_form_field_multi_image('thumb', $thumb);?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">出生地</label>
							<div class="col-sm-2 col-xs-3">
								<select name="province" id="live_province" class="form-control" >
									<option value="0" selected="selected">请选择省份</option>
								</select>
							</div>
							<div class="col-sm-2 col-xs-3">
								<select name="city" id="live_city" class="form-control"></select>
							</div>
							<label>不选择则是默认随机为一个省,选择了省未选择市的则默认为那个省的省会</label>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户自我描述</label>
							<div class="col-sm-7 col-xs-12">
								<textarea name="beizhu" class="form-control" cols="70"><?php  echo $row['beizhu'];?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
							<div class="col-sm-9 col-xs-12">
								<input name="submit2" type="submit" value="提交" class="btn btn-primary span3"> 
								<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
							</div>
						</div>


						<div id="codeDiv" class="none"><div id="code"></div><div class="txtAC white mart20">请用微信扫一扫该二维码！</div></div>
					</div>
				</div>

			</form>
		</div>
		<script src="../addons/jy_ppp/js/area_id.js"></script>
		<script type="text/javascript">
			<?php  if(empty($row['province'])) { ?>
		        for(var i in area_array){
		            $("#live_province").append('<option value="'+ i+'">'+ area_array[i]+'</option>');
		        }
		        <?php  $row['province']=11;?>
		    <?php  } else { ?>
		        for(var i in area_array){
		            if(<?php  echo $row['province'];?>==i)
		            {
		                $("#live_province").append('<option value="'+ i+'" selected>'+ area_array[i]+'</option>');
		            }
		            else
		            {
		                $("#live_province").append('<option value="'+ i+'">'+ area_array[i]+'</option>');
		            }
		        }
		    <?php  } ?>
		    <?php  if(empty($row['city'])) { ?>
		        for(var i in sub_array[<?php  echo $row['province'];?>]){
		            $("#live_city").append('<option value="'+ i+'">'+ sub_array[<?php  echo $row['province'];?>][i]+'</option>');
		        }
		    <?php  } else { ?>
		        for(var i in sub_array[<?php  echo $row['province'];?>]){
		            if(<?php  echo $row['city'];?>==i)
		            {
		                $("#live_city").append('<option value="'+ i+'" selected>'+ sub_array[<?php  echo $row['province'];?>][i]+'</option>');
		            }
		            else
		            {
		                $("#live_city").append('<option value="'+ i+'">'+ sub_array[<?php  echo $row['province'];?>][i]+'</option>');
		            }
		        }
		    <?php  } ?>

	        $("#live_province").bind("change",function(){
		        var p=$("#live_province").eq(0).val();
		        $("#live_city").empty();
		        $("#live_city").append('<option value="0">请选择城市</option>');
		        for(var i in sub_array[p]){
		            $("#live_city").append('<option value="'+ i+'">'+ sub_array[p][i]+'</option>');
		        }
		    });
		</script>
		<br><br>
	<?php  } else { ?>
		<div class="main">
			<form action="" method="post" class="form-horizontal form"
				enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?php  echo $row['id'];?>" />
				<div class="panel panel-default">
					<div class="panel-heading">
						用户<?php  echo $sitem['doubi'];?>变更设置<span style="color:red">(谨慎操作)</span>
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
							<div class="col-sm-7 col-xs-12">
								<a href="javascript:void(0)" onclick="showcredit()"><div class="btn btn-info">修改<?php  echo $sitem['doubi'];?></div></a>
							</div>
						</div>

						<div class="form-group"  id="showcredit" style="display:none">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label" >用户<?php  echo $sitem['doubi'];?></label>
							<div class="col-sm-7 col-xs-12">
								<input type="text" name="credit" class="form-control" value="<?php  echo $row['credit'];?>" />
							</div>
						</div>


						<div class="form-group" id="submit3" style="display:none">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
							<div class="col-sm-9 col-xs-12">
								<input onclick="return confirm('此操作不可恢复，确认吗？'); return false;" name="submit3" type="submit" value="提交" class="btn btn-primary span3"> 
								<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
							</div>
						</div>

					</div>
				</div>

			</form>
		</div>
	<?php  } ?>
	

	<h4 style="text-align:center;font-weight:bold"><?php  echo $row['nicheng'];?>的详细数据</h4>

		<!--基本资料 -->

			<?php  if(count($basic)>0) { ?>
			<form action="" method="post" class="form-horizontal form"
			enctype="multipart/form-data">
				<div class="panel panel-info">
					<div class="panel-heading">
						用户基本信息
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">生日</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  echo $year;?>年 <?php  echo $month;?>月 <?php  echo $day;?>日</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">居住地</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($row['province'])) { ?>未填写<?php  } else { ?><?php  echo $province[$row['province']];?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">身高</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['height'])) { ?>未填写<?php  } else { ?><?php  echo $basic['height'];?> cm<?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">体重</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['weight'])) { ?>未填写<?php  } else { ?><?php  echo $basic['weight'];?> 斤<?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">血型</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['blood'])) { ?>未填写<?php  } else { ?><?php  echo $basic['blood'];?> <?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">学历</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['education'])) { ?>未填写<?php  } else { ?><?php  echo $basic['education'];?> <?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">职业</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['job'])) { ?>未填写<?php  } else { ?><?php  echo $basic['job'];?> <?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">收入</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['income'])) { ?>未填写<?php  } else { ?><?php  echo $basic['income'];?> <?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">婚姻状况</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['marriage'])) { ?>未填写<?php  } else { ?><?php  echo $basic['marriage'];?> <?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否有房</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($basic['house'])) { ?>未填写<?php  } else { ?><?php  echo $basic['house'];?> <?php  } ?></label>
							</div>
						</div>


					</div>
				</div>
			</form>
			<?php  } ?>

		<!--end -->

		<!--详细资料 -->

			<?php  if(count($desc)>0) { ?>
			<form action="" method="post" class="form-horizontal form"
			enctype="multipart/form-data">
				<div class="panel panel-success">
					<div class="panel-heading">
						用户详细资料
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否想要小孩</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['child'])) { ?>未填写<?php  } else { ?><?php  echo $desc['child'];?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">能否接受异地恋</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['yidi'])) { ?>未填写<?php  } else { ?><?php  echo $desc['yidi'];?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">喜欢的异性类型</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['leixin'])) { ?>未填写<?php  } else { ?><?php  echo $desc['leixin'];?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">能否接受婚前性行为</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['sex'])) { ?>未填写<?php  } else { ?><?php  echo $desc['sex'];?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否愿意与父母同住</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['fumu'])) { ?>未填写<?php  } else { ?><?php  echo $desc['fumu'];?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">魅力部位</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['meili'])) { ?>未填写<?php  } else { ?><?php  echo $desc['meili'];?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">爱好</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['aihao'])) { ?>未填写<?php  } else { ?>
									<?php  if(is_array($desc['aihao'])) { foreach($desc['aihao'] as $a) { ?>
										<?php  echo $a['aihao'];?>
									<?php  } } ?>
									<?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">特征</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($desc['tezheng'])) { ?>未填写<?php  } else { ?>
									<?php  if(is_array($desc['tezheng'])) { foreach($desc['tezheng'] as $a) { ?>
										<?php  echo $a['tezheng'];?>
									<?php  } } ?>
								<?php  } ?></label>
							</div>
						</div>

					</div>
				</div>
			</form>
			<?php  } ?>

		<!--end -->

		<!--征友条件 -->

			<?php  if(!empty($match)) { ?>
			<form action="" method="post" class="form-horizontal form"
			enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						用户征友条件
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">年龄范围</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($match['age'])) { ?>未填写<?php  } else { ?><?php  if($match['age']==0) { ?>不限<?php  } ?><?php  if($match['age']==1) { ?>18-25岁<?php  } ?><?php  if($match['age']==2) { ?>26-35岁<?php  } ?><?php  if($match['age']==3) { ?>36-45岁<?php  } ?><?php  if($match['age']==4) { ?>46-55岁<?php  } ?><?php  if($match['age']==5) { ?>55岁以上<?php  } ?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">身高范围</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($match['height'])) { ?>未填写<?php  } else { ?><?php  if($match['height']==0) { ?>不限<?php  } ?><?php  if($match['height']==1) { ?>160cm以下<?php  } ?><?php  if($match['height']==2) { ?>161-165cm<?php  } ?><?php  if($match['height']==3) { ?>166-170cm<?php  } ?><?php  if($match['height']==4) { ?>170以上<?php  } ?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">最低学历</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($match['education'])) { ?>未填写<?php  } else { ?><?php  if($match['education']==0) { ?>不限<?php  } ?><?php  if($match['education']==1) { ?>高中,中专及以上<?php  } ?><?php  if($match['education']==2) { ?>大专及以上<?php  } ?><?php  if($match['education']==3) { ?>本科及以上<?php  } ?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">收入范围</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($match['income'])) { ?>未填写<?php  } else { ?><?php  if($match['income']==0) { ?>不限<?php  } ?><?php  if($match['income']==1) { ?>2000元以上<?php  } ?><?php  if($match['income']==2) { ?>5000元以上<?php  } ?><?php  if($match['income']==3) { ?>10000元以上<?php  } ?><?php  } ?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">居住地</label>
							<div class="col-sm-7 col-xs-12">
								<label><?php  if(empty($match['province'])) { ?>未填写<?php  } else { ?><?php  echo $province[$match['province']];?><?php  } ?></label>
							</div>
						</div>

					</div>
				</div>
			</form>
			<?php  } ?>

		<!--end -->

		<!--反馈数据 -->

		<?php  if(count($item['feedback'])>0) { ?>
			<div class="panel panel-default">

				<div class="panel-heading"><span style="color:red;font-weight:bold" >用户客服反馈数据 </span>  |  总数:<?php  echo count($item['feedback'])?></div>

				<div class="panel-body table-responsive">

				    <form action="" method="post" onsubmit="">

					<table class="table table-hover">

						<thead class="navbar-inner">

							<tr>

								<th style="width:5%;" class="row-first">选择</td>

								<th style="width:10%;">昵称</th>

								<th style="width:8%;">头像</th>

								<th style="width:8%;">用户名</th>

								<th style="width:8%;">手机</th>

								<th style="width:35%;">反馈内容</th>

								<th style="width:15%;">提交时间</th>

								<th style="width:10%;">操作</th>

							</tr>

						</thead>

						<tbody id="main">

							<?php  if(is_array($item['feedback'])) { foreach($item['feedback'] as $row) { ?>

							<tr>

							    <td><input type="checkbox" name="select[]" value="<?php  echo $row['id'];?>" /></td>

								<td><?php  echo $row['nicheng'];?></td>

								<td>
									<?php  if(!empty($row['avatar'])) { ?>
									<img style="width:70px" src="<?php  echo tomedia($row['avatar'])?>">
									<?php  } else { ?>
										<?php  if($row['sex']==1) { ?>
											<img style="width:70px" src="../addons/jy_ppp/images/boy.png">
										<?php  } else { ?>
											<img style="width:70px" src="../addons/jy_ppp/images/girl.png">
										<?php  } ?>
									<?php  } ?>
								</td>

								<td><?php  echo $row['nicheng'];?></td>

								<td><?php  echo $row['mobile'];?></td>

								<td  style="white-space:normal;word-break:break-all"><?php  echo $row['feedback'];?></td> 

								<td><?php  echo date('n月j日 G:i:s', $row['createtime']);?></td>

								<td>

								<!-- <?php  if($row['status']==0) { ?>
									<a href="<?php  echo $this->CreateWebUrl('xinxi',array('op'=>'feedback','id'=>$row['mid']))?>"><div class="btn btn-info">通知Ta</div></a>
								<?php  } ?> -->
									<a href="<?php  echo $this->CreateWebUrl('feedback',array('op'=>'del','id'=>$row['id']))?>"><div class="btn btn-danger">删除</div></a>

								</td>

							</tr>

							<?php  } } ?>

						</tbody>

					</table>

					</form>

			    </div>

			</div>
		<?php  } ?>

		<!--end -->

		<!--充值日志 -->

		<?php  if(count($item['jf'])>0) { ?>
			<div class="panel panel-default">

				<div class="panel-heading"><span style="color:red;font-weight:bold" >用户充值日志数据 </span>  |  总数:<?php  echo count($item['pay'])?></div>

				<div class="panel-body table-responsive">

				    <form action="" method="post" onsubmit="">

					<table class="table table-hover">

						<thead class="navbar-inner">

							<tr>
								<th style="width:20%;">充值原因</th>

								<th style="width:10%">充值金额</th>

								<th style="width:10%;">充值状态</th>

								<th style="width:10%;">充值时间</th>

							</tr>

						</thead>

						<tbody id="main">

							<?php  if(is_array($item['pay'])) { foreach($item['pay'] as $row) { ?>

							<tr>
								<td  style="white-space:normal;word-break:break-all"><?php  if($row['log']==1) { ?>充值虚拟货币<?php  } ?><?php  if($row['log']==2) { ?>购买包月服务<?php  } ?><?php  if($row['log']==3) { ?>购买收信宝<?php  } ?></td>

								<td  style="white-space:normal;word-break:break-all"><?php  echo $row['fee'];?></td>

								<td style="white-space:normal;word-break:break-all"><?php  if($row['status']==1) { ?><span style="color:red">已付款</span><?php  $yipay+=$row['fee']?><?php  } else { ?><span style="color:green">未付款</span><?php  } ?></td>

								<td><?php  echo date('n月j日 G:i:s', $row['createtime']);?></td>

							</tr>

							<?php  } } ?>
							<tr><td></td><td></td><td></td><td>实付款:<?php  echo $yipay;?></td></tr>

						</tbody>

					</table>

					</form>

			    </div>

			</div>
		<?php  } ?>

		<!--end -->

		<!--积分日志 -->

		<?php  if(count($item['jf'])>0) { ?>
			<div class="panel panel-default">

				<div class="panel-heading"><span style="color:red;font-weight:bold" >用户<?php  echo $sitem['doubi'];?>日志数据 </span>  |  总数:<?php  echo count($item['jf'])?></div>

				<div class="panel-body table-responsive">

				    <form action="" method="post" onsubmit="">

					<table class="table table-hover">

						<thead class="navbar-inner">

							<tr>
								<th style="width:30%;">变更原因</th>

								<th style="width:10%">变更积分</th>

								<th style="width:10%;">变更时间</th>

							</tr>

						</thead>

						<tbody id="main">

							<?php  if(is_array($item['jf'])) { foreach($item['jf'] as $row) { ?>

							<tr>
								<td  style="white-space:normal;word-break:break-all"><?php  echo $row['name'];?></td>

								<td style="white-space:normal;word-break:break-all"><?php  if($row['type']=='add') { ?><span style="color:red">+<?php  echo $row['credit'];?></span><?php  } else { ?><span style="color:green">-<?php  echo $row['credit'];?></span><?php  } ?></td>

								<td><?php  echo date('n月j日 G:i:s', $row['createtime']);?></td>

							</tr>

							<?php  } } ?>
							<tr><td></td><td></td><td>总计:<?php  echo $count;?></td></tr>

						</tbody>

					</table>

					</form>

			    </div>

			</div>
		<?php  } ?>

		<!--end -->

		<!--包月日志 -->

		<?php  if(count($item['baoyue'])>0) { ?>
			<div class="panel panel-default">

				<div class="panel-heading"><span style="color:red;font-weight:bold" >用户包月日志数据 </span>  |  总数:<?php  echo count($item['baoyue'])?></div>

				<div class="panel-body table-responsive">

				    <form action="" method="post" onsubmit="">

					<table class="table table-hover">

						<thead class="navbar-inner">

							<tr>
								<th style="width:30%;">包月开始时间</th>

								<th style="width:30%">包月结束时间</th>

								<th style="width:30%;">充值时间</th>

							</tr>

						</thead>

						<tbody id="main">

							<?php  if(is_array($item['baoyue'])) { foreach($item['baoyue'] as $row) { ?>

							<tr>
								<td  style="white-space:normal;word-break:break-all"><?php  echo date('Y年n月j日 G:i', $row['starttime']);?></td>

								<td style="white-space:normal;word-break:break-all"><?php  echo date('Y年n月j日 G:i', $row['endtime']);?></td>

								<td><?php  echo date('Y年n月j日 G:i', $row['createtime']);?></td>

							</tr>

							<?php  } } ?>

						</tbody>

					</table>

					</form>

			    </div>

			</div>
		<?php  } ?>

		<!--end -->

		<!--收信宝日志 -->

		<?php  if(count($item['baoyue'])>0) { ?>
			<div class="panel panel-default">

				<div class="panel-heading"><span style="color:red;font-weight:bold" >用户收信宝日志数据 </span>  |  总数:<?php  echo count($item['shouxin'])?></div>

				<div class="panel-body table-responsive">

				    <form action="" method="post" onsubmit="">

					<table class="table table-hover">

						<thead class="navbar-inner">

							<tr>
								<th style="width:30%;">收信宝开始时间</th>

								<th style="width:30%">收信宝结束时间</th>

								<th style="width:30%;">充值时间</th>

							</tr>

						</thead>

						<tbody id="main">

							<?php  if(is_array($item['shouxin'])) { foreach($item['shouxin'] as $row) { ?>

							<tr>
								<td  style="white-space:normal;word-break:break-all"><?php  echo date('Y年n月j日 G:i', $row['starttime']);?></td>

								<td style="white-space:normal;word-break:break-all"><?php  echo date('Y年n月j日 G:i', $row['endtime']);?></td>

								<td><?php  echo date('Y年n月j日 G:i', $row['createtime']);?></td>

							</tr>

							<?php  } } ?>

						</tbody>

					</table>

					</form>

			    </div>

			</div>
		<?php  } ?>

		<!--end -->

		<!--登陆日志 -->

		<?php  if(count($item['login'])>0) { ?>
		<link rel="stylesheet" href="../addons/jy_ppp/css/loginmain.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="../addons/jy_ppp/css/calendar.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<script src="../addons/jy_ppp/js/jquery-1.7.1.min.js" type="text/javascript"></script>
		<script>
			var login_arr=new Array();
			var l_id=0;
			<?php  if(is_array($item['login'])) { foreach($item['login'] as $l) { ?>
				<?php  $t_l=date('Y-m-d',$l['createtime'])?>
				login_arr[l_id]=<?php  echo strtotime($t_l)?>;
				l_id++;
			<?php  } } ?>

		    var myDate = new Date();
		    //alert(myDate);
		    var y=myDate.getFullYear();
			$(document).ready(function() {
				$("#main-container").calendar({
					tipsy_gravity: 's', // How do you want to anchor the tipsy notification? (n / s / e / w)
					click_callback: calendar_callback, // Callback to return the clicked date
					//year: y, // Optional, defaults to current year - pass in a year - Integer or String
					scroll_to_date: false // Scroll to the current date?
				});
			});

			//
			// Example of callback - do something with the returned date
			var calendar_callback = function(date) {
				
				//
				// Returned date is a date object containing the day, month, and year.
				// date.day = day; date.month = month; date.year = year;
				var stringTime = date.year+"-"+date.month+"-"+date.day;
				var timestamp2 = Date.parse(new Date(stringTime));
				timestamp2 = timestamp2 / 1000;
				if( $.inArray(timestamp2, login_arr)>-1 )
				{
					alert("该用户于"+date.year+"年"+date.month+"月"+date.day+"日"+"有签到！");
				}
				else
				{
					alert("该用户于"+date.year+"年"+date.month+"月"+date.day+"日"+"并未签到！");
				}
			    
				console.log(date);
			}
		</script>
		<script src="../addons/jy_ppp/js/jquery.calendar.js" type="text/javascript" charset="utf-8"></script>
			<div class="panel panel-default">

				<div class="panel-heading"><span style="color:red;font-weight:bold" >用户登陆日志数据 </span>  |  总数:<?php  echo count($item['login'])?></div>

				<div class="panel-body table-responsive">
					<div id="main-container">
					</div>
				</div>
			</div>
		<?php  } ?>

		<!--end -->

	<?php  if($row['type']==3) { ?>
		<script type="text/javascript" src="../addons/jy_ppp/js/jquery.qrcode.js"></script>
		<script type="text/javascript">
			function showPsw(){
				$("#pswDiv").slideToggle();
			}

			

			$("#bindwechat").bind("click",function(){
			    $("#codeDiv").removeClass('none');
			});
			$("#codeDiv").bind("click",function(){
			    $(this).addClass('none');
			});

			window.onload = function(){
			    jQuery(function(){
			        jQuery('#code').qrcode("<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('bind',array('id'=>$row['id'])),2)?>");
			    })
			}
		</script>
	<?php  } else { ?>
		<script type="text/javascript">
			function showcredit(){
				$("#showcredit").slideToggle();
				$("#submit3").slideToggle();
			}
		</script>
	<?php  } ?>
	<script type="text/javascript">
		require(['jquery', 'util'], function($, util){
			util.clip($('#needCopy')[0], $('#needCopy').text());
			util.clip($('#needCopy2')[0], $('#needCopy2').text());
		});
	</script>

<?php  } ?>

<script>

$(function(){

    $(".check_all").click(function(){

       var checked = $(this).get(0).checked;

       $("input[type=checkbox]").attr("checked",checked);

    });

});

</script>


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>