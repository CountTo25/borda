<script lang="ts">
	import Router from "./Router/Router.svelte";
	import Cookies from "js-cookie";
	if (Cookies.get('LARABA_RANDOM') === undefined) {
		Cookies.set('LARABA_RANDOM', Math.random().toString(36).substr(2, 5));
	}
	
</script>

<div class='container-fluid px-0 root night'>
	<Router/>
</div>