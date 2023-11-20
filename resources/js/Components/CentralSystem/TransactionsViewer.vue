<script setup>
import {reactive, watch} from "vue";
import useEventsBus from "@/eventBus.js";
import useUtils from "@/functions.js";
import TransactionsTable from "@/Components/CentralSystem/TransactionsTable.vue";

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
				<TransactionsTable :transactions="state.transactions"></TransactionsTable>
			</div>
		</div>
	</div>

</template>

<style scoped>

</style>