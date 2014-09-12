<h3>修改用户信息</h3>
<form id="user_edit" role="from" method="post" action="<?php echo get_url(array(
	'Admin',
	'ajax'
), '?type=user_password')?>" class="form-horizontal">
	<div id="alert_box"></div>
	<div class="form-group">
		<label for="inputOldPassword" class="col-sm-2 control-label">原始密码</label>

		<div class="col-sm-10">
			<input type="password" id="inputOldPassword" name="old_password" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label for="inputNewPassword" class="col-sm-2 control-label">新密码</label>

		<div class="col-sm-10">
			<input type="password" id="inputNewPassword" name="new_password" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label for="inputConfirmPassword" class="col-sm-2 control-label">确认新密码</label>

		<div class="col-sm-10">
			<input type="password" id="inputConfirmPassword" name="confirm_password" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">修改密码</button>
			<button type="button" id="UpdateToken" class="btn btn-warning" title="此操作导致需要重新登录">更新TOKEN</button>
			<button type="button" id="GetNewUserInfo" data-content="" data-container="body" data-toggle="popover" data-placement="top" class="btn btn-info" title="新的用户数据">生成新用户信息</button>
		</div>
	</div>
</form>
<script>
	$("form").ajaxForm(function (data) {
		if (data['status']) {
			alert_box("密码已修改，刷新重新登录", 'success');
		} else {
			alert_box("密码修改出错:" + data['error'], 'danger');
		}
	});
	$("#UpdateToken").click(function () {
		$.post(SITE_URL + "Admin/ajax?type=user_token_update", {confirm:"OK"}, function (data) {
			if (data['status']) {
				alert_box("TOKEN已更新，刷新重新登录", 'success');
			} else {
				alert_box("Token更新出错：" + data['error'], 'danger');
			}
		});
	});

	$('#GetNewUserInfo').click(function () {
		$.post(SITE_URL + "Admin/ajax?type=user_new_user_info", {confirm:"OK"}, function (data) {
			if (data['status']) {
				alert_box("密码明文:<em>" + html_encode(data['data']['plain']) + "</em>" +
						"<br>SQL信息:" +
						"<br>Password:<em>" + html_encode(data['data']['password']) + "</em>" +
						"<br>Salt:<em>" + html_encode(data['data']['salt']) + "</em>" +
						"<br>Token:<em>" + html_encode(data['data']['token']) + "</em>", "success", false);
			} else {
				alert_box("信息获取出错：" + data['error'], 'danger');
			}
		});
	});
	alert_box = function (content, type, close) {
		$("#alert_box").html("<div class='alert fade alert-" + type + " in'>" +
				"<a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a>" +
				"<p>" + content + "</p>" +
				"</div>");
		if (close == undefined || close == true)
			setTimeout('$(".alert").alert("close");', 5000);
	}
	html_encode = function (str) {
		var s = "";
		if (str.length == 0) return "";
		for (var i = 0; i < str.length; i++) {
			switch (str.substr(i, 1)) {
				case "<":
					s += "&lt;";
					break;
				case ">":
					s += "&gt;";
					break;
				case "&":
					s += "&amp;";
					break;
				case "'":
					s += "&#039;";
					break;
				case " ":
					if (str.substr(i + 1, 1) == " ") {
						s += " &nbsp;";
						i++;
					} else s += " ";
					break;
				case "\"":
					s += "&quot;";
					break;
				default:
					s += str.substr(i, 1);
					break;
			}
		}
		return s;
	}
</script>