<h3>【<?php echo strtoupper($__name) ?>】版本列表
	<small><a class="label label-success" href="<?php echo get_url('Admin', 'version', $__name, 'add') ?>">添加新版本</a>
	</small>
</h3>
<div class="well well-sm">
	<p>最新版本：<?php echo $__info['top_version'] ?>(<?php echo $__info['top_version_code'] ?>)<a
			href="#" class="pull-right label label-warning version_edit_info">编辑信息</a>
	</p>

	<p>下载地址：<a class="v_download" href="<?php echo $__info['download'] ?>"
			   rel="external"><?php echo $__info['download'] ?></a></p>
	<?php if(!empty($__info['info'])): ?>
		<p class="v_info"><?php echo $__info['info'] ?></p>
	<?php endif; ?>
</div>
<?php if(count($__list)): ?>
	<table class="table">
		<thead>
		<tr>
			<th>时间</th>
			<th>版本</th>
			<th>版本号</th>
			<th>构建版本</th>
			<th>下载地址</th>
			<th>更新地址</th>
			<th>操作</th>
		</tr>
		</thead>
		<?php foreach($__list as $v): ?>
			<tr>
				<td><?php echo date("Y-m-d H:i:s", $v['time']) ?></td>
				<td><?php echo $v['version'] ?></td>
				<td><?php echo $v['version_code'] ?></td>
				<td><?php echo $v['build_version'] ?></td>
				<td><a href="<?php echo $v['download_url'] ?>" rel="external">GO</a></td>
				<td><a href="<?php echo $v['update_url'] ?>" rel="external">GO</a></td>
				<td><a href="#" data="<?php echo $v['id'] ?>" class="label label-info version_get_detail">详情</a>
					<a href="<?php echo get_url("Admin","version",$__name,"edit",$v['id'])?>" class="label label-warning version_edit">编辑</a>
					<a href="#" data="<?php echo $v['id'] ?>" class="label label-danger version_del">删除</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p class="text-danger">无任何可查询数据</p>
<?php endif; ?>
<div id="VersionEditInfo" style="display: none">
	<form role="form" class="form-horizontal" action="<?php echo get_url(array(
		'Admin',
		'ajax'
	), "?type=version_edit_info")?>" method="post">
		<div class="form-group">
			<label for="inputName" class="col-sm-2 control-label">所属:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputName" name="name" readonly
					   value="<?php echo $__name ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="inputDownload" class="col-sm-2 control-label">下载地址:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputDownload" name="url" placeholder="下载页面地址">
			</div>
		</div>
		<div class="form-group">
			<label for="inputInfo" class="col-sm-2 control-label">信息:</label>

			<div class="col-sm-10">
				<textarea class="form-control" id="inputInfo" name="info" placeholder="详细描述，换行分割"></textarea>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary">修改</button>
			</div>
		</div>
	</form>
</div>