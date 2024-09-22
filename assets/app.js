import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";
import "./calendar.js";
// ========================= BURGER =======================================
const menuMobile = document.querySelector(".menu-mobile");
const menuMobileClose = document.querySelector(".menu-close-mobile");
const burger = document.querySelector(".burgers");

// Ajout de la transition à toutes les propriétés affectées
menuMobile.style.transition =
  "opacity 0.5s ease-in-out, visibility 0.5s ease-in-out";
menuMobileClose.style.transition =
  "opacity 0.5s ease-in-out, visibility 0.5s ease-in-out";
burger.style.transition = "left 0.5s ease-in-out";

menuMobileClose.style.opacity = "0";
menuMobileClose.style.visibility = "hidden";
burger.style.left = "100%";

menuMobile.addEventListener("click", function () {
  menuMobile.style.opacity = "0";
  menuMobile.style.visibility = "hidden";
  menuMobileClose.style.opacity = "1";
  menuMobileClose.style.visibility = "visible";
  if (window.innerWidth < 400) {
    burger.style.left = "10%";
  } else if (window.innerWidth < 850) {
    burger.style.left = "15.3%";
  } else {
    burger.style.left = "72%";
  }
});

menuMobileClose.addEventListener("click", function () {
  menuMobile.style.opacity = "1";
  menuMobile.style.visibility = "visible";
  menuMobileClose.style.opacity = "0";
  menuMobileClose.style.visibility = "hidden";
  burger.style.left = "100%";
});
// ===================== CAROUSEL =======================================
document.addEventListener("DOMContentLoaded", function () {
  const reviews = document.querySelectorAll(".review");
  let currentReview = 0;
  const colors = ["#b4f2e5", "#abd8df"]; // Couleurs de fond

  if (reviews.length > 0) {
    reviews[currentReview].classList.add("active");
    reviews[currentReview].style.backgroundColor =
      colors[currentReview % colors.length];
  }

  setInterval(() => {
    reviews[currentReview].classList.remove("active");

    currentReview = (currentReview + 1) % reviews.length;

    reviews[currentReview].classList.add("active");
    reviews[currentReview].style.backgroundColor =
      colors[currentReview % colors.length];
  }, 3000); // Change d'avis toutes les 5 secondes
});
// =========================================================================
