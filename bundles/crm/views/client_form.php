<form <?php echo array_to_attr($form_attr); ?>>
	<?php foreach ($fieldsets as $name => $form): ?>
	<fieldset class="<?php echo $name; ?>">
		<?php echo $form->build(); ?>
	</fieldset>
	<?php endforeach; ?>
</form>