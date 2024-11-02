var calendarOptions = {
    initialView: 'dayGridMonth',
    locale: 'it',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    views: {
        dayGridMonth: { buttonText: 'Mese' },
        timeGridWeek: { buttonText: 'Settimana' },
        timeGridDay: { buttonText: 'Giorno' }
    },
    events: '/api/dates',
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

