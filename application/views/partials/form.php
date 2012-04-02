<form <?php echo HTML::attributes($attr); ?>>
	<fieldset>
	<?php if (! empty($legend)): ?>
		<legend><?php echo $legend; ?></legend>
	<?php endif;
	foreach ($fields as $field): 
		if (is_null($field['label']))
		{
			echo $field['field'];
			continue;
		}
	?>
		<div class="control-group">
			<?php echo $field['label']; ?>
			<div class="controls">
				<?php echo $field['field']; ?>
			</div>
		</div>
	<?php endforeach; 
	if (empty($modal)):
	?>
		<div class="form-actions">
			<?php echo $buttons; ?>
		</div>
	<?php endif; ?>
	</fieldset>
</form>