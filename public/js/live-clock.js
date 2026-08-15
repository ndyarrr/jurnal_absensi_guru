/* ==========================================================================
   Live Clock Widget - Real-Time Display (Simple & Clean)
   ========================================================================== */

(function() {
    'use strict';

    const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Clear any remnant custom time offset from localStorage
    try { localStorage.removeItem('customClockOffsetMs'); } catch(e) {}

    function updateLiveClock() {
        const now = new Date();
        const dayName = DAYS[now.getDay()];
        const dateNum = now.getDate();
        const monthName = MONTHS[now.getMonth()];
        const year = now.getFullYear();

        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const dateEl = document.getElementById('live_date_str');
        const timeEl = document.getElementById('live_time_str');

        if (dateEl) dateEl.innerText = `${dayName}, ${dateNum} ${monthName} ${year}`;
        if (timeEl) timeEl.innerText = `${hours}:${minutes}:${seconds} WIB`;
    }

    function init() {
        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.updateLiveClock = updateLiveClock;
})();
