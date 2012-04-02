<div <?php echo HTML::attributes($attr); ?>>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3><?php echo $title; ?></h3>
	</div>
	<div class="modal-body">
		<?php echo $content; ?>
	</div>
	<div class="modal-footer">
		<?php foreach ($buttons as $text => $attr): ?>
		<a <?php echo HTML::attributes($attr); ?>><?php echo $text; ?></a>
		<?php endforeach; ?>
	</div>
</div>