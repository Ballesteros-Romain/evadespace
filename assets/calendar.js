import { Calendar } from "@fullcalendar/core";
import timeGridPlugin from "@fullcalendar/timegrid";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  // Fonction pour formater une date en format "DD-MM-YYYY HH:MM"
  function formatDateForPrompt(date) {
    const day = date.getDate().toString().padStart(2, "0");
    const month = (date.getMonth() + 1).toString().padStart(2, "0");
    const year = date.getFullYear();
    const hours = date.getHours().toString().padStart(2, "0");
    const minutes = date.getMinutes().toString().padStart(2, "0");
    return `${day}/${month}/${year} ${hours}:${minutes}`;
  }

  // Fonction pour convertir une date au format "DD-MM-YYYY HH:MM" en format ISO
  function parseDateFromPrompt(dateString) {
    const [day, month, year, hour, minute] = dateString
      .split(/[/\s:]/)
      .map(Number);
    return new Date(year, month - 1, day, hour, minute).toISOString();
  }

  let calendar = new Calendar(calendarEl, {
    plugins: [timeGridPlugin, dayGridPlugin, interactionPlugin],
    locale: "fr",
    timeZone: "local",
    businessHours: {
      daysOfWeek: [1, 2, 3, 4, 5], // Lundi à Vendredi
      startTime: "09:00",
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
      const start = info.start;
      const end = info.end;

      // Pré-remplir les champs du prompt avec les valeurs sélectionnées
      const title = prompt("Entrez un nom de réservation:");
      const startDate = prompt(
        "Entrez la date et l'heure de début (format: DD-MM-YYYY HH:MM):",
        formatDateForPrompt(start)
      );
      const endDate = prompt(
        "Entrez la date et l'heure de fin (format: DD-MM-YYYY HH:MM):",
        formatDateForPrompt(end)
      );

      if (title && startDate && endDate) {
        fetch("/api/reservations", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content"),
          },
          body: JSON.stringify({
            start_date: parseDateFromPrompt(startDate),
            end_date: parseDateFromPrompt(endDate),
            title: title,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              calendar.addEvent({
                id: data.id,
                title: title,
                start: parseDateFromPrompt(startDate),
                end: parseDateFromPrompt(endDate),
              });
            } else {
              alert("Erreur lors de la sauvegarde de la réservation");
            }
          })
          .catch((error) => {
            console.error("Erreur :", error);
            alert("Erreur lors de la sauvegarde de la réservation");
          });
      }

      calendar.unselect();
    },

    eventClick: function (info) {
      const eventId = info.event.id;
      const currentTitle = info.event.title;
      const currentStart = info.event.start;
      const currentEnd = info.event.end;

      // Convertir les dates en chaînes formatées
      const formattedStart = currentStart
        ? formatDateForPrompt(currentStart)
        : "";
      const formattedEnd = currentEnd ? formatDateForPrompt(currentEnd) : "";

      // Pré-remplir les champs du prompt avec les valeurs actuelles
      const newTitle = prompt(
        "Modifier le titre de la réservation:",
        currentTitle
      );
      const newStartDate = prompt(
        "Modifier la date et l'heure de début (format: DD-MM-YYYY HH:MM):",
        formattedStart
      );
      const newEndDate = prompt(
        "Modifier la date et l'heure de fin (format: DD-MM-YYYY HH:MM):",
        formattedEnd
      );

      if (newTitle !== null && newStartDate && newEndDate) {
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
            start_date: parseDateFromPrompt(newStartDate),
            end_date: parseDateFromPrompt(newEndDate),
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              info.event.setProp("title", newTitle);
              info.event.setDates(
                parseDateFromPrompt(newStartDate),
                parseDateFromPrompt(newEndDate)
              );
            } else {
              alert("Erreur lors de la mise à jour de la réservation");
            }
          })
          .catch((error) => {
            console.error("Erreur :", error);
            alert("Erreur lors de la mise à jour de la réservation");
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
          end_date: info.event.end ? info.event.end.toISOString() : null,
          title: info.event.title,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (!data.success) {
            alert("Erreur lors de la mise à jour de la réservation");
            info.revert();
          }
        })
        .catch((error) => {
          console.error("Erreur :", error);
          alert("Erreur lors de la mise à jour de la réservation");
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
            alert("Erreur lors de la mise à jour de la réservation");
            info.revert();
          }
        })
        .catch((error) => {
          console.error("Erreur :", error);
          alert("Erreur lors de la mise à jour de la réservation");
          info.revert();
        });
    },

    customButtons: {
      deleteEventButton: {
        text: "Supprimer un créneau",
        click: function () {
          const title = prompt("Entrez le titre de l'événement à supprimer:");
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
                  alert("Erreur lors de la suppression de la réservation");
                }
              })
              .catch((error) => {
                console.error("Erreur :", error);
                alert("Erreur lors de la suppression de la réservation");
              });
          }
        },
      },
    },
  });

  calendar.render();
});
