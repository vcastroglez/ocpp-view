<script setup>

import {reactive, watch} from "vue";
import useUtils from "@/functions.js";
import useEventsBus from "@/eventBus.js";

const {humanDate, goto} = useUtils();
const state = reactive({
	clients: []
})

const {bus} = useEventsBus();

watch(() => bus.value.get('get-clients'), (payload) => {
	getClients();
})

const getClients = () => {
	axios('/get-clients').then(response => {
		state.clients = response.data.payload;
	})
}

getClients();

const toggleAuthorize = function (event, id_client) {
	const authorize = event.target.checked;
	axios.post(`/toggle-client/${id_client}`, {authorize}).then((response) => {
		state.clients = response.data.payload;
	})
}
</script>

<template>
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white">
				<table class="table border w-full">
					<thead>
					<tr>
						<th>UUID</th>
						<th>Authorized</th>
						<th>Created at</th>
						<th>Last update</th>
						<th>Authorize</th>
					</tr>
					</thead>
					<tbody>
					<tr @click="goto(`/client-transactions/${client.id}`)" :class="!client.authorized?'darken':''"
					    v-for="client in state.clients" :key="client.id">
						<td>{{ client.uuid }}</td>
						<td>{{ client.authorized ? 'Yes' : 'No' }}</td>
						<td>{{ humanDate(client.created_at) }}</td>
						<td>{{ humanDate(client.updated_at) }}</td>
						<td><input @click="toggleAuthorize($event,client.id)" :checked="client.authorized"
						           type="checkbox"/></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<style scoped>

</style>