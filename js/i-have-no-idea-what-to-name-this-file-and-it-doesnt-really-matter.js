const unlikeImgHTML = "<img style=\"vertical-align: middle;\" src=\"/static/witter-liked.png\">";
const likeImgHTML = "<img style=\"vertical-align: middle;\" src=\"/static/witter-like.png\">";
function like(id) {
	let xht = new XMLHttpRequest();
	xht.onreadystatechange = () => {
		let newButton = document.getElementById(`like-toggle-${id}`);
		newButton.setAttribute("onclick", `unlike(${id})`);
		newButton.innerHTML = unlikeImgHTML;
		delete newButton;
	};
	xht.open("GET", `/like.php?id=${id}`, true);
	xht.send();
}
function unlike(id) {
	let xht = new XMLHttpRequest();
	xht.onreadystatechange = () => {
		let newButton = document.getElementById(`like-toggle-${id}`);
		newButton.setAttribute("onclick", `like(${id})`);
		newButton.innerHTML = likeImgHTML;
		delete newButton;
	};
	xht.open("GET", `/unlike.php?id=${id}`, true);
	xht.send();
}
