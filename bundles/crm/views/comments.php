<div id="<?php echo $id; ?>" class="panel span6" data-uri="<?php echo $uri; ?>">
	<h2>Comments</h2>
	<?php if (count($comments)):
	foreach ($comments as $comment): ?>
		<div class="comment">
			<div class="meta">
				<span class="author"><?php echo $comment->user->name; ?></span>
				<span class="date"><?php echo DateTime::createFromFormat(PG_TIMESTAMP, $comment->created_at)->format('d-M-Y g:i a'); ?></span>
			</div>
			<div class="body">
				<?php echo nl2br($comment->comment); ?>
			</div>
		</div>
	<?php endforeach; 
	else: ?>
	<p>No comments</p>
	<?php endif; ?>
	<hr style="margin: 8px 0;"/>
	<form class="form form-horizontal crm-comment-form" id="customer_comment_form" action="<?php echo URL::to('comments/comment'); ?>" method="post">
		<fieldset>
			<?php echo Form::hidden('redirect_uri', URI::current()); ?>
			<?php echo Form::hidden('client_id', $client->id); ?>
			<textarea name="comment" rows="2" class="span6" style="margin-bottom:1em"></textarea>
			<?php echo Form::submit('Save Comment', array('class' => 'btn')); ?>
		</fieldset>
	</form>
</div>