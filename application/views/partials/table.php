<table class="table table-striped <?php echo $class; ?>">
	<thead>
		<tr <?php echo HTML::attributes($header_attributes); ?>>
			<?php foreach ($columns as $col => $label): ?>
			<th data-property="<?php echo $col; ?>" <?php echo HTML::attributes($header_cell_attributes($col)); ?>>
				<?php echo $label; ?>
			</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rows as $row): ?>
		<tr <?php echo HTML::attributes($row_attributes($row)); ?>>
			<?php foreach ($columns as $col => $label): ?>
			<td <?php echo HTML::attributes($cell_attributes($col, $row)); ?>>
				<?php echo $cell_value($col, $row); ?>
			</td>
			<?php endforeach; ?>
		</tr><?php endforeach; if ( ! count($rows)): ?>
		<tr class="norecords nohighlight">
			<td colspan="<?php echo count($columns); ?>"><?php echo $norecords_message; ?></td>
		</tr><?php endif; ?>
	</tbody>
</table>