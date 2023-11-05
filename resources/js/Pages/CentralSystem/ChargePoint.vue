<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import PayloadViewer from "@/Components/CentralSystem/PayloadViewer.vue";
import {reactive} from "vue";

const props = defineProps({
	chargePointId: {
		type: Number,
	}
});
const state = reactive({
	messages: []
})
const getMessages = () => {
	state.messages = [];
	axios.get(`/get-messages/${props.chargePointId}`).then(response => {
		state.messages = response.data.payload;
	})
}
getMessages();

const triggerMessage = () => {
	const type = 2;//request 3 is for message
	const requestedMessage = window.prompt("Enter requested message, possible types:\n\nBootNotification, DiagnosticsStatusNotification, FirmwareStatusNotification, Heartbeat, MeterValues, StatusNotification. \nNOTE: MeterValues can only be triggered if a transaction is up.")
	const connectorId = window.prompt("Enter connector id, 1 by default") || 1;
	axios.post('/send-charge-point-msg/'+props.chargePointId,{type,requestedMessage,connectorId}).then(()=>{
		setTimeout(()=>{
			getMessages()
		},2000);
	});
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
					<button class="button mr-2" @click="triggerMessage()">Trigger Message</button>
					<button class="button" @click="getMessages">Refresh</button>
				</div>
			</div>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white">
					<div class="p-6 text-gray-900" v-for="message in state.messages" :key="message.id">
						<div class="mb-4" style="border-bottom: 1px dashed rgb(128,128,128)">
							<b style="color: #5269c0">{{ message.type }}</b>
						</div>
						<div>
							<PayloadViewer :payload="message.payload"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
<style scoped>
.button {
	color: black;
	border: 1px solid black;
	padding: 5px 10px;
	border-radius: 5px;
}

.button:hover {
	border: 1px solid rgb(128, 128, 128);
	padding: 5px 10px;
	border-radius: 5px;
	color: white;
	background-color: rgb(128, 128, 128);
}

.spread {
	display: flex;
	justify-content: space-between;
}

</style>