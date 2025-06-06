const eye = document.getElementById("showPassword");
const mdp = document.getElementById("mdp");

eye.addEventListener("click", function () {
	mdp.type = mdp.type === "password" ? "text" : "password";

	if (eye.src.includes("eye-closed.svg")) {
		eye.src = "../res/eye.svg";
	} else {
		eye.src = "../res/eye-closed.svg";
	}
});
