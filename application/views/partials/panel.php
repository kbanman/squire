<div <?php echo HTML::attributes($attr); ?>>
	<hgroup>
		<?php if ( ! empty($title)): ?><h2><?php echo $title; ?></h2><?php endif; ?>
		<?php if (isset($buttons)): ?>
		<ul class="buttons">
			<?php foreach ($buttons as $text => $btn): ?>
			<li><a <?php echo HTML::attributes($btn); ?>><?php echo $text; ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</hgroup>
	<div class="panel-content">
		<?php echo $content; ?>
	</div>
</div>
