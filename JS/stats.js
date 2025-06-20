function getYearFromPageUrl() {
  const params = new URLSearchParams(window.location.search);
  return params.has("year")
    ? parseInt(params.get("year"), 10)
    : new Date().getFullYear();
}

document.addEventListener("DOMContentLoaded", function () {
  const leftBtn = document.getElementById("left");
  const rightBtn = document.getElementById("right");
  const headerDate = document.getElementById("headerDate");

  // Fonction pour récupérer le paramètre "year" dans l'URL
  function getYearFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.has("year") ? parseInt(params.get("year"), 10) : null;
  }

  // Initialisation de la date affichée
  let currentYear = getYearFromPageUrl();
  headerDate.textContent = currentYear;

  // Gestion des clics sur les boutons
  leftBtn.addEventListener("click", function () {
    currentYear -= 1;
    // Redirection vers la nouvelle URL avec la nouvelle année
    window.location.href = `?year=${currentYear}`;
  });

  rightBtn.addEventListener("click", function () {
    currentYear += 1;
    window.location.href = `?year=${currentYear}`;
  });
});

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

const year = getYearFromPageUrl();
fetch(`../phpPure/get_stats.php?year=${year}`)
  .then((response) => response.json())
  .then((reservations) => {
    const ctx = document.getElementById("Reservations").getContext("2d");
    const chart = new Chart(ctx, {
      type: "line",
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
            data: reservations.total, // un tableau de 10 valeurs
          },
          {
            label: "Enseignant(e)s",
            backgroundColor: "rgba(15, 81, 124, 0.49)",
            borderColor: "rgb(15, 81, 124)",
            data: reservations.enseignants, // tableau
          },
          {
            label: "Etudiant(e)s",
            backgroundColor: "rgba(219, 162, 136, 0.49)",
            borderColor: "rgb(219, 162, 136)",
            data: reservations.etudiants, // tableau
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: "Statistiques temporelles des réservations",
          },
        },
      },
    });

    var ctx4 = document.getElementById("Validations").getContext("2d");
    var myDoughnutChart4 = new Chart(ctx4, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [
              reservations.validation.acceptee,
              reservations.validation.refusee,
              reservations.validation.attente,
              reservations.validation.terminee,
            ],
            backgroundColor: ["#9ed28e", "#d33859", "#eabf70", "#8b9fbe"],
            borderColor: "#fff",
            borderWidth: 2,
          },
        ],
        labels: ["Acceptée", "Refusée", "En attente", "Terminée"],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: "Taux de validation des réservations",
          },
        },
      },
    });

    //MATERIEL
    var ctx2 = document.getElementById("Materiel").getContext("2d");
    var myBarChart = new Chart(ctx2, {
      type: "bar",
      data: {
        labels: ["Accessoire", "Vidéo", "Audio", "Drone", "AR/VR", "Graphisme"],
        datasets: [
          {
            label: "Etudiant(e)s en 1ere année",
            backgroundColor: "rgba(255, 99, 133, 0.65)",
            borderColor: "rgb(255, 99, 133)",
            data: reservations.firstyear,
          },
          {
            label: "Etudiant(e)s en 2eme année",
            backgroundColor: "rgba(15, 81, 124, 0.49)",
            borderColor: "rgb(15, 81, 124",
            data: reservations.secondyear,
          },
          {
            label: "Etudiant(e)s en 3eme année",
            backgroundColor: "rgba(219, 162, 136, 0.49)",
            borderColor: "rgb(219, 162, 136)",
            data: reservations.thirdyear,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: "Réservations des matériels selon les niveaux",
          },
        },
      },
    });

    var ctx3 = document.getElementById("Temporalite1").getContext("2d");
    var myDoughnutChart3 = new Chart(ctx3, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [
              reservations.heure,
              reservations.deuxHeure,
              reservations.troisHeure,
              reservations.plus4Heure,
            ],
            backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88", "#0f507c"],
            borderColor: "#fff",
            borderWidth: 2,
          },
        ],
        labels: ["1 heure", "2-3 heures", "3 heures", "Plus de 4 heures"],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: `Durée d'usage de ${reservations.materiel1}`,
          },
        },
      },
    });

    var ctx3 = document.getElementById("Temporalite2").getContext("2d");
    var myDoughnutChart3 = new Chart(ctx3, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [
              reservations.heureSecond,
              reservations.deuxHeureSecond,
              reservations.troisHeureSecond,
              reservations.plus4HeureSecond,
            ],
            backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88", "#0f507c"],
            borderColor: "#fff",
            borderWidth: 2,
          },
        ],
        labels: ["1 heure", "2 heures", "3 heures", "Plus de 4 heures"],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: `Durée d'usage de ${reservations.materiel2}`,
          },
        },
      },
    });

    var ctx3 = document.getElementById("Temporalite3").getContext("2d");
    var myDoughnutChart3 = new Chart(ctx3, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [
              reservations.heureTroisieme,
              reservations.deuxHeureTroisieme,
              reservations.troisHeureTroisieme,
              reservations.plus4HeureTroisieme,
            ],
            backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88", "#0f507c"],
            borderColor: "#fff",
            borderWidth: 2,
          },
        ],
        labels: ["1 heure", "2 heures", "3 heures", "Plus de 4 heures"],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: `Durée d'usage de ${reservations.materiel3}`,
          },
        },
      },
    });

    //SALLES
    var ctx2 = document.getElementById("Salles").getContext("2d");
    var myBarChart = new Chart(ctx2, {
      type: "bar",
      data: {
        labels: ["Salle 138", "Salle 212"],
        datasets: [
          {
            label: "Etudiant(e)s en 1ere année",
            backgroundColor: "rgba(255, 99, 133, 0.65)",
            borderColor: "rgb(255, 99, 133)",
            data: reservations.firstyearS,
          },
          {
            label: "Etudiant(e)s en 2eme année",
            backgroundColor: "rgba(15, 81, 124, 0.49)",
            borderColor: "rgb(15, 81, 124",
            data: reservations.secondyearS,
          },
          {
            label: "Etudiant(e)s en 3eme année",
            backgroundColor: "rgba(219, 162, 136, 0.49)",
            borderColor: "rgb(219, 162, 136)",
            data: reservations.thirdyearS,
          },
          {
            label: "Enseignant(e)s",
            backgroundColor: "rgba(154, 136, 219, 0.49)",
            borderColor: "rgb(154, 136, 219)",
            data: reservations.enseignantsS,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: `Réservations des Salles selon le rôle`,
          },
        },
      },
    });

    var ctx3 = document.getElementById("Temporalite4").getContext("2d");
    var myDoughnutChart3 = new Chart(ctx3, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [
              reservations.heuresalle138,
              reservations.deuxHeuresalle138,
              reservations.troisHeuresalle138,
              reservations.plus4Heuresalle138,
            ],
            backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88", "#0f507c"],
            borderColor: "#fff",
            borderWidth: 2,
          },
        ],
        labels: ["1 heure", "2 heures", "3 heures", "Plus de 4 heures"],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: `Durée d'usage de la Salle 138`,
          },
        },
      },
    });

    var ctx3 = document.getElementById("Temporalite5").getContext("2d");
    var myDoughnutChart3 = new Chart(ctx3, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [
              reservations.heuresalle212,
              reservations.deuxHeuresalle212,
              reservations.troisHeuresalle212,
              reservations.plus4Heuresalle212,
            ],
            backgroundColor: ["#a38bbe", "#dc88bf", "#dcab88", "#0f507c"],
            borderColor: "#fff",
            borderWidth: 2,
          },
        ],
        labels: ["1 heure", "2 heures", "3 heures", "Plus de 4 heures"],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: `Durée d'usage de la Salle 212`,
          },
        },
      },
    });
  });

//TELECHARGEMENT CSV
function exportChartToCSV(canvasId) {
  const chart = Chart.getChart(canvasId);
  if (!chart) return alert(`Graphique "${canvasId}" introuvable.`);

  const labels = chart.data.labels;
  const datasets = chart.data.datasets;
  const separator = ";"; // ou "," selon ton Excel
  const chartTitle = chart.options?.plugins?.title?.text || "Label";

  let csv =
    `"${chartTitle}"` +
    separator +
    datasets.map((ds) => `"${ds.label}"`).join(separator) +
    "\n";

  for (let i = 0; i < labels.length; i++) {
    let row = labels[i];
    datasets.forEach((ds) => {
      const value = ds.data[i];
      row += separator + (value != null ? value : "");
    });
    csv += row + "\n";
  }

  const BOM = "\uFEFF";
  const blob = new Blob([BOM + csv], { type: "text/csv;charset=utf-8;" });

  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `${canvasId}.csv`;
  link.click();
}
