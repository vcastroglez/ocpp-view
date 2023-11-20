<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import Clients from '../Components/CentralSystem/Clients.vue';
import useEventsBus from "@/eventBus.js";

const {emit} = useEventsBus();
const triggerGetClients = () => {
	emit('get-clients');
}
</script>
<template>
	<Head title="Clients"></Head>

	<AuthenticatedLayout>
		<template #header>
			<div class="spread">
				<div>
					<h2 class="font-semibold text-xl text-gray-800 leading-tight">Clients</h2>
				</div>
				<div>
					<button class="button" @click="triggerGetClients()">Refresh</button>
				</div>
			</div>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
					<div class="p-6 text-gray-900">
						<Clients></Clients>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>