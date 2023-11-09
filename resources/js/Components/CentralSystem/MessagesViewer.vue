<script setup>
import PayloadViewer from "@/Components/CentralSystem/PayloadViewer.vue";
import {reactive, watch} from "vue";
import useEventsBus from "@/eventBus.js";

const {bus} = useEventsBus();

watch(() => bus.value.get('get-messages'), (payload) => {
	const [cpId] = payload;
	if (cpId === props.chargePointId) {
		getMessages();
	}
})

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
</script>

<template>
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
</template>

<style scoped>

</style>