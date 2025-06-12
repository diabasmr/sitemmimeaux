document.addEventListener("DOMContentLoaded", function () {
  var buttonSearch = document.getElementById("buttonSearch");
  var inputSearch = document.getElementById("inputSearch");

  buttonSearch.addEventListener("click", function () {
    const value = inputSearch.value.toLowerCase();
    const lines = document.querySelectorAll(".line");
    lines.forEach((line) => {
      const text = line.textContent.toLowerCase();
      if (text.includes(value)) {
        line.style.display = "";
      } else {
        line.style.display = "none";
      }
    });
  });

  const popup = document.querySelector(".modifPopupReservation");
  const closeButton = document.querySelector(".close_modifPopupReservation");
  const modifierButtons = document.querySelectorAll(".modifier");

  // Fonction pour gérer les erreurs de chargement d'image
  function handleImageError(img) {
    img.src = "../uploads/default.png";
    img.onerror = null; // Évite les boucles infinies
  }

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
    const motif = button.dataset.motif;
    const dateDebut = button.dataset.dateDebut;
    const dateFin = button.dataset.dateFin;
    const status = button.dataset.status;
    const materiels = JSON.parse(button.dataset.materiels);
    const salles = JSON.parse(button.dataset.salles);
    const users = JSON.parse(button.dataset.users);
    console.log(materiels);
    console.log(salles);

    // Remplir les champs du formulaire
    document.getElementById("idR").value = id;
    document.getElementById("motif").value = motif;
    document.getElementById("date_debut").value = dateDebut;
    document.getElementById("date_fin").value = dateFin;
    document.getElementById("status").value = status;
    // recuperer materiels[designation] et salles[nom]
    document.getElementById("materiels").value = materiels
      .map((item) => item.designation)
      .join(", ");
    document.getElementById("sallesinput").value = salles
      .map((item) => item.nom)
      .join(", ");
    console.log(
      document.getElementById("materiels").value,
      document.getElementById("sallesinput").value
    );

    // Mettre à jour les avatars
    const avatarContainer = document.querySelector(".avatar-container_img");
    avatarContainer.innerHTML = ""; // Vider le conteneur

    users.forEach((user) => {
      const avatarDiv = document.createElement("div");
      avatarDiv.className = "user-avatar";

      const img = document.createElement("img");
      img.src = user.avatar;
      img.alt = `${user.prenom} ${user.nom}`;
      img.title = `${user.prenom} ${user.nom}`;
      img.onerror = () => handleImageError(img);

      avatarDiv.appendChild(img);
      avatarContainer.appendChild(avatarDiv);
    });

    // Afficher le popup
    popup.classList.add("active");
  }

  // Fonction pour fermer le popup
  function closePopup() {
    popup.classList.remove("active");
  }

  // Ajouter les écouteurs d'événements
  modifierButtons.forEach((button) => {
    button.addEventListener("click", () => openPopup(button));
  });

  closeButton.addEventListener("click", closePopup);

  // Fermer le popup si on clique en dehors
  popup.addEventListener("click", (e) => {
    if (e.target === popup) {
      closePopup();
    }
  });
});
