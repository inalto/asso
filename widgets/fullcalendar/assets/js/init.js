var calendarOptions = {
    initialView: 'dayGridMonth',
    locale: 'it',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    buttonText: {
        today: 'Oggi'
    },
    views: {
        dayGridMonth: { buttonText: 'Mese' },
        timeGridWeek: { buttonText: 'Settimana' },
        timeGridDay: { buttonText: 'Giorno' }
    },
    events: '/api/dates',
    eventClick: function(info) {
        window.location.href = '/backend/martinimultimedia/asso/modules/update/' + info.event.id;
    },
    eventDidMount: function(info) {
        info.el.style.cursor = 'pointer';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, calendarOptions);
    calendar.render();
});

if (oc.useTurbo && oc.useTurbo()) {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, calendarOptions);
    calendar.render();
}

