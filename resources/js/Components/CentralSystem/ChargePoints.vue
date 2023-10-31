<script setup>

let chargePoints = [];

const getChargepoints = () => {
	chargePoints = [];
	axios('/get-charge-points').then(response => {
		chargePoints = response.data.payload;
	})
}

getChargepoints();

const goto = (route) => {
	window.location.href = route;
}
</script>

<template>
	<div class="py-12 justify-items-center flex">
		<div @click="goto('/charge-point/'+chargePoint.id)" class="charge-point p-3"
		     v-for="chargePoint in chargePoints" :key="chargePoint.id">
			<div class="">
				<b>idTag:</b> {{ chargePoint.uuid }}
			</div>
			<div>
				<b>Last meter value:</b> {{ chargePoint.last_meter_value }}
			</div>
			<div>
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
</style>