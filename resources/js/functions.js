export default function useUtils() {

	function humanDate(timestamp) {
		const date = new Date(timestamp);
		return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()} (UTC ${date.getTimezoneOffset() / 60})`;
	}

	function goto(route) {
		window.location.href = route;
	}

	return {
		humanDate,
		goto
	}
}