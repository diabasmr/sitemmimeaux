

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
