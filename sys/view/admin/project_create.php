<h3>创建新的项目</h3>

<div id="alert_box"></div>

<form class="form-horizontal" role="form" action="<?php echo get_url(array(
	'Admin',
	'ajax'
), "?type=project_create")?>" method="post">
	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">项目名称:</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputName" name="name" placeholder="输入一个非中文的名称（如:FileSystem）">
		</div>
	</div>
	<div class="form-group">
		<label for="inputTitle" class="col-sm-2 control-label">项目标题:</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputTitle" name="title" placeholder="项目标题（如:文件系统）">
		</div>
	</div>
	<div class="form-group">
		<label for="inputSort" class="col-sm-2 control-label">项目序号:</label>

		<div class="col-sm-10">
			<input type="number" class="form-control" id="inputSort" name="sort" placeholder="输入数字，越大越靠后">
		</div>
	</div>
	<div class="form-group">
		<label for="inputDesc" class="col-sm-2 control-label">项目描述:</label>

		<div class="col-sm-10">
			<textarea id="inputDesc" name="desc" class="form-control" rows="3"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="inputType" class="col-sm-2 control-label">项目类型:</label>

		<div class="col-sm-10">
			<select id="inputType" class="form-control" name="type">
				<option value="project">项目</option>
				<option value="page">单页面</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">开始创建项目</button>
		</div>
	</div>
</form>
<script>
	$("form").ajaxForm(function (data) {
		$(".alert").alert('close');
		if (data['status']) {
			window.location.href = "<?php echo get_url(array(
				'Admin',
				'page_edit'
			), "?id=")?>" + data['page_id'];
		} else {
			$("#alert_box").html("<div class='alert alert-danger in'><a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a>" +
					"<p>错误信息：" + data['message'] + "<br>" + "数据库错误信息：" + data['sql_error'] + "</p></div>");
		}
		return false;
	});
</script>
