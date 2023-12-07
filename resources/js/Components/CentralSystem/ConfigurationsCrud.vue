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
	configurations: []
})

watch(() => bus.value.get('get-messages'), (payload) => {
	const [cpId] = payload;
	if (cpId === props.chargePointId) {
		getConfigurations();
	}
})
let configurationTimeout = {};

const getConfigurations = () => {
	axios.get(`/get-configurations/${props.chargePointId}`).then(response => {
		state.configurations = response.data.payload;
	})
}
const getConfiguration = (configuration) => {
	if(configuration === undefined){
		configuration = window.prompt("Give me the key of the configuration.");
	}
	axios.get(`/get-configuration/${props.chargePointId}?configuration=${configuration}`).then(response => {
		state.configurations = response.data.payload;
		configurationTimeout = setTimeout(() => {
			getConfigurations();
		}, 3000);
	})
}
const editConfiguration = (configuration) => {
	if (configuration.readonly) return;
	const newValue = window.prompt(`Set new value for ${configuration.key}:`, configuration.value);
	if (!newValue) return;

	axios.post(`/set-configuration/${props.chargePointId}`, {key: configuration.key, value: newValue});
	configurationTimeout = setTimeout(() => {
		getConfiguration(configuration.key)
	}, 2000);
}
getConfigurations();
</script>

<template>
	<div class="py-12">

		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div>
				<button @click="getConfiguration(undefined)" class="button">Request configuration</button>
			</div>
		</div>
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white">
				<table v-if="state.configurations.length" class="table border w-full">
					<thead>
					<tr>
						<th>Key</th>
						<th>Readonly</th>
						<th>Value</th>
						<th>Last update</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr class="pointer" :class="configuration.readonly?'darken':''"
					    @click="editConfiguration(configuration)"
					    v-for="configuration in state.configurations"
					    :key="configuration.id">
						<td>{{ configuration.key }}</td>
						<td>{{ configuration.readonly ? 'Y' : 'N' }}</td>
						<td style="overflow: auto; width: 30%;word-break: break-all">
							{{ configuration.value || "No value" }}
						</td>
						<td>{{ humanDate(configuration.updated_at) }}</td>
						<td><button @click.prevent="getConfiguration(configuration.key)" class="btn icon">🔄</button></td>
					</tr>
					</tbody>
				</table>
				<div class="w-full text-center" style="font-size: 30px" v-else>
					<div class="mx-auto p-12 ">Loading configurations...</div>
				</div>
			</div>
		</div>
	</div>

</template>

<style scoped>
</style>