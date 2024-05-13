import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

// ========================= BURGER =======================================
const menuMobile = document.querySelector(".menu-mobile");
const menuMobileClose = document.querySelector(".menu-close-mobile");
const burger = document.querySelector(".burger");

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
    burger.style.left = "35%";
  } else {
    burger.style.left = "75%";
  }
});

menuMobileClose.addEventListener("click", function () {
  menuMobile.style.opacity = "1";
  menuMobile.style.visibility = "visible";
  menuMobileClose.style.opacity = "0";
  menuMobileClose.style.visibility = "hidden";
  burger.style.left = "100%";
});
