<script setup>
import {reactive, watch} from "vue";
import useEventsBus from "@/eventBus.js";
import useUtils from "@/functions.js";

const {humanDate} = useUtils()

const props = defineProps({
	chargePointId: {
		type: Number
	}
})

const {bus} = useEventsBus();

const state = reactive({
	transactions: []
})

watch(() => bus.value.get('get-messages'), (payload) => {
	const [cpId] = payload;
	if (cpId === props.chargePointId) {
		getTransactions();
	}
})

const getTransactions = () => {
	axios.get(`/get-transactions/${props.chargePointId}`).then(response => {
		state.transactions = response.data.payload;
	})
}

getTransactions();
</script>

<template>
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white">
				<table class="table border w-full">
					<thead>
					<tr>
						<th>UUID</th>
						<th>Meter Start</th>
						<th>Meter Stop</th>
						<th>Created at</th>
						<th>Last update</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="transaction in state.transactions" :key="transaction.id">
						<td>{{ transaction.transaction_uuid }}</td>
						<td>{{ transaction.meter_start }}</td>
						<td>{{ transaction.meter_stop || "unknown" }}</td>
						<td>{{ humanDate(transaction.created_at) }}</td>
						<td>{{ humanDate(transaction.updated_at) }}</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</template>

<style scoped>

</style>