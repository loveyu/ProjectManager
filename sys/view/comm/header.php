<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php pm_title()?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo get_file_url('css/bootstrap.min.css');?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo get_file_url('css/style.css');?>?v=20160827"/>
	<script src="<?php echo get_file_url('js/jquery.min.js')?>" type="text/javascript"></script>
	<script async src="<?php echo get_file_url('js/bootstrap.min.js')?>" type="text/javascript"></script>
	<?php pm_header();?>

	<!--[if lt IE 9]>
	<script async src="<?php echo get_file_url('js/ie8-responsive-file-warning.js');?>"></scriptasync>
	<script async src="<?php echo get_file_url('js/html5shiv.min.js');?>"></script>
	<script async src="<?php echo get_file_url('js/respond.min.js');?>"></script>
	<![endif]-->
</head>
<body>
<header class="navbar navbar-default">
	<nav class="container" role="navigation">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo get_url();?>" title="返回 <?php site_title()?> 首页"><?php site_name()?></a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php if(menu()){
				menu()->out();
			}?>
			</ul>
		</div>
	</nav>
</header>
<div class="container">
	<!--顶部结束-->
