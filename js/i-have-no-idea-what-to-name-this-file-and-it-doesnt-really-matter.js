const unlikeImgHTML = "<img style=\"vertical-align: middle;\" src=\"/static/witter-liked.png\">";
const likeImgHTML = "<img style=\"vertical-align: middle;\" src=\"/static/witter-like.png\">";
function like(id) {
	let xht = new XMLHttpRequest();
	xht.onreadystatechange = () => {
		let newUnlikeButton = document.getElementsByClassName("like")[0];
		newUnlikeButton.classList.add("unlike");
		newUnlikeButton.classList.remove("like");
		newUnlikeButton.setAttribute("onclick", `unlike(${id})`);
		newUnlikeButton.innerHTML = unlikeImgHTML;
	};
	xht.open("GET", `/like.php?id=${id}`, true);
	xht.send();
}
function unlike(id) {
	let xht = new XMLHttpRequest();
	xht.onreadystatechange = () => {
		let newLikeButton = document.getElementsByClassName("unlike")[0];
		newLikeButton.classList.add("like");
		newLikeButton.classList.remove("unlike");
		newLikeButton.setAttribute("onclick", `like(${id})`);
		newLikeButton.innerHTML = likeImgHTML;
	};
	xht.open("GET", `/unlike.php?id=${id}`, true);
	xht.send();
}
