function toggleSidebar() {
  const sidebar = document.querySelector("aside");
  const menuimg = document.getElementById("menuimg");
  if (sidebar.classList.contains("active")) {
    sidebar.classList.remove("active");
    menuimg.src = "../res/menu.svg";
  } else {
    sidebar.classList.add("active");
    menuimg.src = "../res/x.svg";
  }
}

// Récupérer le nom de la page actuelle
const url = window.location.href;
const lastSlashIndex = url.lastIndexOf("/");
const lastPart = url.substring(lastSlashIndex + 1);
const pageName = lastPart.split(".")[0];

// Mettre la classe active sur le lien correspondant
const links = document.querySelectorAll("aside nav ul li a");
links.forEach((link) => {
  // Retirer la classe active de tous les liens
  link.classList.remove("active");

  // Ajouter la classe active au lien correspondant à la page actuelle
  if (link.id === pageName) {
    link.classList.add("active");
  }

  // Gestion spéciale pour la page materiels
  if (pageName === "materiels" && link.id === "materiel") {
    link.classList.add("active");
  } else if (pageName === "reservation_materiel" && link.id === "materiel") {
    link.classList.add("active");
  } else if (pageName === "reservation_salle" && link.id === "salles") {
    link.classList.add("active");
  } else if (
    pageName === "listeDesReservations" &&
    link.id === "accepter_reservation"
  ) {
    link.classList.add("active");
  } else if (
    pageName === "listeDuMateriel" &&
    link.id === "accepter_materiel"
  ) {
    link.classList.add("active");
  }
});
