<div id="login">
	<h3 class="title">用户登录</h3>

	<form method="post" class="form-horizontal" action="<?php echo get_url('Home', 'post')?>" role="form">
		<div class="form-group">
			<label for="inputUser" class="col-sm-2 control-label">用户名</label>

			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputUser" name="user" placeholder="输入用户名">
			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword" class="col-sm-2 control-label">密码</label>

			<div class="col-sm-10">
				<input type="password" class="form-control" id="inputPassword" name="password" placeholder="输入密码">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox">
					<label>
						<input name="remember" type="checkbox"> 记住登录状态
					</label>
				</div>
			</div>
		</div>
		<?php hook()->apply('Home_login_page', null);?>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">登录</button>
			</div>
		</div>
	</form>
</div>