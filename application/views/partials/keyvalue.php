<div class="row">
	<div class="well keyvalue span12">
	<?php foreach ($object as $key => $value): ?>
		<div <?php echo HTML::attributes($row_attributes($object, $key)); ?>>
			<span class="key"><?php echo $row_label($object, $key); ?></span>
			<span class="value"><?php echo $row_value($object, $key); ?></span>
		</div>
	<?php endforeach; ?>
		<div style="clear:both;"></div>
	</div>
</div>