<h3>修改你的项目</h3>

<div id="alert_box"></div>

<form class="form-horizontal" role="form" action="<?php echo get_url(array(
	'Admin',
	'ajax'
), "?type=project_edit")?>" method="post">
	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">项目名称:</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputName" value="<?php echo $__name?>" name="name" placeholder="输入一个非中文的名称（如:FileSystem）">
		</div>
	</div>
	<div class="form-group">
		<label for="inputTitle" class="col-sm-2 control-label">项目标题:</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputTitle" value="<?php echo $__title?>" name="title" placeholder="项目标题（如:文件系统）">
		</div>
	</div>
	<div class="form-group">
		<label for="inputSort" class="col-sm-2 control-label">项目序号:</label>

		<div class="col-sm-10">
			<input type="number" class="form-control" id="inputSort" name="sort"  value="<?php echo $__sort?>" placeholder="输入数字，越大越靠后">
		</div>
	</div>
	<div class="form-group">
		<label for="inputDesc" class="col-sm-2 control-label">项目描述:</label>

		<div class="col-sm-10">
			<textarea id="inputDesc" name="desc" class="form-control" rows="3"><?php echo $__desc?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="inputType" class="col-sm-2 control-label">项目类型:</label>

		<div class="col-sm-10">
			<select id="inputType" class="form-control" name="type">
				<option value="project"<?php echo $__type=='project'?" selected":"";?>>项目</option>
				<option value="page"<?php echo $__type=='page'?" selected":"";?>>单页面</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">修改项目</button>
		</div>
	</div>
	<input type="hidden" name="id" value="<?php echo $__id?>">
</form>
<script>
	$("form").ajaxForm(function (data) {
		$(".alert").alert('close');
		if (data['status']) {
			$("#alert_box").html("<div class='alert alert-success in'><a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a>" +
					"<p>数据已更新</p></div>");
		} else {
			$("#alert_box").html("<div class='alert alert-danger in'><a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a>" +
					"<p>错误信息：" + data['message'] + "<br>" + "数据库错误信息：" + data['sql_error'] + "</p></div>");
		}
		setTimeout('$(".alert").alert("close");',5000);
		return false;
	});
</script>
