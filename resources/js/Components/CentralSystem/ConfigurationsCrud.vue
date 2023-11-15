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
		clearTimeout(configurationTimeout);
		if (!state.configurations.length) {
			configurationTimeout = setTimeout(() => {
				getConfigurations()
			}, 1500);
		}
	})
}
const editConfiguration = (configuration) => {
	if (configuration.readonly) return;
	const newValue = window.prompt(`Set new value for ${configuration.key}:`, configuration.value);
	if (!newValue) return;

	axios.post(`/set-configuration/${props.chargePointId}`, {key: configuration.key, value: newValue});
	configurationTimeout = setTimeout(() => {
		getConfigurations()
	}, 2000);
}

getConfigurations();
</script>

<template>
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white">
				<table v-if="state.configurations.length" class="table border w-full">
					<thead>
					<tr>
						<th>Key</th>
						<th>Readonly</th>
						<th>Value</th>
						<th>Last update</th>
					</tr>
					</thead>
					<tbody>
					<tr class="pointer" :style="configuration.readonly?'background-color: #e3e3e3':''"
					    @click="editConfiguration(configuration)"
					    v-for="configuration in state.configurations"
					    :key="configuration.id">
						<td>{{ configuration.key }}</td>
						<td>{{ configuration.readonly ? 'Y' : 'N' }}</td>
						<td style="overflow: auto; width: 30%;word-break: break-all">
							{{ configuration.value || "No value" }}
						</td>
						<td>{{ humanDate(configuration.updated_at) }}</td>
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