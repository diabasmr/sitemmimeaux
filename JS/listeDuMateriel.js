const popup = document.querySelector(".modifPopupMateriel");
const closeButton = document.querySelector(".close_modifPopupMateriel");
const modifierButtons = document.querySelectorAll(".modifier");

// Fonction pour formater la liste des matériels ou salles
// function formatList(items) {
// 	if (!items || items.length === 0) return "Aucun";
// 	return items
// 		.map((item) => {
// 			if (item.designation) {
// 				// C'est un matériel
// 				return `${item.designation} (${item.reference})`;
// 			} else {
// 				// C'est une salle
// 				return `${item.nom} (${item.type})`;
// 			}
// 		})
// 		.join(", ");
// }

// Fonction pour ouvrir le popup
function openPopup(button) {
  const id = button.dataset.id;
  const designation = button.dataset.designation;
  const dateAchat = button.dataset.dateachat;
  const quantite = button.dataset.quantite;
  const descriptif = button.dataset.descriptif;
  const type = button.dataset.type;
  const etat = button.dataset.etat;
  const lien_demo = button.dataset.lien_demo;

  // Remplir les champs du formulaire
  document.getElementById("idM").value = id;
  document.getElementById("designation").value = designation;
  document.getElementById("date_achat").value = dateAchat;
  document.getElementById("quantite").value = quantite;
  document.getElementById("descriptif").value = descriptif;
  document.getElementById("type").value = type;
  document.getElementById("etat").value = etat;
  document.getElementById("lien_demo").value = lien_demo;
  // Afficher le popup
  popup.classList.add("active");
}

function addmateriel() {
  const addpopup = document.getElementById("ajouterMateriel");
  addpopup.style.display = "block";
}
document.getElementById("addMateriel").addEventListener("click", addmateriel);

// Fonction pour fermer le popup
function closePopup() {
  popup.classList.remove("active");
}

// Ajouter les écouteurs d'événements
modifierButtons.forEach((button) => {
  button.addEventListener("click", () => openPopup(button));
});

closeButton.addEventListener("click", closePopup);

document
  .getElementById("close_modifPopupMateriel")
  .addEventListener("click", closePopup);

// Fermer le popup si on clique en dehors
popup.addEventListener("click", (e) => {
  if (e.target === popup) {
    closePopup();
  }
});
