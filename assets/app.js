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

import { Calendar } from "@fullcalendar/core";
import timeGridPlugin from "@fullcalendar/timegrid";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction"; // Importer le plugin d'interaction

let evenements = [
  {
    title: "bureaux partagés",
    start: "2024-08-14 09:00:00",
    end: "2024-08-14 11:00:00",
    backgroundColor: "#5d6371",
  },
  {
    title: "bureaux partagés",
    start: "2024-08-14 12:00:00",
    end: "2024-08-14 16:00:00",
  },
];

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  let calendar = new Calendar(calendarEl, {
    plugins: [timeGridPlugin, dayGridPlugin, interactionPlugin],
    locale: "fr",
    businessHours: {
      // days of week. an array of zero-based day of week integers (0=Sunday)
      daysOfWeek: [1, 2, 3, 4, 5], // Monday - Thursday

      startTime: "9:00", // a start time (10am in this example)
      endTime: "18:00", // an end time (6pm in this example)
    },
    headerToolbar: {
      left: "prev,next",
      center: "title",
      right: "today,timeGridWeek,dayGridMonth",
    },
    buttonText: {
      today: "aujourd'hui",
      month: "mois",
      week: "semaine",
      day: "jour",
    },
    allDayText: "jour entier",
    eventInteractive: true,
    selectable: true,
    events: evenements,
    nowIndicator: true,
    editable: true,
    navLinks: true,
  });

  calendar.render();
});
