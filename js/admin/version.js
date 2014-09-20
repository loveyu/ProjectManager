var Version = {
	/**
	 * 添加一个新版本控制
	 * @returns {boolean}
	 */
	add_new_version: function () {
		Version.modal_show("添加新版本控制", $("#AddNewVersionBox").html());
		$("form").ajaxForm(function (data) {
			if (data.status) {
				location.reload();
			} else {
				alert(data.msg);
			}
			return false;
		});
		return false;
	}, add_version: function (data) {
		if (data.status) {
			alert("创建成功");
			location.href = location.href.substr(0, location.href.lastIndexOf('/'));
		} else {
			alert("创建失败:" + data.msg);
		}
		return false;
	}, edit_version: function (data) {
		if (data.status) {
			alert("更新成功");
			location.reload();
		} else {
			alert("更新失败:" + data.msg);
		}
		return false;
	}, version_del: function () {
		var id = $(this).attr("data");
		if (confirm("你确定的要删除该版本？")) {
			$.post("/Admin/ajax?type=version_del", {id: id}, function (data) {
				if (data.status) {
					alert("删除成功");
					location.reload();
				} else {
					alert(data.msg);
				}
			});
		}
	}, version_edit_info: function () {
		//编辑版本控制信息
		var url = $("a.v_download").attr("href");
		var i_obj = $("p.v_info");
		var info = "";
		if (i_obj.length) {
			info = i_obj.html();
		}
		console.log(info);
		Version.modal_show("修改版本细节信息", $("#VersionEditInfo").html(), {
			type: 'shown', call: function () {
				$("form input[name=url]").val(url);
				$("form textarea[name=info]").val(info);
			}
		});
		$("form").ajaxForm(function (data) {
			if (data.status) {
				alert("更新成功");
				location.reload();
			} else {
				alert(data.msg);
			}
			return false;
		});
	}, version_get_detail: function () {
		var id = $(this).attr("data");
		$.get("/Admin/ajax", {type: 'version_detail', id: id}, function (data) {
			var html = "<table class='table'>";
			for (var i in data) {
				if (i == "id")continue;
				var content = data[i];
				if (content === null)continue;
				if (i == "update_info" || i == "bugs") {
					content = content.replace(/\n/g, "</p><p>");
					if (content != "") {
						content = "<p>" + content + "</p>"
					}
				}
				html += "<tr><td style='width: 120px;text-align: right'>" + i + "</td><td>" + content + "</td></tr>";
			}
			html += "</table>";
			Version.modal_show("详细信息", html);
		});
		return false;
	}, modal_show: function (title, content, callback) {
		$("#commonModal").remove();
		$("body").append("<div id='commonModal' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='commonModalMyModalLabel' aria-hidden='true'>" +
		'<div class="modal-dialog">' + '<div class="modal-content">' + '<div class="modal-header">' +
		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
		'<h4 class= "modal-title" id="commonModalMyModalLabel" >' + title + '</h4 >' + '</div>	' +
		"<div class='modal-body'>" + content + "</div>" + '<div class="modal-footer">' +
		'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>' + "</div></div></div></div>");
		if ($.isArray(callback)) {
			for (var i = 0, l = callback.length; i < l; i++) {
				if (callback[i].hasOwnProperty('type') && callback[i].hasOwnProperty('call')) {
					$("#commonModal").on(callback[i]['type'] + ".bs.modal", callback[i]['call']);
				}
			}
		} else {
			if (typeof callback != 'undefined' && callback.hasOwnProperty('type') && callback.hasOwnProperty('call')) {
				$("#commonModal").on(callback['type'] + ".bs.modal", callback['call']);
			}
		}
		$('#commonModal').modal('show');
	}
};
jQuery(function ($) {
	$("#AddNewVersionType").click(Version.add_new_version);//添加新版本控制
	$("#VersionAddForm").ajaxForm(Version.add_version);//添加一个新版本
	$("#VersionEditForm").ajaxForm(Version.edit_version);//编辑版本控制信息
	$(".version_get_detail").click(Version.version_get_detail);//获取一个版本的详细信息
	$(".version_del").click(Version.version_del);//删除一个版本
	$(".version_edit_info").click(Version.version_edit_info);//编辑一个版本信息
});
