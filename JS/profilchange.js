const dropZone = document.getElementById("dropZone");
const fileInput = document.getElementById("fileInput");
const preview = document.getElementById("preview");
const uploadForm = document.getElementById("uploadForm");
const imgProfilContainerMain = document.getElementById(
	"imgProfilContainerMain"
);

dropZone.addEventListener("click", () => fileInput.click());

dropZone.addEventListener("dragover", (e) => {
	e.preventDefault();
	dropZone.classList.add("dragover");
});

dropZone.addEventListener("dragleave", () => {
	dropZone.classList.remove("dragover");
});

dropZone.addEventListener("drop", (e) => {
	e.preventDefault();
	dropZone.classList.remove("dragover");
	const file = e.dataTransfer.files[0];
	fileInput.files = e.dataTransfer.files;
	showPreview(file);
});

fileInput.addEventListener("change", () => {
	const file = fileInput.files[0];
	showPreview(file);
});

function showPreview(file) {
	if (file && file.type.startsWith("image/")) {
		const reader = new FileReader();
		reader.onload = function (e) {
			preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
		};
		reader.readAsDataURL(file);
	} else {
		preview.innerHTML = "";
	}
}

function displayUploadForm() {
	uploadForm.style.display = "flex";
	imgProfilContainerMain.style.display = "none";
}

// recuperer les differet form

document.addEventListener("DOMContentLoaded", () => {
	const forms = document.querySelectorAll("form");

	forms.forEach((form) => {
		const inputs = form.querySelectorAll("input");
		const button = form.querySelector("button");

		const checkIfModified = () => {
			let modified = false;
			inputs.forEach((input) => {
				if (input.value !== input.defaultValue) {
					modified = true;
				}
			});

			if (button) {
				if (modified) {
					button.classList.add("active");
				} else {
					button.classList.remove("active");
				}
			}
		};

		// Check à chaque frappe / changement
		inputs.forEach((input) => {
			input.addEventListener("input", checkIfModified);
		});
	});
});
