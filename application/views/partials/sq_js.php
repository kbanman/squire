<script type="text/javascript">
function empty(v) {
	var k;
	if (v === '' || v === 0 || v === '0' || v === null || v === false || typeof v === 'undefined') {
		return true;
	}
	if (typeof v == 'object') {
		for (k in v) { return false; }
		return true;
	}
	return false;
}

var Sq = {
	base_url: '<?php echo $base_url; ?>',
	site_url: function(uri) { return this.base_url + uri; },
	entities: <?php echo json_encode($entities); ?>
};
</script>