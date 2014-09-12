PageEdit = function () {

}
PageEdit.page_info = null;
PageEdit.load = function (id) {
	$.get(SITE_URL + 'Admin/ajax', {type:'page_info', id:id}, function (data) {
		if (data['status']) {
			PageEdit.page_info = data;
			$("form#page_edit input[name='title']").val(data['data']['title']);
			$("form#page_edit input[name='time']").val(data['data']['time']);
			$("form#page_edit input[name='view']").val(data['data']['view']);
			$("form#page_edit input[name='type']").val(data['data']['type']);
			$("form#page_edit input[name='keywords']").val(data['data']['keywords']);
			$.each(data['status_list'], function (index, elem) {
				$("form#page_edit select[name='status']").append('<option>' + elem + '</option>');
			});
			$("form#page_edit select[name='status']").val(data['data']['status']);
			editor.html(data['data']['content']);
		} else {
			PageEdit.alert("请求内容不存在，当前ID为" + id + "!", 'danger');
			$("form#page_edit").hide();
		}
	});
	$("form#page_edit").ajaxForm(function (data) {
		$("#alert_box").html("");
		switch (data['status']) {
			case 'success':
				PageEdit.alert("内容修改成功", "success");
				break;
			case 'no change':
				PageEdit.alert("内容无修改", "warning");
				break;
			case 'error':
				PageEdit.alert('修改出错<br>错误信息：' + data['message'], "danger");
				break;
		}
		setTimeout('$(".alert").alert("close");', 5000);
	});
}
PageEdit.alert = function (content, type) {
	$("#alert_box").html("<div class='alert alert-" + type + " in'>" +
		"<a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a>" +
		"<p>" + content + "</p>" +
		"</div>");
}