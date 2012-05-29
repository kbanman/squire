<script type="text/javascript">
var Sq = {
	base_url: '<?php echo URL::to('/'); ?>',
	site_url: function(uri) { return this.base_url + uri; },
	uri: '<?php echo URI::current(); ?>'
};
</script>