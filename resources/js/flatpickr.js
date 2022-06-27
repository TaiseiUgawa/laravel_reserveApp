// es modules are recommended, if available, especially for typescript
import flatpickr from "flatpickr";
import { Japanese } from "flatpickr/dist/l10n/ja.js"

// Otherwise, selectors are also supported
flatpickr("#event_date", {
    "locale": Japanese,
    minDate: "today",
    maxDate: new Date().fp_incr(30),
});

flatpickr("#calendar", {
    "locale": Japanese,
    // minDate: "today",
    maxDate: new Date().fp_incr(30),
    minuteIncrement: 30,
});

const setting = {
    "locale": Japanese,
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    minTime: "10:00",
    maxTime: "20:00",
}

flatpickr("#start_time", setting);
flatpickr("#end_time", setting);
