<script setup>
import useUtils from "@/functions.js";

const {humanDate, goto} = useUtils();

defineProps({
	transactions: {
		type: Array
	},
	showClient: {
		type: Boolean,
		default: true
	}
})
</script>

<template>
	<table class="table border w-full">
		<thead>
		<tr>
			<th>UUID</th>
			<th>Meter Start</th>
			<th>Meter Stop</th>
			<th v-if="showClient">Client</th>
			<th>Created at</th>
			<th>Last update</th>
		</tr>
		</thead>
		<tbody>
		<tr v-for="transaction in transactions" :key="transaction.id">
			<td>{{ transaction.transaction_uuid }}</td>
			<td>{{ transaction.meter_start }}</td>
			<td>{{ transaction.meter_stop || "unknown" }}</td>
			<td @click="goto(`/client-transactions/${transaction.client?.id}`)" v-if="showClient">
				{{ transaction.client?.uuid || "No client" }}
			</td>
			<td>{{ humanDate(transaction.created_at) }}</td>
			<td>{{ humanDate(transaction.updated_at) }}</td>
		</tr>
		</tbody>
	</table>
</template>

<style scoped>

</style>