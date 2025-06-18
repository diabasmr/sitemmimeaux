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

fetch("../phpPure/get_stats.php")
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
      options: { responsive: true },
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
      options: {},
    });

    var ctx3 = document.getElementById("Temporalite1").getContext("2d");
    var myDoughnutChart3 = new Chart(ctx3, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [1, 3, 4],
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
  });

//TELECHARGEMENT CSV
document.getElementById("downloadCSV").addEventListener("click", function () {
  downloadCSV({
    filename: "chart-data.csv",
    chart: chart,
  });
});

function convertChartDataToCSV(args) {
  let result, columnDelimiter, lineDelimiter, labels, data;

  data = args.data.data || null;
  if (data == null || !data.length) {
    return null;
  }

  labels = args.labels || null;
  if (labels == null || !labels.length) {
    return null;
  }

  columnDelimiter = args.columnDelimiter || ",";
  lineDelimiter = args.lineDelimiter || "\n";

  result = "" + columnDelimiter;
  result += labels.join(columnDelimiter);
  result += lineDelimiter;

  result += args.data.label.toString();

  for (let i = 0; i < data.length; i++) {
    result += columnDelimiter;
    result += data[i];
  }
  result += lineDelimiter;

  return result;
}

function downloadCSV(args) {
  var data, filename, link;
  var csv = "";
  for (var i = 0; i < chart.data.datasets.length; i++) {
    csv += convertChartDataToCSV({
      data: chart.data.datasets[i],
      labels: dataLabels,
    });
  }
  if (csv == null) return;
  console.log(csv);

  filename = args.filename || "chart-data.csv";
  if (!csv.match(/^data:text\/csv/i)) {
    csv = "data:text/csv;charset=utf-8," + csv;
  }

  // not sure if anything below this comment works
  data = encodeURI(csv);
  link = document.createElement("a");
  link.setAttribute("href", data);
  link.setAttribute("download", filename);
  document.body.appendChild(link); // Required for FF
  link.click();
  document.body.removeChild(link);
}
