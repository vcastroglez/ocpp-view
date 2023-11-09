<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import MessagesViewer from "@/Components/CentralSystem/MessagesViewer.vue";
import useEventsBus from "@/eventBus.js";
import {reactive, watch} from "vue";
import TransactionsViewer from "@/Components/CentralSystem/TransactionsViewer.vue";

const {emit} = useEventsBus();

const props = defineProps({
	chargePointId: {
		type: Number,
	},
});

const state = reactive({
	tab: 'transactions'
})

watch(() => state.tab, (value) => {
	emit('cp-tab', value);
})

const triggerMessage = () => {
	const type = 2;//request 3 is for message
	const requestedMessage = window.prompt("Enter requested message, possible types:\n\nBootNotification, DiagnosticsStatusNotification, FirmwareStatusNotification, Heartbeat, MeterValues, StatusNotification. \nNOTE: MeterValues can only be triggered if a transaction is up.")
	const connectorId = window.prompt("Enter connector id, 1 by default") || 1;
	axios.post('/send-charge-point-msg/' + props.chargePointId, {type, requestedMessage, connectorId}).then(() => {
		setTimeout(() => {
			getMessages()
		}, 2000);
	});
}
const triggerGetMessages = () => {
	emit('get-messages', props.chargePointId);
}
</script>
<template>
	<Head title="Dashboard"/>

	<AuthenticatedLayout>
		<template #header>
			<div class="spread">
				<div>
					<h2 class="font-semibold text-xl text-gray-800 leading-tight">Charge Point: {{ chargePointId }}
					</h2>
				</div>
				<div>
					<div :class="{active: state.tab==='msgs'}" class="button mr-2" @click="state.tab='msgs'">Messages</div>
					<div :class="{active: state.tab==='transactions'}" class="button" @click="state.tab='transactions'">Transactions</div>
				</div>
				<div>
					<button class="button mr-2" @click="triggerMessage()">Trigger Message</button>
					<button class="button" @click="triggerGetMessages()">Refresh</button>
				</div>
			</div>
		</template>

		<MessagesViewer v-show="state.tab==='msgs'" :charge-point-id="chargePointId"></MessagesViewer>
		<TransactionsViewer v-show="state.tab==='transactions'" :charge-point-id="chargePointId"></TransactionsViewer>
	</AuthenticatedLayout>
</template>
<style scoped>
</style>