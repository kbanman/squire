<script type="text/javascript">
var Sq = {
	base_url: '{{ URL::to('/') }}',
	site_url: function(uri) { return this.base_url + uri; },
	uri: '{{ URI::current() }}',
	dialog_callbacks: [],
	widgets: []
};
</script>