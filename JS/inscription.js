// Progression bar
const stepIndex = { step1: 33, step2: 66, step3: 100 };

function nextStep(currentStep, nextStep, requiredFields = []) {
  let valid = true;
  let firstInvalid = null;

  // Validation spécifique pour l'étape 2
  if (currentStep === "step2") {
    const dateNaissance = document
      .getElementById("date_naissance")
      .value.trim();
    const email = document.getElementById("email").value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Cas 1: Pas rempli
    if (!dateNaissance || !email) {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Veuillez remplir tous les champs obligatoires.</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    }

    // Cas 2: Email mal formaté
    if (email && !emailRegex.test(email)) {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Veuillez entrer une adresse email valide.</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    }

    // Si tout est valide, on continue
    document.getElementById(currentStep).style.display = "none";
    document.getElementById(nextStep).style.display = "block";
    document.querySelector(".progress").style.display = "";
    document.getElementById("formTitle").style.display = "";
    document.getElementById("progressBar").style.width =
      stepIndex[nextStep] + "%";
    return;
  }

  // Validation pour les autres étapes
  for (let field of requiredFields) {
    const input = document.getElementById(field);
    if (input) {
      const value = input.value.trim();

      if (!value) {
        valid = false;
        firstInvalid = input;
        break;
      }

      // Email validation
      if (field === "email") {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          valid = false;
          firstInvalid = input;
          break;
        }
      }

      // Date de naissance validation
      if (field === "date_naissance") {
        const dateRegex = /^\d{2}-\d{2}-\d{4}$/;
        if (!dateRegex.test(value)) {
          valid = false;
          firstInvalid = input;
          break;
        }
      }
    }
  }

  if (!valid) {
    if (firstInvalid) firstInvalid.focus();
    if (requiredFields.includes("email")) {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Veuillez entrer un email valide.</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    } else if (requiredFields.includes("date_naissance")) {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Veuillez entrer une date de naissance</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    } else {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Veuillez remplir tous les champs obligatoires.</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    }
  }

  // Validation spécifique des mots de passe
  if (currentStep === "step3") {
    const mdp = document.getElementById("mdp").value.trim();
    const confirme_mdp = document.getElementById("confirme_mdp").value.trim();
    const mdpRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,}$/;

    if (!mdp || !confirme_mdp) {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Veuillez remplir tous les champs obligatoires.</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    }
    if (!mdpRegex.test(mdp)) {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Le mot de passe doit faire au moins 6 caractères et contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    }
    if (mdp !== confirme_mdp) {
      const messageDiv = document.createElement("div");
      messageDiv.innerHTML = `
    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center"
         style="--bs-border-opacity: .5; z-index:10; width: 80%; border: 1px solid #e47390;">
      <p class="mb-2 d-block">Les mots de passe ne correspondent pas.</p>
      <div class="text-center mt-3">
        <button onclick="this.closest('.container-sm-6').style.display='none'"
                style="height: 7vh; width:50%; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" class="fs-6 fs-md-2">
          Fermer
        </button>
      </div>
    </div>
  `;
      document.body.appendChild(messageDiv);
      return;
    }
    // Si tout est valide, on soumet le formulaire
    document.getElementById("inscriptionForm").submit();
    return;
  }

  // Etape suivante
  document.getElementById(currentStep).style.display = "none";
  document.getElementById(nextStep).style.display = "block";

  // Progression bar
  document.querySelector(".progress").style.display = "";
  document.getElementById("formTitle").style.display = "";
  document.getElementById("progressBar").style.width =
    stepIndex[nextStep] + "%";
}

// Visibilité des mdp
function voirmdp() {
  let voirimg = document.querySelector(".voirimg");
  let mdp = document.getElementById("mdp");

  if (mdp.type === "password") {
    voirimg.src = "../res/eye-regular.svg";
    mdp.type = "text";
  } else {
    voirimg.src = "../res/eye-slash-regular.svg";
    mdp.type = "password";
  }
}

function voirmdpconfirm() {
  let voirimgconfirm = document.querySelector(".voirimgconfirm");
  let confirme_mdp = document.getElementById("confirme_mdp");

  if (confirme_mdp.type === "password") {
    voirimgconfirm.src = "../res/eye-regular.svg";
    confirme_mdp.type = "text";
  } else {
    voirimgconfirm.src = "../res/eye-slash-regular.svg";
    confirme_mdp.type = "password";
  }
}

function prevStep(currentStep, prevStep) {
  document.getElementById(currentStep).style.display = "none";
  document.getElementById(prevStep).style.display = "block";

  document.querySelector(".progress").style.display = "";
  document.getElementById("formTitle").style.display = "";
  document.getElementById("progressBar").style.width =
    stepIndex[prevStep] + "%";
}

// Auto-remplissage pseudo : prenom.nom
const prenomInput = document.getElementById("prenom");
const nomInput = document.getElementById("nom");
const pseudoInput = document.getElementById("pseudo");

function updatePseudo() {
  const prenom = prenomInput.value.trim().toLowerCase();
  const nom = nomInput.value.trim().toLowerCase();
  if (nom && prenom) {
    pseudoInput.value = `${prenom}.${nom}`;
  }
}

// MAJ pseudo à chaque saisie dans nom ou prénom
prenomInput.addEventListener("input", updatePseudo);
nomInput.addEventListener("input", updatePseudo);
