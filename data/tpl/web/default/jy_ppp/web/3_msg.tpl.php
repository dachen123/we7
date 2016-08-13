<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li <?php  if($operation == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('msg', array('op' => 'display'))?>">消息库管理</a></li>
	<li <?php  if($operation == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('msg', array('op' => 'post'))?>"><?php  if($id==0) { ?>添加消息库<?php  } else { ?>修改消息库<?php  } ?></a></li>

</ul>
<style>
.panel-body {
padding: 10px;
}
</style>
<?php  if($operation == 'post') { ?>
<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
		<div class="panel panel-default">
			<div class="panel-heading">
				添加消息库
			</div>

			<?php  if(!empty($parentid)) { ?>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">上级消息标题</label>
						<div class="col-sm-5">
							<label><?php  echo $parent['name'];?></label>
						</div>
					</div>
				</div>
			<?php  } ?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">排序</label>
					<div class="col-sm-5">
						<input type="text" name="displayorder" class="form-control" value="<?php  echo $category['displayorder'];?>" />
					</div>
				</div>
			</div>

			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">消息标题</label>
					<div class="col-sm-5">
						<input type="text" name="catename" class="form-control" value="<?php  echo $category['name'];?>" />
					</div>
				</div>
			</div>

			<?php  if(empty($parentid)) { ?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">消息类型</label>
					<div class="col-sm-5">
						<label for="enabled3" class="radio-inline"><input type="radio" name="type" value="0" id="enabled3" onclick="desc_hide();thumb_hide();url_hide();media_hide();media_id_hide()"  <?php  if(empty($category['type']) || $category['type'] == 0) { ?>checked="true"<?php  } ?> /> 文本</label>
						&nbsp;&nbsp;&nbsp;
						<label for="enabled4" class="radio-inline"><input type="radio" name="type" value="1" id="enabled4" onclick="desc_hide();thumb_hide();url_hide();media_hide();media_id_show()" <?php  if(!empty($category['type']) && $category['type'] == 1) { ?>checked="true"<?php  } ?> /> 图片</label>
	                    &nbsp;&nbsp;&nbsp;
	                    <label for="enabled5" class="radio-inline"><input type="radio" name="type" value="2" id="enabled5" onclick="desc_show();thumb_show();url_show();media_hide();media_id_hide()" <?php  if(!empty($category['type']) && $category['type'] == 2) { ?>checked="true"<?php  } ?> /> 单图文</label>
	                    &nbsp;&nbsp;&nbsp;
	                    <label for="enabled6" class="radio-inline"><input type="radio" name="type" value="3" id="enabled6" onclick="desc_hide();thumb_hide();url_hide();media_hide();media_id_hide()" <?php  if(!empty($category['type']) && $category['type'] == 3) { ?>checked="true"<?php  } ?> /> 多图文</label>
	                    &nbsp;&nbsp;&nbsp;
	                    <label for="enabled7" class="radio-inline"><input type="radio" name="type" value="4" id="enabled7" onclick="desc_hide();thumb_hide();url_hide();media_show();media_id_hide()" <?php  if(!empty($category['type']) && $category['type'] == 4) { ?>checked="true"<?php  } ?> /> 语音</label>
					</div>
				</div>
			</div>
			<?php  } ?>

			<?php  if(!empty($parentid)) { ?>
			<div class="panel-body" id="desc" >
			<?php  } else { ?>
				<?php  if($category['type']==2) { ?>
				<div class="panel-body" id="desc">
				<?php  } else { ?>
				<div class="panel-body" id="desc" style="display:none">
				<?php  } ?>
			<?php  } ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">图文描述</label>
					<div class="col-sm-5">
						<textarea class="form-control" rows="5" name="description"><?php  echo $category['description'];?></textarea>
					</div>
				</div>
			</div>
			<?php  if(!empty($parentid)) { ?>
				<div class="panel-body" id="url">
			<?php  } else { ?>
				<?php  if($category['type']==2) { ?>
				<div class="panel-body" id="url">
				<?php  } else { ?>
				<div class="panel-body" id="url" style="display:none">
				<?php  } ?>
			<?php  } ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">URL</label>
					<div class="col-sm-5">
						<input type="text" name="url" class="form-control" value="<?php  echo $category['url'];?>" />
					</div>
				</div>
			</div>


			<?php  if($category['type']==1) { ?>
				<div class="panel-body" id="media_id">
			<?php  } else { ?>
				<div class="panel-body" id="media_id" style="display:none">
			<?php  } ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">图片</label>
					<div class="col-sm-5">
						<?php  echo tpl_form_field_wechat_image('media_id', $category['media_id']);?>
					</div>
				</div>
			</div>

			<?php  if(!empty($parentid)) { ?>
				<div class="panel-body" id="thumb">
			<?php  } else { ?>
				<?php  if($category['type']==2) { ?>
					<div class="panel-body" id="thumb">
				<?php  } else { ?>
					<div class="panel-body" id="thumb" style="display:none">
				<?php  } ?>
			<?php  } ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">图片</label>
					<div class="col-sm-5">
						<?php  echo tpl_form_field_image('picurl', $category['picurl']);?>
					</div>
				</div>
			</div>

			<?php  if($category['type']==4) { ?>
				<div class="panel-body" id="media">
			<?php  } else { ?>
				<div class="panel-body" id="media" style="display:none">
			<?php  } ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">音频</label>
					<div class="col-sm-5">
						<?php  echo tpl_form_field_wechat_voice('media', $category['media']);?>
					</div>
				</div>
			</div>

			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">是否显示</label>
					<div class="col-sm-5">
						<label for="enabled1" class="radio-inline"><input type="radio" name="enabled" value="1" id="enabled1" <?php  if(!empty($category['enabled']) && $category['enabled'] == 1) { ?>checked="true"<?php  } ?> /> 是</label>
	                    &nbsp;&nbsp;&nbsp;
	                    <label for="enabled2" class="radio-inline"><input type="radio" name="enabled" value="0" id="enabled2"  <?php  if(empty($category['enabled']) || $category['enabled'] == 0) { ?>checked="true"<?php  } ?> /> 否</label>	           
					</div>
				</div>
			</div>
			<div class="form-group col-sm-12">
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				<input type="hidden" name="id" value="<?php  echo $category['id'];?>" />
				<input type="submit" class="btn btn-primary col-lg-1" name="submit" value="提交" />
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	function desc_show(){
		$("#desc").show();
	}
	function desc_hide(){
		$("#desc").hide();
	}
	function thumb_show(){
		$("#thumb").show();
	}
	function thumb_hide(){
		$("#thumb").hide();
	}
	function url_show(){
		$("#url").show();
	}
	function url_hide(){
		$("#url").hide();
	}
	function media_show(){
		$("#media").show();
	}
	function media_hide(){
		$("#media").hide();
	}
	function media_id_show(){
		$("#media_id").show();
	}
	function media_id_hide(){
		$("#media_id").hide();
	}
</script>

<?php  } else if($operation == 'display') { ?>

<div class="main">
	<div class="category">
		
			<div class="panel panel-default">
				<div class="panel-heading">
					管理消息库
				</div>
				<div class="panel-body table-responsive">
					<form action="" method="post" class="form-horizontal form" onsubmit="return formcheck(this)">
					<table class="table table-hover">

					<thead class="navbar-inner">
						<tr>
							<th style="width:10px;"></th>
							<th style="width:100px;">显示顺序</th>
							<th style="width:200px;">消息标题</th>
							<th style="width:100px;">消息类型</th>
							<th style="width:100px;">是否显示</th>
							<th style="width:180px;">操作</th>
						</tr>
					</thead>

					<tbody id="main">
						<?php  if(is_array($category)) { foreach($category as $row) { ?>
						<tr>
							<td>
								<?php  if(count($children[$row['id']]) > 0) { ?>
									<a href="javascript:;" >
										
									</a>
								<?php  } ?>
							</td>
							<td class="text-center">
								<input type="text" name="displayorder[<?php  echo $row['id'];?>]" value="<?php  echo $row['displayorder'];?>">
							</td>

							<td class="text-left">
								<div style="height:30px;line-height:30px;"><?php  echo $row['name'];?>&nbsp;&nbsp;
									<?php  if($row['type']==3) { ?>
									<a href="<?php  echo $this->createWebUrl('msg', array('parentid' => $row['id'], 'op' => 'post'))?>">
										<i class="fa fa-plus"></i> 添加图文消息</a>
									<?php  } ?>
								</div>
							</td>

							<td><?php  if(empty($row['type'])) { ?><div class="btn btn-default btn-sm">文字</div><?php  } ?><?php  if($row['type'] == 1) { ?><div class="btn btn-success btn-sm">图片</div><?php  } ?><?php  if($row['type'] == 2) { ?><div class="btn btn-info btn-sm">单图文</div><?php  } ?><?php  if($row['type'] == 3) { ?><div class="btn btn-warning btn-sm">多图文</div><?php  } ?><?php  if($row['type'] == 4) { ?><div class="btn btn-danger btn-sm">语音</div><?php  } ?>
							</td>

							<td><?php  if(!empty($row['enabled']) && $row['enabled'] == 1) { ?><div class="btn btn-success btn-sm">显示</div><?php  } else { ?><div class="btn btn-default btn-sm">不显示</div><?php  } ?>
							</td>

							<td>
								<a href="<?php  echo $this->createWebUrl('msg', array('op' => 'post', 'id' => $row['id']))?>" title="编辑" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit">编辑</i></a>

								<a href="<?php  echo $this->createWebUrl('msg', array('op' => 'delete','id' => $row['id']))?>" onclick="return confirm('确认删除此分类吗？');return false;" title="删除" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-times">删除</i></a>

							</td>
						</tr>
							<?php  if(is_array($children[$row['id']])) { foreach($children[$row['id']] as $item) { ?>
								<tr>
									<td></td>
									<td class="text-center"><input type="text" class="span1" name="displayorder[<?php  echo $item['id'];?>]" value="<?php  echo $item['displayorder'];?>"></td>
									<td class="text-left"><div style="padding-left:50px;height:30px;line-height:30px;background:url('./resource/images/bg_repno.gif') no-repeat -245px -545px;color:#3366cc"><?php  echo $item['name'];?>&nbsp;&nbsp;
									</div></td>
									<td></td>
									<td><?php  if(!empty($item['enabled']) && $item['enabled'] == 1) { ?><div class="btn btn-success btn-sm">显示</div><?php  } else { ?><div class="btn btn-default btn-sm">不显示</div><?php  } ?></td>
									<td>

									<a href="<?php  echo $this->createWebUrl('msg', array('op' => 'post', 'id' => $item['id'],'parentid'=>$row['id']))?>" title="编辑" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit">编辑</i></a>

									<a href="<?php  echo $this->createWebUrl('msg', array('op' => 'delete','id' => $item['id']))?>" onclick="return confirm('确认删除此分类吗？');return false;" title="删除" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-times">删除</i></a>
									</td>
								</tr>
							<?php  } } ?>
					<?php  } } ?>
					<tr>
						<td></td>
						<td colspan="5">
							<input name="submit" type="submit" class="btn btn-primary" value="提交">
							<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
						</td>
					</tr>
					</tbody>
					</table>
					</form>
				</div>
			</div>
	</div>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>