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
import interactionPlugin from "@fullcalendar/interaction";

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  let calendar = new Calendar(calendarEl, {
    plugins: [timeGridPlugin, dayGridPlugin, interactionPlugin],
    locale: "fr",
    businessHours: {
      daysOfWeek: [1, 2, 3, 4, 5],
      startTime: "9:00",
      endTime: "18:00",
    },
    headerToolbar: {
      left: "prev,next",
      center: "title",
      right: "today,timeGridWeek,dayGridMonth deleteEventButton",
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
    nowIndicator: true,
    editable: true,
    navLinks: true,
    events: "/api/events",

    select: function (info) {
      const start = info.startStr;
      const end = info.endStr;

      const title = prompt("Entrez un titre de réservation:");
      if (title) {
        fetch("/api/reservations", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content"),
          },
          body: JSON.stringify({
            start_date: start,
            end_date: end,
            title: title,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              calendar.addEvent({
                id: data.id,
                title: title,
                start: start,
                end: end,
              });
            } else {
              alert("Error saving reservation");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert("Error saving reservation");
          });
      }

      calendar.unselect();
    },

    eventClick: function (info) {
      const eventId = info.event.id;
      const newTitle = prompt(
        "Modifier le titre de la réservation:",
        info.event.title
      );
      if (newTitle !== null) {
        fetch(`/api/reservations/${eventId}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content"),
          },
          body: JSON.stringify({
            title: newTitle,
            start_date: info.event.start.toISOString(),
            end_date: info.event.end.toISOString(),
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              info.event.setProp("title", newTitle);
            } else {
              alert("Error updating reservation");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert("Error updating reservation");
          });
      }
    },

    eventDrop: function (info) {
      const eventId = info.event.id;
      fetch(`/api/reservations/${eventId}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-Token": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
        body: JSON.stringify({
          start_date: info.event.start.toISOString(),
          end_date: info.event.end.toISOString(),
          title: info.event.title,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (!data.success) {
            alert("Error updating reservation");
            info.revert();
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Error updating reservation");
          info.revert();
        });
    },

    eventResize: function (info) {
      const eventId = info.event.id;
      fetch(`/api/reservations/${eventId}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-Token": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
        body: JSON.stringify({
          start_date: info.event.start.toISOString(),
          end_date: info.event.end.toISOString(),
          title: info.event.title,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (!data.success) {
            alert("Error updating reservation");
            info.revert();
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Error updating reservation");
          info.revert();
        });
    },

    customButtons: {
      deleteEventButton: {
        text: "Delete",
        click: function () {
          const title = prompt("Enter the title of the event to delete:");
          if (title) {
            fetch(`/api/reservations`, {
              method: "DELETE",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": document
                  .querySelector('meta[name="csrf-token"]')
                  .getAttribute("content"),
              },
              body: JSON.stringify({ title: title }),
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.success) {
                  const event = calendar
                    .getEvents()
                    .find((event) => event.title === title);
                  if (event) {
                    event.remove();
                  }
                } else {
                  alert("Error deleting reservation");
                }
              })
              .catch((error) => {
                console.error("Error:", error);
                alert("Error deleting reservation");
              });
          }
        },
      },
    },
  });

  calendar.render();
});
