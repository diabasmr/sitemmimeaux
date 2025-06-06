function prev() {
  // recuper fc-prev-button fc-button fc-button-primary
  const fcPrevButton = document.querySelector(
    ".fc-prev-button.fc-button.fc-button-primary"
  );
  fcPrevButton.click();
  updateCustomHeader();
}

function next() {
  // recuper fc-next-button fc-button fc-button-primary
  const fcNextButton = document.querySelector(
    ".fc-next-button.fc-button.fc-button-primary"
  );
  fcNextButton.click();
  updateCustomHeader();
}
let tel = false;

document.addEventListener("DOMContentLoaded", function () {
  getEventTimes();
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "timeGridWeek",
    themeSystem: "standard",
    locale: "fr",
    headerToolbar: {
      left: "prev,next",
      center: "title",
      right: "dayGridMonth,dayGridWeek,dayGridDay",
    },
    events: "..SAE201-mainv2/PHPpure/get_reservations.php",

    eventDidMount: function (info) {
      const container = info.el.querySelector(".fc-event-main-frame");

      if (container && info.event.extendedProps.avatars) {
        const avatarWrapper = document.createElement("div");
        avatarWrapper.style.position = "absolute";
        avatarWrapper.style.bottom = "5%";
        avatarWrapper.style.left = "5px";
        avatarWrapper.style.display = "flex";
        avatarWrapper.style.gap = "5px";

        // Pour chaque avatar, on crée une image et on l'ajoute
        info.event.extendedProps.avatars.forEach((src) => {
          const img = document.createElement("img");
          img.src = src;
          img.style.width = "30px";
          img.style.height = "30px";
          img.style.borderRadius = "0.5vw";
          img.style.objectFit = "cover";
          img.style.border = "1px solid white";
          avatarWrapper.appendChild(img);
        });

        container.style.position = "relative";
        container.appendChild(avatarWrapper);
      }
    },
  });

  // recuperation de l'idR qui est dans chaque bloque de l'evenement creer avec le json

  calendar.render();
  updateCustomHeader();
  modifyCalendar();

  const reservation = document.getElementsByClassName("fc-event-main-frame");
  for (let i = 0; i < reservation.length; i++) {
    reservation[i].addEventListener("click", function () {
      const idR = reservation[i].getAttribute("data-idR");
      console.log(idR);
    });
  }

  window.addEventListener("resize", function () {
    modifyCalendar();
  });

  const block = document.getElementsByClassName("fc-event-main-frame");

  if (block.length > 0) {
    const img = document.createElement("img");
    img.src = "../../IMG/jinx.png";
    img.style.position = "absolute";
    img.style.bottom = "0";
    img.style.left = "0";
    img.style.width = "30%";
    img.style.height = "auto";
    img.style.borderRadius = "0.5vw";
    block[0].style.position = "relative";
    block[0].appendChild(img);
  }
});

function modifyCalendar() {
  if (window.innerWidth < 1000) {
    const dayBtn = document.querySelector(
      ".fc-dayGridDay-button.fc-button.fc-button-primary"
    );
    if (dayBtn) dayBtn.click();
    tel = true;
  } else {
    if (tel === true) {
      location.reload(); // on était en mobile, on repasse en grand écran => reload
    }
    tel = false;
  }
}

function updateCustomHeader() {
  const actual = document.querySelector(".fc-toolbar-title");
  const dates = document.getElementById("dates");
  if (actual && dates) {
    dates.innerText = actual.innerText;
  }
}

// fc-event-time exemple: 08:00 - 10:00
// recuperer le debut et la fin de l'evenement
function getEventTimes() {
  const fcEventTimes = document.getElementsByClassName("fc-event-time");

  for (let i = 0; i < fcEventTimes.length; i++) {
    const timeText = fcEventTimes[i].innerText;
    const [debut, fin] = timeText.split(" - ");

    // Créer les divs pour heureDebut et heureFin
    const heureDebut = document.createElement("div");
    const heureFin = document.createElement("div");

    heureDebut.className = "heureDebut";
    heureFin.className = "heureFin";

    heureDebut.innerText = debut;
    heureFin.innerText = fin;

    // Ajoute dans le DOM à côté de l'élément actuel (par exemple)
    fcEventTimes[i].parentElement.appendChild(heureDebut);
    fcEventTimes[i].parentElement.appendChild(heureFin);

    // Debug
    console.log(`Événement ${i + 1} : ${debut} à ${fin}`);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const dates = document.getElementById("dates");
  const headerDate = document.getElementById("headerDate");

  if (dates && headerDate) {
    const updateHeaderDate = () => {
      const words = dates.innerText.trim().split(/\s+/); // coupe tous les mots
      if (words.length >= 2) {
        const moisAnnee = `${words[words.length - 2]} ${
          words[words.length - 1]
        }`;
        if (headerDate.innerText !== moisAnnee) {
          headerDate.innerText = moisAnnee;
        }
      }
    };

    // Exécution initiale
    updateHeaderDate();

    // Observer les changements
    const observer = new MutationObserver(updateHeaderDate);
    observer.observe(dates, {
      childList: true,
      subtree: true,
      characterData: true,
    });
  }

  const prevM = document.getElementById("prevM");
  const nextM = document.getElementById("nextM");
  const left = document.getElementById("left");
  const right = document.getElementById("right");

  function clickUntilChange(buttonToClick, oldDate) {
    let attempts = 0;
    const maxAttempts = 32; // évite boucle infinie, max 20 essais
    const interval = setInterval(() => {
      if (headerDate.innerText === oldDate && attempts < maxAttempts) {
        buttonToClick.click();
        attempts++;
      } else {
        clearInterval(interval);
      }
    }, 10); // toutes les 100ms, ajuste selon ta vitesse d'update
  }

  left.addEventListener("click", () => {
    if (!headerDate) return;
    const oldDate = headerDate.innerText;
    clickUntilChange(prevM, oldDate);
  });

  right.addEventListener("click", () => {
    if (!headerDate) return;
    const oldDate = headerDate.innerText;
    clickUntilChange(nextM, oldDate);
  });
});
