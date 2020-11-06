const unlikeImgHTML = "<img style=\"vertical-align: middle;\" src=\"/static/witter-liked.png\">";
const likeImgHTML = "<img style=\"vertical-align: middle;\" src=\"/static/witter-like.png\">";
function like(id) {
	let xht = new XMLHttpRequest();
	xht.onreadystatechange = () => {
		let newUnlikeButton = document.getElementById(`like-toggle-${id}`);
		newUnlikeButton.setAttribute("onclick", `unlike(${id})`);
		newUnlikeButton.innerHTML = unlikeImgHTML;
	};
	xht.open("GET", `/like.php?id=${id}`, true);
	xht.send();
}
function unlike(id) {
	let xht = new XMLHttpRequest();
	xht.onreadystatechange = () => {
		let newLikeButton = document.getElementById(`like-toggle-${id}`);
		newLikeButton.setAttribute("onclick", `like(${id})`);
		newLikeButton.innerHTML = likeImgHTML;
	};
	xht.open("GET", `/unlike.php?id=${id}`, true);
	xht.send();
}
