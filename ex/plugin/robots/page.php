<form action="" method="post">
	<textarea name="robots" rows="6" class="form-control"><?php echo strip_tags(@file_get_contents(_BasePath_."/robots.txt"))?></textarea>
	<button class="btn btn-danger" style="margin-top: 5px;" type="submit">更新</button>
</form>
