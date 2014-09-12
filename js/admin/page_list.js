$(function () {
	$("#page_list form button").click(PageList.form_set);
	PageList.load(1, PageList.form_number());
});

PageList = function () {
}
PageList.now_page = 1;
PageList.load = function (page, number) {
	$("#page_list .panel-heading small").removeClass('hidden');
	$.get(SITE_URL + 'Admin/ajax', {type:"project_list", page:page, number:number}, function (data) {
		if (data['status']) {
			PageList.table(data['data']);
			PageList.pagination(data['count'], data['page'], data['number']);
		} else if (data['count'] > 0) {
			PageList.error("第 " + data['page'] + " 不存在", 'warning');
			PageList.pagination(data['count'], data['page'], data['number']);
		} else {
			PageList.error("内容加载失败", 'danger');
		}
		$("#page_list .panel-heading small").addClass('hidden');
	});
}
PageList.form_set = function () {
	PageList.load(PageList.now_page, PageList.form_number());
}
PageList.form_number = function () {
	return $("#page_list form input[name='number']").val();
}
PageList.pagination = function (count, now, number) {
	PageList.now_page = now;
	$("#page_list form input[name='number']").val(number);
	$("#page_list .pagination").html("");
	$("#page_list .pagination").removeClass("hidden");
	var all = Math.ceil(count / number);
	$("#page_list .pagination").append('<li' + ((now == 1) ? ' class="disabled"' : '') + '><a href="#page_' + (now - 1) + '">&laquo;</a></li>');
	for (var i = 1; i <= all; i++) {
		if (i == now) {
			$("#page_list .pagination").append("<li class=\"active\"><a href=\"#page_" + i + "\">" + i + "</a></li>");
		} else {
			$("#page_list .pagination").append("<li><a href='#page_" + i + "'>" + i + "</a></li>");
		}
	}
	$("#page_list .pagination").append('<li' + ((now == all) ? ' class="disabled"' : '') + '><a href="#page_' + (now + 1) + '">&raquo;</a></li>');
	$("#page_list .pagination li a").click(function () {
		var page = ($(this).attr('href') + "").substr(6);
		if (page > 0 && page <= all) {
			PageList.load(page, PageList.form_number());
		}
		return false;
	});
}
PageList.table = function (data) {
	PageList.table_show();
	$("#page_list table tbody").html("");
	$.each(data, function (i, elem) {
		$("#page_list tbody").append("<tr><td>" + elem['id'] + "</td><td>" + elem['name'] + (elem['type'] == 'page' ? "(单页)" : "") + "</td><td>" + elem['title'] + "</td><td>" +
			"<a href=\"?id=" + elem['page_id'] + "\">页面</a>&nbsp;" +
			"<a href=\"" + SITE_URL + "Admin/project_edit?id=" + elem['id'] + "\">信息</a>&nbsp;" +
			"<a class='delete' href=\"#delete_proj_" + elem['id'] + "\">删除</a></td>" +
			"<td><span class='label label-info view_item' id='view_project_item_" + elem['id'] + "'>查看</span></td></tr>");
	});
	$("#page_list table tbody a.delete").unbind('click').click(PageList.delete);
	$("#page_list table tbody span.view_item").click(function () {
		var id = $(this).attr('id').substr(18);
		PageList.load_item(id);
	});
}
PageList.load_item = function (id) {
	$("#page_list .item_list_table").remove();
	$("#page_list .panel-heading small").removeClass('hidden');
	$.get(SITE_URL + 'Admin/ajax', {type:"item_list", id:id}, function (data) {
		if (data['status']) {
			$("#alert_box").slideUp();
			PageList.table_item(data['data'], id);
		} else {
			PageList.alert("没有找到对应的子页面", "info");
			$("#alert_box").slideDown();
		}
		$("#page_list .panel-heading small").addClass('hidden');
	});
}

PageList.table_item = function (data, id) {
	var table_data = "";
	$.each(data, function (i, elem) {
		table_data += "<tr class='item_list_table'><td>&raquo;" + elem['id'] + "</td><td>" + elem['name'] + "</td><td>" + elem['title'] + "</td>" +
			"<td><a href=\"?id=" + elem['page_id'] + "\">页面</a>&nbsp;" +
			"<a href=\"" + SITE_URL + "Admin/item_edit?id=" + elem['id'] + "\">信息</a>&nbsp;" +
			"<a class='delete' href=\"#delete_item_" + elem['id'] + "\">删除</a></td>" +
			(i == 0 ? "<td class='close_td' rowspan='" + data.length + "'><a title='关闭子项目' class=\"close\">&times;</a></td>" : "") + "</tr>";
	});
	$("#view_project_item_" + id).parent().parent().after(table_data);
	$("#page_list table tbody a.delete").unbind('click').click(PageList.delete);
	$("#page_list .item_list_table a.close").click(function () {
		$("#page_list .item_list_table").remove();
	});
}
PageList.table_hidden = function () {
	$("#page_list table").addClass("hidden");
}
PageList.table_show = function () {
	$("#page_list table").removeClass("hidden");
}
PageList.error = function (content, type) {
	PageList.table_hidden();
	PageList.alert(content, type);
}
PageList.alert = function (content, type) {
	$("#alert_box").html("<div class='alert fade alert-" + type + " in'>" +
		"<a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a>" +
		"<p>" + content + "</p>" +
		"</div>");
}
PageList.delete = function () {
	var href = $(this).attr('href');
	var type = href.substr(8, 4);
	var id = href.substr(13);
	switch (type) {
		case "item":
			if (confirm("确定删除该子项目？\n同时删除对应文章页面.")) {
				$.post(SITE_URL + "Admin/ajax?type=item_delete", {id:id}, function (data) {
					if (data['status']) {
						alert("删除成功");
						PageList.form_set();
					} else {
						alert(data['error']);
					}
				});
			}
			break;
		case "proj":
			if (confirm("确定删除该项目？\n同时删除对应文章及其子项目全部页面信息.")) {
				$.post(SITE_URL + "Admin/ajax?type=project_delete", {id:id}, function (data) {
					if (data['status']) {
						alert("删除成功");
						PageList.form_set();
					} else {
						alert(data['error']);
					}
				});
			}
			break;
		default:
			alert("unknown");
	}
	return false;
}