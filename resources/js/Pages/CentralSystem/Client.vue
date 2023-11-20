<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import TransactionsTable from "@/Components/CentralSystem/TransactionsTable.vue";

const props = defineProps({
	transactions: {
		type: Array,
	},
	client: {
		type: Array,
	},
});
</script>
<template>
	<Head title="Client transactions"/>

	<AuthenticatedLayout>
		<template #header>
			<div class="spread">
				<div>
					<h2 class="font-semibold text-xl text-gray-800 leading-tight">Client: {{ client.uuid }}
					</h2>
				</div>
				<div>
					<button class="button" @click="window.location.reload()">Refresh</button>
				</div>
			</div>
		</template>
		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white">
					<TransactionsTable :show-client="false" :transactions="transactions"></TransactionsTable>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
<style scoped>
</style>