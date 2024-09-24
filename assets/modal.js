// Vérifie si l'utilisateur a fermé la modale dans les dernières 24 heures
const lastClosed = localStorage.getItem("modalClosedAt");
const now = new Date().getTime();
const oneDay = 24 * 60 * 60 * 1000; // 24 heures en millisecondes

// Fonction pour afficher la modale au chargement si nécessaire
window.onload = (event) => {
  if (!lastClosed || now - lastClosed > oneDay) {
    let modalCookie = new bootstrap.Modal(
      document.querySelector("#modalCookie")
    );
    modalCookie.show();
    console.log("La modale est affichée");

    // Événement déclenché lorsque la modale est fermée
    document
      .querySelector("#modalCookie")
      .addEventListener("hidden.bs.modal", () => {
        // Enregistre la date de fermeture dans localStorage
        localStorage.setItem("modalClosedAt", new Date().getTime());
        console.log("La modale a été fermée et l'heure a été enregistrée");
      });
  } else {
    console.log(
      "La modale ne s'affiche pas car elle a été fermée dans les dernières 24 heures"
    );
  }
};
