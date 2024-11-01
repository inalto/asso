var calendarOptions = {
    initialView: 'dayGridMonth',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    views: {
        dayGridMonth: { buttonText: 'Month' },
        timeGridWeek: { buttonText: 'Week' },
        timeGridDay: { buttonText: 'Day' }
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

// Function to fetch calendar events
function fetchCalendarEvents(fetchInfo, successCallback, failureCallback) {
    fetch('/api/dates', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        // Pass the events to FullCalendar
        successCallback(data);
    })
    .catch(error => {
        console.error('Error fetching calendar events:', error);
        failureCallback(error);
    });
}
