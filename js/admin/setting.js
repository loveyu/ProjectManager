Setting = function () {
};
Setting.now_page = 20;

Setting.set_data = function (data) {
	$("#setting_list").html("");
	$.each(data, function (i, elem) {
		$("#setting_list").append('<div class="form-group">	' +
			'<label for="__InputSetting_' + elem['name'] + '" class="col-sm-2 control-label">' + Setting.name_map(elem['name']) + '：</label>' +
			'<div class="col-sm-9">' +
			((elem['value'].length > 100) ?
				'<textarea class="form-control" rows="3" id="__InputSetting_' + elem['name'] + '" name="' + elem['name'] + '">' + Setting.html_encode(elem['value']) + '</textarea>'
				: '<input type="text" class="form-control" id="__InputSetting_' + elem['name'] + '" value="' + Setting.html_encode(elem['value']) + '" name="' + elem['name'] + '">' ) +
			'</div>' +
			'<div class="col-sm-1">' +
			'<button type="button" class="btn btn-info ">更新</button>' +
			'</div>' +
			'</div>');
	});
	$("#setting_list button").click(function () {
		var id = $(this).parent().parent().children("label").attr('for');
		Setting.up_data(id, $("#" + id).attr("name"), $("#" + id).val());
	});
};
Setting.name_map = function (name) {
	var map = {site_name:"网站名称", site_title:"网站标题", site_desc:"网站描述"};
	if (map[name] != undefined)return map[name];
	return name;
};
Setting.up_data = function (id, name, value) {
	$.post(SITE_URL + "Admin/ajax?type=setting_edit", {name:name, value:value}, function (data) {
		if (data['status']) {
			$("#" + id).parent().addClass("has-success");
			$("#" + id).parent().children("button").html("成功");
			setTimeout('$("#' + id + '").parent().removeClass("has-success")', 3000);
			setTimeout('$("#' + id + '").parent().children("button").html("更新")', 3000)
		} else {
			$("#" + id).parent().addClass("has-error");
			setTimeout('$("#' + id + '").parent().removeClass("has-error")', 3000);
			Setting.alert(Setting.name_map(name) + "：更新失败<br>错误信息：" + data['error'], 'danger');
		}
	});
};
Setting.alert = function (content, type) {
	$("#alert_box").html("<div class='alert fade alert-" + type + " in'>" +
		"<a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a>" +
		"<p>" + content + "</p>" +
		"</div>");
	setTimeout('$(".alert").alert("close");', 5000);
};

Setting.pagination = function (count, now, number) {
	Setting.now_page = now;
	$(".pagination").html("");
	$(".pagination").removeClass("hidden");
	var all = Math.ceil(count / number);
	$(".pagination").append('<li' + ((now == 1) ? ' class="disabled"' : '') + '><a href="#page_' + (now - 1) + '">&laquo;</a></li>');
	for (var i = 1; i <= all; i++) {
		if (i == now) {
			$(" .pagination").append("<li class=\"active\"><a href=\"#page_" + i + "\">" + i + "</a></li>");
		} else {
			$(".pagination").append("<li><a href='#page_" + i + "'>" + i + "</a></li>");
		}
	}
	$(".pagination").append('<li' + ((now == all) ? ' class="disabled"' : '') + '><a href="#page_' + (now + 1) + '">&raquo;</a></li>');
	$(".pagination li a").click(function () {
		var page = ($(this).attr('href') + "").substr(6);
		if (page > 0 && page <= all) {
			Setting.load(page, Setting.form_number());
		}
		return false;
	});
}

Setting.form_set = function () {
	Setting.load(Setting.now_page, Setting.form_number());
}

Setting.load = function (page, number) {
	$.post(SITE_URL + "Admin/ajax?type=setting_get", {number:number, page:page}, function (data) {
		if (data['status']) {
			Setting.set_data(data['data']);
		} else {
			Setting.alert("没有找到数据", 'danger');
		}
		Setting.pagination(data['count'], page, number);
	})
}

Setting.form_number = function () {
	return $("form input[name='number']").val();
}
Setting.html_encode = function (str) {
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

$(function () {
	Setting.load(1, Setting.now_page);
	$("form button").click(Setting.form_set);
});