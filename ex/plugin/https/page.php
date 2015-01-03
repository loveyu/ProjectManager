<div>
	<form class="form" method="post">
		<div class="form-group">
			<label class="control-label">模式选择</label>

			<div class="form-control">
				<?php
				$mode = cfg()->get('s_g', 'https', 'mode');
				foreach(['share' => '共享模式','focus'=>'强制HTTPS','none'=>'关闭HTTPS'] as $name => $value):
					$checked = ($name==$mode)?"checked":"";
					?>
					<label class="radio-inline">
						<input type="radio" name="mode" id="r_<?php echo $name?>" value="<?php echo $name?>"<?php echo $checked?>> <?php echo $value?>
					</label>
				<?php endforeach;?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">转换地址</label>
		</div>
		<div class="form-group">
			<label class="control-label" for="https_cdn">HTTPS 文件 CDN
				<small class="text-warning">(eg. https://static.xx.xx/xx/)</small>
			</label>
			<input class="form-control" value="<?php echo cfg()->get('s_g','https','https')?>" name="https" id="https_cdn" type="url">
		</div>
		<div class="form-group">
			<label class="control-label" for="http_cdn">HTTP 文件 CDN
				<small class="text-warning">(eg. http://static.xx.xx/xx/)</small>
			</label>
			<input class="form-control" value="<?php echo cfg()->get('s_g','https','http')?>" name="http" id="http_cdn" type="url">
		</div>
		<div class="form-group">
			<label class="control-label" for="rs_path">资源文件路径
				<small class="text-warning">(eg. /image/)</small>
			</label>
			<input class="form-control" name="path" value="<?php echo cfg()->get('s_g','https','path')?>" id="rs_path" type="text">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-warning">更新</button>
		</div>
	</form>
</div>