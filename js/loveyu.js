$(function () {
	//给外站连接加上新窗口打开
	$("a").each(function (id, elem) {
		var parse = elem.href.match(/^(([a-z]+):\/\/)?([^\/\?#]+)\/*([^\?#]*)\??([^#]*)#?(\w*)$/i);
		if ((parse != null && parse.length > 3 && parse[3] != location.hostname)
			|| ($(elem).attr("rel") + "").toLowerCase() == "external") {
			$(elem).attr("target", "_blank");
		}
	});
	if (typeof(PM_PAGE_ID) != 'undefined' && PM_PAGE_ID > 0) {
		//对可以访问的文章进行访问统计
		$.post(SITE_URL + "Home/page_view", {id:PM_PAGE_ID}, function (data) {
			if (typeof(data['view']) != 'undefined') {
				//统计存在的操作
			}
		});
	}
	if (typeof(PM_USER_LOGIN_STATUS) != 'undefined' && PM_USER_LOGIN_STATUS) {
		//当用户登录时的操作
	} else {
		//非登录用户的操作
	}
});