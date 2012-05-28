<section class="maincontent panelgroup clearfix">
		<hgroup>
			<h1>View Leads</h1>
		</hgroup>
		<input type="hidden" id="franchise_location" value="<? //$this->franchise->city.' '.$this->franchise->province ?>" />
		<article class="ovPanel halfwidth span8" id="panel_leads_view">
			<?= $clients ?>
		</article>
		<article class="span4" id="panel_leads_view_details">
			<h3 style="margin-top:.7em;">Select a lead for more details</h3>
		</article>
	</section>
