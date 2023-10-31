<script setup>
	const props = defineProps({
		payload: {
			type: Object
		}
	})

	const parsed = JSON.parse(props.payload);
</script>

<template>
	<div class="inline-flex justify-between" style="width: 100%">
		<span class="key-value p-2" v-for="(value,key) in parsed">
			<b><span class="pr-4">{{key}}:</span></b>{{value || "No value"}}
		</span>
	</div>
</template>

<style scoped>
	.key-value{
		display: flex;
		width: 50%;
		overflow: auto;
		border-bottom: 1px dashed rgb(128, 128, 128);
	}
	.inline-flex{
		flex-wrap: wrap;
	}
</style>