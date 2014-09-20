<?php
/**
 * @var array $__list
 */
if(empty($__list)): ?>
	<p>当前无任何历史版本信息</p>
<?php
else:
	foreach($__list as $v):?><p>Version:<?php echo $v['version'] ?> : <a href="<?php
		echo $v['download_url'] ?>" rel="external"><?php echo $v['download_url'] ?></a></p><?php
	endforeach;endif;