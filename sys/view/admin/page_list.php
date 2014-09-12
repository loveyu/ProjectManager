<div id="page_list" class="panel panel-default">
	<div class="panel-heading">项目列表
		<small class="text-info">&nbsp;加载中..</small>
	</div>
	<div class="panel-body">
		<p>在这里选择所需的项目，点击之后展开项目列表。</p>

		<div id="alert_box"></div>
	</div>
	<table class="table table-hover hidden">
		<thead>
		<tr>
			<th width="10%">ID</th>
			<th width="20%">名称</th>
			<th width="40%">标题</th>
			<th width="20%">编辑操作</th>
			<th width="10%">子项目</th>
		</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<div class="panel">
		<ul class="pagination hidden"></ul>
		<form class="form-horizontal" onclick="return false;" role="form">
			<div class="form-group">
				<label for="InputNumber" class="col-sm-2 control-label">每页显示</label>

				<div class="col-sm-2">
					<input type="number" class="form-control" id="InputNumber" value="20" placeholder="数量" name="number">
				</div>
				<div class="col-sm-1">
					<button type="submit" class="btn btn-info ">设定</button>
				</div>
			</div>
		</form>
	</div>
</div>