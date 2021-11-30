<script lang="ts">
	import Router from "./Router/Router.svelte";
	import Cookies from "js-cookie";
	import { consumePrepender } from "./Storage/Toolbar";
	
	if (Cookies.get('LARABA_RANDOM') === undefined) {
		Cookies.set('LARABA_RANDOM', Math.random().toString(36).substr(2, 5));
	}
	console.log(Cookies.get('LARABA-TOKEN'));


	//@ts-ignore
	window.___cookies = Cookies;

	//@ts-ignore
	consumePrepender(window.__prefetched.subscriptions ?? [], Cookies.get('LARABA-TOKEN'));




	
</script>

<div class='container-fluid px-0 root night'>
	<Router/>
</div>