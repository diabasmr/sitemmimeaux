const quantite = document.getElementById("quantite");
const disponible = document.getElementById("dispo");

quantite.addEventListener("input", (e) => {
  const quantiteValeur = parseInt(e.target.value) || 0;
  const dispoInitial = parseInt(disponible.dataset.stock) || 0; // Stock initial stocké dans un data-attribute

  const nouveauDispo = dispoInitial - quantiteValeur;
  disponible.textContent = nouveauDispo >= 0 ? nouveauDispo : 0;
});
