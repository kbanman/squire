<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Clients</a>
	<ul class="dropdown-menu">
		<li>
			<a href="<?php echo URL::to('clients'); ?>" title="Browse Clients">Browse Clients</a>
		</li>
		<li>
			<a href="<?php echo URL::to('communications'); ?>">Communications</a>
		</li>
	</ul>
</li>
<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Leads</a>
	<ul class="dropdown-menu">
		<li>
			<a href="<?php echo URL::to('leads'); ?>" title="Browse Leads">Browse Leads</a>			
		</li>
		<li>
			<a href="<?php echo URL::to('leads/submit'); ?>">Submit Lead</a>
		</li>
		<li>
			<a href="<?php echo URL::to('leads/map'); ?>">Map</a>
		</li>
	</ul>
</li>
