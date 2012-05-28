<section class="maincontent panelgroup clearfix">
	<hgroup>
		<h1>Submit Leads</h1>
	</hgroup>
	<div class="alert alert-error" id="leads_error_message" style=" display:none">
		Error message goes here
    </div>
	
	<!-- style="background:none; border: none;"-->
	<table class="vertical" id="submission_metadata" style="float:left">
		<tbody>
			<tr>
				<th align="right" width="50" style="background:none; border: none; color: #414141;">Date:</th>
				<td>
					<input type="text" id="submission_date" class="input-medium" />
				</td>
			</tr>
			<tr>
				<th align="right" style="background:none; border: none; color: #414141;">Time:</th>
				<td>
					<select id="time_start_hour" class="input-mini" >
						<!--<?php for($i=1; $i<=12; $i++): ?>
						<option value="<?= $i ?>">
						<?= $i ?>
						</option>
						<?php endfor; ?>-->
					</select>:<select id="time_start_minute" class="input-mini" >
						<!--<option value="0">00</option>
						<option value="15">15</option>
						<option value="30">30</option>
						<option value="45">45</option>-->
					</select>
					<select name="time_start_ampm" id="time_start_ampm" class="input-mini" >
						<option value="am">AM</option>
						<option selected="selected" value="pm">PM</option>
					</select>
					to
					<select id="time_end_hour" class="input-mini" >
						<!--<?php for($i=1; $i<=12; $i++): ?>
						<option value="<?= $i ?>">
						<?= $i ?>
						</option>
						<?php endfor; ?>-->
					</select>:<select id="time_end_minute" class="input-mini" >
						<!--<option value="0">00</option>
						<option value="15">15</option>
						<option value="30">30</option>
						<option value="45">45</option>-->
					</select>
					<select name="time_end_ampm" id="time_end_ampm" class="input-mini" >
						<option value="am">AM</option>
						<option selected="selected" value="pm">PM</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<br clear="all" />
	<article class="ovPanel halfwidth" id="panel_recent_leads" >
		<h2>Leads</h2>
		<div class="leadform first">
		<form>
			<table class="nobanding nohighlight">
				<tbody>
					<tr>
						<td class="labelcell">Name:</td>
						<td class="namecell">
							<input type="text" placeholder="First" name="firstname" style="border-right: medium none;"><input type="text" placeholder="Last" name="lastname">
						</td>
						<td class="labelcell">Phone:</td>
						<td>
							<input type="text" name="phone">
						</td>
						<td class="labelcell">Address:</td>
						<td>
							<input type="text" name="address">
						</td>
						<td class="labelcell">Postal Code:</td>
						<td>
							<select name="postalcode" style="width:57px;">
								<option value="V4N">V4N</option>
								<option value="V2T">V2T</option>
								<?// foreach ($this->franchise->getPostalCodes() as $postalcode): ?>
<!--								<option value="<?// $postalcode ?>"><?// $postalcode ?></option>-->
								<?// endforeach; ?>
							</select><input type="text" name="postalcode2" style="width: 30px;">
						</td>
					</tr>
					<tr>
						<td class="labelcell">Notes:</td>
						<td class="notescell">
							<input type="text" name="notes">
						</td>
						<td class="labelcell">Other Phone:</td>
						<td>
							<input type="text" name="phone_other">
						</td>
						<td class="labelcell">Email:</td>
						<td>
							<input type="text" name="email">
						</td>
						<td class="labelcell">Type:</td>
						<td>
							<select style="width: 92px;" name="jobtype">
								<option value="ext">Exterior</option>
								<option value="int">Interior</option>
								<option value="int_ext">Interior &amp; Exterior</option>
							<? (Crm\Flexfields::makeOptions('job_type'))?>
							
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<button id="btn_submit_leads" class="btn pull-right">Submit</button>
	</article>
</section>
