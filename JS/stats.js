statsResa = document.getElementById("stats-resa");
statsMateriel = document.getElementById("stats-materiel");
statsSalles = document.getElementById("stats-salles");
function showResas() {
  statsResa.style.display = "block";
  statsMateriel.style.display = "none";
  statsSalles.style.display = "none";
}
function showMateriel() {
  statsResa.style.display = "none";
  statsMateriel.style.display = "block";
  statsSalles.style.display = "none";
}
function showSalles() {
  statsResa.style.display = "none";
  statsMateriel.style.display = "none";
  statsSalles.style.display = "block";
}

var ctx = document.getElementById("Reservations").getContext("2d");
var chart = new Chart(ctx, {
  // The type of chart we want to create
  type: "line",

  // The data for our dataset
  data: {
    labels: [
      "Septembre",
      "Octobre",
      "Novembre",
      "Décembre",
      "Janvier",
      "Février",
      "Mars",
      "Avril",
      "Mai",
      "Juin",
    ],
    datasets: [
      {
        label: "Total des Réservations",
        backgroundColor: "rgba(255, 99, 133, 0.65)",
        borderColor: "rgb(255, 99, 133)",
        data: [22, 22, 60, 57, 34, 22, 20, 8, 27, 70, 60, 8],
      },
      {
        label: "Enseignant(e)s",
        backgroundColor: "rgba(15, 81, 124, 0.49)",
        borderColor: "rgb(15, 81, 124",
        data: [20, 16, 40, 7, 0, 2, 10, 3, 20, 30, 10, 3],
      },
      {
        label: "Etudiant(e)s",
        backgroundColor: "rgba(219, 162, 136, 0.49)",
        borderColor: "rgb(219, 162, 136)",
        data: [2, 6, 20, 50, 34, 20, 10, 5, 7, 40, 50, 5],
      },
    ],
  },

  // Configuration options go here
  options: {},
});

var ctx4 = document.getElementById("Validations").getContext("2d");
var myDoughnutChart4 = new Chart(ctx4, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [5, 15, 2, 25],
        backgroundColor: ["#9ed28e", "#d33859", "#eabf70", "#8b9fbe"],
        borderColor: "#fff",
        borderWidth: 2,
      },
    ],
    labels: ["Acceptée", "Refusée", "En attente", "Terminée"],
  },
  options: { responsive: true },
});

//MATERIEL
var ctx2 = document.getElementById("Materiel").getContext("2d");
var myBarChart = new Chart(ctx2, {
  type: "bar",
  data: {
    labels: [
      "Camera",
      "Acessoire",
      "Vidéo",
      "Audio",
      "Drone",
      "AR/VR",
      "Graphisme",
    ],
    datasets: [
      {
        label: "Etudiant(e)s en 1ere année",
        backgroundColor: "rgba(255, 99, 133, 0.65)",
        borderColor: "rgb(255, 99, 133)",
        data: [22, 22, 60, 57, 34, 22, 20, 8, 27, 70, 60, 8],
      },
      {
        label: "Etudiant(e)s en 2eme année",
        backgroundColor: "rgba(15, 81, 124, 0.49)",
        borderColor: "rgb(15, 81, 124",
        data: [20, 16, 40, 7, 0, 2, 10, 3, 20, 30, 10, 3],
      },
      {
        label: "Etudiant(e)s en 3eme année",
        backgroundColor: "rgba(219, 162, 136, 0.49)",
        borderColor: "rgb(219, 162, 136)",
        data: [2, 6, 20, 50, 34, 20, 10, 5, 7, 40, 50, 5],
      },
    ],
  },
  options: {},
});

var ctx3 = document.getElementById("Temporalite1").getContext("2d");
var myDoughnutChart3 = new Chart(ctx3, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [10, 20, 30],
        backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88"],
        borderColor: "#fff",
        borderWidth: 2,
      },
    ],
    labels: ["1 heure", "2-3 heures", "Plus de 4 heures"],
  },
  options: { responsive: true },
});

var ctx3 = document.getElementById("Temporalite2").getContext("2d");
var myDoughnutChart3 = new Chart(ctx3, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [10, 20, 30],
        backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88"],
        borderColor: "#fff",
        borderWidth: 2,
      },
    ],
    labels: ["1 heure", "2-3 heures", "Plus de 4 heures"],
  },
  options: { responsive: true },
});

var ctx3 = document.getElementById("Temporalite3").getContext("2d");
var myDoughnutChart3 = new Chart(ctx3, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [10, 20, 30],
        backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88"],
        borderColor: "#fff",
        borderWidth: 2,
      },
    ],
    labels: ["1 heure", "2-3 heures", "Plus de 4 heures"],
  },
  options: { responsive: true },
});

//SALLES
var ctx2 = document.getElementById("Salles").getContext("2d");
var myBarChart = new Chart(ctx2, {
  type: "bar",
  data: {
    labels: [
      "Camera",
      "Acessoire",
      "Vidéo",
      "Audio",
      "Drone",
      "AR/VR",
      "Graphisme",
    ],
    datasets: [
      {
        label: "Etudiant(e)s en 1ere année",
        backgroundColor: "rgba(255, 99, 133, 0.65)",
        borderColor: "rgb(255, 99, 133)",
        data: [22, 22, 60, 57, 34, 22, 20, 8, 27, 70, 60, 8],
      },
      {
        label: "Etudiant(e)s en 2eme année",
        backgroundColor: "rgba(15, 81, 124, 0.49)",
        borderColor: "rgb(15, 81, 124",
        data: [20, 16, 40, 7, 0, 2, 10, 3, 20, 30, 10, 3],
      },
      {
        label: "Etudiant(e)s en 3eme année",
        backgroundColor: "rgba(219, 162, 136, 0.49)",
        borderColor: "rgb(219, 162, 136)",
        data: [2, 6, 20, 50, 34, 20, 10, 5, 7, 40, 50, 5],
      },
    ],
  },
  options: {},
});

var ctx3 = document.getElementById("Temporalite4").getContext("2d");
var myDoughnutChart3 = new Chart(ctx3, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [10, 20, 30],
        backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88"],
        borderColor: "#fff",
        borderWidth: 2,
      },
    ],
    labels: ["1 heure", "2-3 heures", "Plus de 4 heures"],
  },
  options: { responsive: true },
});

var ctx3 = document.getElementById("Temporalite5").getContext("2d");
var myDoughnutChart3 = new Chart(ctx3, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [10, 20, 30],
        backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88"],
        borderColor: "#fff",
        borderWidth: 2,
      },
    ],
    labels: ["1 heure", "2-3 heures", "Plus de 4 heures"],
  },
  options: { responsive: true },
});

var ctx3 = document.getElementById("Temporalite6").getContext("2d");
var myDoughnutChart3 = new Chart(ctx3, {
  type: "doughnut",
  data: {
    datasets: [
      {
        data: [10, 20, 30],
        backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88"],
        borderColor: "#fff",
        borderWidth: 2,
      },
    ],
    labels: ["1 heure", "2-3 heures", "Plus de 4 heures"],
  },
  options: { responsive: true },
});
