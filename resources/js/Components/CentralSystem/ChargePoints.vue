<script setup>

import {reactive} from "vue";
import useUtils from "@/functions.js";

const {goto, alert} = useUtils();
const state = reactive({
	chargePoints: []
})

const getChargepoints = () => {
	state.chargePoints = [];
	axios('/get-charge-points').then(response => {
		state.chargePoints = response.data.payload;
	})
}

const deleteChargePoint = (id) => {
	console.log(id);
	alert("Estas seguro que quieres eliminar el chargePoint?", "Eliminar").then(response => {
		if (response) {
			axios(`/delete-charge-point/${id}`).then(() => {
				getChargepoints();
			});
		}
	});
}

getChargepoints();

</script>

<template>
	<div class="py-12 justify-items-center flex wrap">
		<div class="charge-point p-3"
		     v-for="chargePoint in state.chargePoints" :key="chargePoint.id">
			<div class="flex-row">
				<div class="flex col-8" @click="goto(`/charge-point/${chargePoint.id}`)"><b>idTag:</b>
					{{ chargePoint.uuid }}
				</div>
				<div class="flex col-4 pull-right"><a @click="deleteChargePoint(chargePoint.id)" class="btn icon">🗑️</a>
				</div>
			</div>
			<div @click="goto(`/charge-point/${chargePoint.id}`)">
				<b>Last meter value:</b> {{ chargePoint.last_meter_value }}
			</div>
			<div @click="goto(`/charge-point/${chargePoint.id}`)">
				<b>Last transaction id:</b> {{ chargePoint.last_transaction_id }}
			</div>
		</div>
	</div>
</template>

<style scoped>
.charge-point {
	flex: 1;
	min-width: 30%;
	max-width: 33%;
	border: 1px solid black;
	cursor: pointer;
}

.wrap {
	flex-wrap: wrap;
}
</style>
