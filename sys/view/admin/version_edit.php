<h3>编辑版本</h3>
<?php
/**
 * @var array $__info
 */
if(!isset($__info['id'])):?>
	<p class="text-danger">当前编辑的内容未找到</p>
<?php else: ?>
	<form role="form" class="form-horizontal" id="VersionEditForm" action="<?php echo get_url(array(
		'Admin',
		'ajax'
	), "?type=version_edit")?>" method="post">
		<input type="hidden" name="id" value="<?php echo $__info['id'] ?>">

		<div class="form-group">
			<label for="inputName" class="col-sm-2 control-label">所属:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputName" readonly value="<?php echo $__info['name'] ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="inputVersion" class="col-sm-2 control-label">版本:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputVersion" readonly
					   value="<?php echo $__info['version'] ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="inputVersionCode" class="col-sm-2 control-label">版本代号:</label>

			<div class="col-sm-10">
				<input type="number" class="form-control" id="inputVersionCode"  readonly
					   value="<?php echo $__info['version_code'] ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="inputBuildVersion" class="col-sm-2 control-label">构建版本:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputBuildVersion" readonly
					   value="<?php echo $__info['build_version'] ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="inputForceUpdate" class="col-sm-2 control-label">强制更新:</label>

			<div class="col-sm-10">
				<select id="inputForceUpdate" class="form-control" name="force_update">
					<option value="1"<?php echo $__info['force_update']?" selected":""?>>是</option>
					<option value="0"<?php echo !$__info['force_update']?" selected":""?>>否</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="InputDownloadUrl" class="col-sm-2 control-label">下载地址:</label>

			<div class="col-sm-10">
				<input type="url" class="form-control" id="InputDownloadUrl" name="download_url" value="<?php echo $__info['download_url']?>">
			</div>
		</div>
		<div class="form-group">
			<label for="InputUpdateUrl" class="col-sm-2 control-label">下载更新页面:</label>

			<div class="col-sm-10">
				<input type="url" class="form-control" id="InputUpdateUrl" name="update_url" value="<?php echo $__info['update_url']?>">
			</div>
		</div>
		<div class="form-group">
			<label for="InputMessage" class="col-sm-2 control-label">新版本信息:</label>

			<div class="col-sm-10">
				<p class="help-block">对于新版本的简单介绍</p>
				<textarea name="message" class="form-control" id="InputMessage"><?php echo $__info['message']?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="InputUpdateInfo" class="col-sm-2 control-label">更新信息:</label>

			<div class="col-sm-10">
				<p class="help-block">每行一条，用于描述相对更新的内容，支持行内HTML标记，<b><i>[#]</i></b>分割内容与详情网址</p>
				<textarea name="update_info" class="form-control" id="InputUpdateInfo"><?php echo htmlentities($__info['update_info'])?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="InputBug" class="col-sm-2 control-label">BUG信息:</label>

			<div class="col-sm-10">
				<p class="help-block">当前版本的BUG列表，每行一个，<b><i>[#]</i></b>分割内容与详情网址</p>
				<textarea name="bugs" class="form-control" id="InputBug"><?php echo htmlentities($__info['bugs'])?></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary">更新</button>
			</div>
		</div>
	</form>
<?php endif; ?>