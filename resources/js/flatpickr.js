import flatpickr from "flatpickr";

// flatpickrを日本語に変換
import { Japanese } from "flatpickr/dist/l10n/ja.js"

flatpickr("#event_date", {
    "locale": Japanese,

    // 本日以降の日付でないと選べないようにする
    minDate: "today",

    // minDateから30日以内の日付を選択できるようにする
    maxDate: new Date().fp_incr(30)
});

flatpickr("#calendar", {
    "locale": Japanese,
    // minDate: "today",
    maxDate: new Date().fp_incr(30)
});

const setting = {
    "locale": Japanese,
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,

    // 10時から20時の間の時間で
    // 選択できるようにする
    minTime: "10:00",
    maxTime: "20:00",

    //30分区切りで時間の選択をできるようにする
    minuteIncrement: 30
}

flatpickr("#start_time", setting);
flatpickr("#end_time", setting);