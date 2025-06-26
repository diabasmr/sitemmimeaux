

function changerQuantite(val) {
  const input = document.getElementById('quantite');
  const min = parseInt(input.min);
  const max = parseInt(input.dataset.max);
  let valeur = parseInt(input.value) || min;
  console.log(max, min, valeur, val);
  valeur += val;

  // Clamp entre min et max
  if (valeur < min) valeur = min;
  if (valeur > max) valeur = max;

  input.value = valeur;
}

function verifierHoraires() {
  const bouton = document.getElementById('boutonSoumettre');
  const horaireD = document.getElementById('horaireD');
  const horaireF = document.getElementById('horaireF');
  const alerte = document.getElementById('alerte');

  if (horaireD.value && horaireF.value && horaireD.value >= horaireF.value) {
    alerte.classList.add('d-flex');
    alerte.classList.remove('d-none');
    bouton.disabled = true;
  } else {
    alerte.classList.remove('d-flex');
    alerte.classList.add('d-none');
    bouton.disabled = false;
  }
}

horaireD.addEventListener('change', verifierHoraires);
horaireF.addEventListener('change', verifierHoraires);
