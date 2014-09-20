<h3>添加新版本</h3>
<form role="form" class="form-horizontal" id="VersionAddForm" action="<?php echo get_url(array(
	'Admin',
	'ajax'
), "?type=version_add")?>" method="post">
	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">所属:</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputName" name="name" readonly value="<?php echo $__name ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputVersion" class="col-sm-2 control-label">新版本:</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputVersion" name="version" placeholder="like: 1.1.1">
		</div>
	</div>
	<div class="form-group">
		<label for="inputVersionCode" class="col-sm-2 control-label">版本代号:</label>

		<div class="col-sm-10">
			<input type="number" class="form-control" id="inputVersionCode" name="version_code" placeholder="数字">
		</div>
	</div>
	<div class="form-group">
		<label for="inputBuildVersion" class="col-sm-2 control-label">构建版本:</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputBuildVersion" name="build_version"
				   placeholder="like: <?php echo date("Ymd") ?>01">
		</div>
	</div>
	<div class="form-group">
		<label for="inputForceUpdate" class="col-sm-2 control-label">强制更新:</label>

		<div class="col-sm-10">
			<select id="inputForceUpdate" class="form-control" name="force_update">
				<option value="1">是</option>
				<option value="0">否</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="InputDownloadUrl" class="col-sm-2 control-label">下载地址:</label>

		<div class="col-sm-10">
			<input type="url" class="form-control" id="InputDownloadUrl" name="download_url" placeholder="版本下载地址">
		</div>
	</div>
	<div class="form-group">
		<label for="InputUpdateUrl" class="col-sm-2 control-label">下载更新页面:</label>

		<div class="col-sm-10">
			<input type="url" class="form-control" id="InputUpdateUrl" name="update_url" placeholder="更新页面">
		</div>
	</div>
	<div class="form-group">
		<label for="InputMessage" class="col-sm-2 control-label">新版本信息:</label>

		<div class="col-sm-10">
			<p class="help-block">对于新版本的简单介绍</p>
			<textarea name="message" class="form-control"  id="InputMessage"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="InputUpdateInfo" class="col-sm-2 control-label">更新信息:</label>

		<div class="col-sm-10">
			<p class="help-block">每行一条，用于描述相对更新的内容，支持行内HTML标记</p>
			<textarea name="update_info" class="form-control"  id="InputUpdateInfo"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button class="btn btn-primary">创建</button>
		</div>
	</div>
</form>