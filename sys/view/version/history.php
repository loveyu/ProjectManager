<?php
/**
 * @var array $__list
 */
if(empty($__list)): ?>
	<p>当前无任何历史版本信息</p>
<?php
else:
	foreach($__list as $v):?><p><?php echo date("Y年m月d日",$v['time']); ?><br />Version:<strong><?php echo $v['version']?></strong> : <a href="<?php
		echo $v['download_url'] ?>" rel="external"><?php echo $v['download_url'] ?></a></p><?php
	endforeach;endif;