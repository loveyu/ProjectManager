<?php
/**
 * @var string $db            选择的数据库名称
 * @var bool   $has_tmp_table 临时数据库状态
 * @var int    $db_count      数据总数
 * @var int    $tmp_count     临时数据总数
 */
$ajax_url = get_url('Admin', 'ajax') . "?type=plugin&name=" . RecordCounter::name . "&db=" . $db;
?>
<div class="well-sm well">
	<p>当前选择数据库：<span class="text-danger"><?php echo $db ?></span>，数据总数为 : <?php echo $db_count?>, 临时数据为 : <?php echo $tmp_count?></p>

	<p>
		临时数据库状态：<?php echo ($has_tmp_table) ? "<a class='label label-success' data-action='tmp_delete' href='#'>存在,点击删除</a>" : "<a data-action='tmp_create' href='#' class='label label-danger'>不存在，点击创建</a>" ?></p>

	<p>每日用户数据：<a href="#" class="label label-info" data-action="day_output">导出</a></p>

	<p>从临时表新用户每日数据：<a href="#" class="label label-info" data-action="tmp_day_output">导出</a></p>

	<p>临时表手机品牌统计：<a href="#" class="label label-info" data-action="tmp_phone_output">导出</a></p>

	<p>临时表手机型号统计：<a href="#" class="label label-info" data-action="tmp_phone_model_output">导出</a></p>

	<p>临时表安卓版本统计：<a href="#" class="label label-info" data-action="tmp_android_version">导出</a></p>

	<p>临时表分辨率版本统计：<a href="#" class="label label-info" data-action="tmp_width_height">导出</a></p>

	<p>用户使用次数排行：<a href="#" class="label-info label" data-action="most_frequently_used">导出</a></p>

	<p>用户使用次数统计：<a href="#" class="label-info label" data-action="count_used">导出</a></p>

</div>
<script>
	jQuery(function ($) {
		var ajax_url = <?php echo json_encode($ajax_url)?>;
		var count_action = function (action, obj) {
			switch (action) {
				case "tmp_day_output":
				case "day_output":
				case "tmp_phone_output":
				case "tmp_phone_model_output":
				case "most_frequently_used":
				case "tmp_android_version":
				case "tmp_width_height":
				case "count_used":
					window.open(ajax_url + "&action=" + action);
					break;
				case "tmp_create":
					$.post(ajax_url, {action: action}, function (data) {
						if (data.status) {
							obj.html("已创建，点击删除");
							obj.removeClass("label-danger").addClass("label-success");
							obj.data("action", "tmp_delete");
						} else {
							alert("创建失败");
						}
					});
					break;
				case "tmp_delete":
					$.post(ajax_url, {action: action}, function (data) {
						if (data.status) {
							obj.html("已删除，点击创建");
							obj.removeClass("label-success").addClass("label-danger");
							obj.data("action", "tmp_create");
						} else {
							alert("删除失败");
						}
					});
					break;
				default:
					console.error("unknown action!");
					break;
			}
		};
		$("a").click(function () {
			var href = $(this).attr('href');
			var action = $(this).data('action');
			if (href == '#' && action != "") {
				count_action(action, $(this));
				return false;
			}
			return true;
		});
	});
</script>