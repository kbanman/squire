<div id="panel_leads_view_details" class="lead_detail" data-clientid="<?php echo $lead->id; ?>">
	<i class="icon-arrow-left indicator_left"></i>
	<hgroup>
		<h2 id="leads_detail_name">Lead Details</h2>
		<a id="btn_leads_detail_edit" class="btn btn-mini">Edit</a>
		<a id="btn_leads_detail_delete" class="btn btn-mini btn-danger">Delete</a>
		<a id="btn_new_appointment" class="btn btn-mini">New Appointment</a>
	</hgroup>
	<table class="table table-condensed">
	 	<tr>
			<th scope="row">Name</th>
			<td id="client_name"><?php echo $lead->business_name ?></td>
		</tr>
		<tr>
			<th scope="row">Address</th>
			<td><a target="_blank" href="http://maps.google.com/maps?q=<?php echo urlencode($lead->address_street.' '.$lead->address_city.' '.$lead->address_province) ?>"><span id="address_street"><?php echo $lead->address_street ?></span><br><span id="address_postalcode"><?php echo $lead->address_postalcode ?></span></a></td>
		</tr>
		<?php if (!empty($lead->email)): ?><tr>
			<th scope="row">Email</th>
			<td><?php echo $lead->email ?></td>
		</tr><?php endif; ?>
		<tr>
			<th scope="row">Phone</th>
			<td><span id="phone_main"><?php echo $lead->phone_main ?></span></td>
		</tr>
		<?php if (!empty($lead->phone_other)): ?><tr>
			<th scope="row">Other Phone</th>
			<td><?php echo $lead->phone_other ?></td>
		</tr><?php endif; if (!empty($lead->notes)): ?><tr>
			<th scope="row">Notes</th>
			<td><?php echo nl2br($lead->notes) ?></td>
		</tr><?php endif; ?>
		<tr>
			<th scope="row">Type</th>
			<td><?php echo $lead->type ?></td>
		</tr>
		<tr>
			<th scope="row">Status</th>
			<td><?php echo $lead->status ?></td>
		</tr>
		<?php if (!empty($lead->grade_id)): ?><tr>
			<th scope="row">Grade</th>
			<td><?php echo $lead->grade ?></td>
		</tr><?php endif; ?>
	</table>
	<div id="detailview_map"></div>
	<span class="bottombuttons">
		<a class="button" id="btn_log_call">Log Call</a>
		<a class="button" id="btn_wp_lookup">WhitePages Lookup</a>
		<?php if ($lead->jobkey): ?><a class="button blue" target="_blank" href="https://cpower.collegepro.com/Leads/JobDetail.aspx?JobKey=<?php echo $lead->jobkey ?>">View in CPOWER</a>
		<?php else: ?><a class="button" id="btn_savetocpower">Save to CPOWER</a>
		<?php endif; ?>
	</span>
	<?php //if ($apps = $lead->getAppointments()): ?>
	<br />
	<h3>Appointments</h3>
	<table class="nohighlight">
	 	<tr>
			<th>Date</th>
			<th>Type</th>
		</tr>
	<?php //foreach ($apps as $app): ?>
		<tr>
			<td><?php //echo date('d-M g:i a', $app->timestamp_scheduled) ?></td>
			<td><?php //echo $app->flexLabel('type') ?></td>
		</tr>
	<?php // endforeach; ?>
	</table>
	<?php // endif; if ($calls = $lead->getCalls()): ?>
	<br />
	<h3>Calls</h3>
	<table class="nohighlight">
	<?php // foreach ($calls as $call): ?>
	 	<tr>
			<th align="left"><?php //echo date('d-M g:i a', $call->timestamp_called) ?></th>
		</tr>
		<tr>
			<td><?php //echo $call->left_message? '(Left Message)' : '' ?> <?php //echo $call->notes ?></td>
		</tr>
	<?php //endforeach; ?>
	</table>
	<?php //endif; ?>
</div>