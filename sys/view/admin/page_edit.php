<h3>编辑文章</h3>

<div id="alert_box"></div>
<form id="page_edit" method="post" role="form" action="<?php echo get_url(array(
	'Admin',
	'ajax'
), '?type=page_edit&id=' . (req()->_plain()->get('id') + 0));?>">
	<div class="form-group">
		<label for="InputTitle">页面标题</label>
		<input value="" type="text" name="title" class="form-control" id="InputTitle">
	</div>
	<div class="form-inline">
		<div class="form-group">
			<label for="InputTime">创建时间</label>
			<input name="time" value="" type="datetime" class="form-control" id="InputTime">
		</div>
		<div class="form-group">
			<label for="InputView">访问量</label>
			<input name="view" value="" type="number" class="form-control" id="InputView">
		</div>
		<div class="form-group">
			<label for="InputType">页面类型</label>
			<input name="type" value="" type="text" class="form-control" id="InputType">
		</div>
		<div class="form-group">
			<label for="InputStatus">状态</label>
			<select class="form-control" id="InputStatus" name="status" style="width: 100%;"></select>
		</div>
	</div>

	<div class="input-group" style="margin-top: 10px;">
		<span class="input-group-addon">关键字</span>
		<input value="" type="text" name="keywords" class="form-control" id="InputKeywords">
	</div>

	<div class="editor">
		<textarea class="form-control" rows="3" id="editor" style="height: 500px;" name="content"></textarea>
	</div>
	<button type="submit" class="form-control btn btn-primary">更新文章</button>
</form>
<script type="text/javascript">
	var editor;
	KindEditor.ready(function (K) {
		editor = K.create('#editor', {
			allowFileManager:true,
			designMode:false,
			width:'100%',
			afterCreate:function () {
				PageEdit.load(<?php echo req()->_plain()->get('id') + 0?>);
			}
		});
	});
</script>
