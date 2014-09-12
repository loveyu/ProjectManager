<div id="item_create">
	<h3>标题：<?php echo $__p_title?>, 名称:<a title="编辑项目信息" href="<?php echo get_url(array(
		'Admin',
		'project_edit'
	), '?id=' . $__p_id)?>"><?php echo $__p_name?></a></h3>

	<div id="alert_box"></div>

	<form role="form" class="form-horizontal" action="<?php echo get_url(array(
		'Admin',
		'ajax'
	), "?type=item_edit")?>" method="post">
		<div class="form-group">
			<label for="inputName" class="col-sm-2 control-label">子项目名称:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" value="<?php echo $__i_name?>" id="inputName" name="name" placeholder="输入一个非中文的名称">
			</div>
		</div>
		<div class="form-group">
			<label for="inputTitle" class="col-sm-2 control-label">子项目标题:</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" value="<?php echo $__i_title?>" id="inputTitle" name="title" placeholder="项目标题">
			</div>
		</div>
		<div class="form-group">
			<label for="inputSort" class="col-sm-2 control-label">子项目序号:</label>

			<div class="col-sm-10">
				<input type="number" class="form-control" value="<?php echo $__i_sort?>" id="inputSort" name="sort" placeholder="输入数字，越大越靠后">
			</div>
		</div>
		<div class="form-group">
			<label for="inputDesc" class="col-sm-2 control-label">子项目描述:</label>

			<div class="col-sm-10">
				<textarea id="inputDesc" name="desc" class="form-control" rows="3"><?php echo $__i_desc?></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">修改子项目信息</button>
			</div>
		</div>
		<input name="id" type="hidden" value="<?php echo $__i_id?>"/></label>
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
</div>