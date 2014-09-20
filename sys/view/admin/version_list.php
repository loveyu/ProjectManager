<div id="item_create_select">
	<h3>选择版本控制
		<small><a class="label label-success" href="#" id="AddNewVersionType">添加</a></small>
	</h3>

	<div class="list">
		<?php
		if(empty($__list)){
			echo "<p class='text-danger'>没有任何版本项目</p>";
		}
		foreach($__list as $v){
			echo "<a class=\"label label-info\" href=\"", get_url(array(
				'Admin',
				'version',
				$v['name']
			)), "\">", $v['name'], "</a> \n";
		}
		?>
	</div>
</div>
<div id="AddNewVersionBox" style="display: none">
	<form role="form" class="form-horizontal" action="<?php echo get_url(array(
		'Admin',
		'ajax'
	), "?type=version_create")?>" method="post">
		<div class="form-group">
			<label for="inputName" class="col-sm-2 control-label">名称:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputName" name="name" placeholder="输入名称">
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
				<button type="submit" class="btn btn-primary">创建</button>
			</div>
		</div>
	</form>
</div>