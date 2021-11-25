<script lang="ts">
	import Router from "./Router/Router.svelte";
	import Cookies from "js-cookie";
import { consumePrepender } from "./Storage/Toolbar";
	if (Cookies.get('LARABA_RANDOM') === undefined) {
		Cookies.set('LARABA_RANDOM', Math.random().toString(36).substr(2, 5));
	}

	//@ts-ignore
	if ('token' in window.__prefetched && !Cookies.get('LARABA-TOKEN')) {
		//@ts-ignore
		Cookies.set('LARABA-TOKEN', window.__prefetched.token);
	}

	//@ts-ignore
	if ('subscriptions' in window.__prefetched) {
		//@ts-ignore
		consumePrepender(window.__prefetched.subscriptions);
	}

	
</script>

<div class='container-fluid px-0 root night'>
	<Router/>
</div>