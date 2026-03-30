/**
 * Alpine.js data component for the event calendar.
 *
 * @param {Object} initialEvents - JSON object keyed by 'YYYY-MM-DD',
 *   values are arrays of { id, title, url }.
 *   Passed from Blade via: x-data="eventCalendar({{ $calendarEvents }})"
 */
export function eventCalendar(initialEvents) {
    const today = new Date();

    return {
        // State
        currentYear:  today.getFullYear(),
        currentMonth: today.getMonth(), // 0-based
        calendarEvents: initialEvents,  // events for currently displayed month

        // ── Computed ───────────────────────────────────────────────────────

        get monthLabel() {
            return new Date(this.currentYear, this.currentMonth, 1)
                .toLocaleString('default', { month: 'long', year: 'numeric' });
        },

        get daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },

        /** 0 = Sunday, 6 = Saturday — used for blank leading cells */
        get startDayOfWeek() {
            return new Date(this.currentYear, this.currentMonth, 1).getDay();
        },

        // ── Methods ────────────────────────────────────────────────────────

        isToday(day) {
            return day            === today.getDate()
                && this.currentMonth === today.getMonth()
                && this.currentYear  === today.getFullYear();
        },

        hasEvents(day) {
            const key = this.dateKey(day);
            return !! (this.calendarEvents[key]?.length);
        },

        dateKey(day) {
            const m = String(this.currentMonth + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${this.currentYear}-${m}-${d}`;
        },

        goToEvent(day) {
            const events = this.calendarEvents[this.dateKey(day)];
            if (events?.length === 1) {
                window.location.href = events[0].url;
            } else if (events?.length > 1) {
                // Multiple events on same day — go to first one for now
                window.location.href = events[0].url;
            }
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.fetchEvents();
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.fetchEvents();
        },

        /**
         * Fetch events for the newly navigated month via JSON endpoint.
         * Falls back gracefully if the request fails.
         */
        async fetchEvents() {
            try {
                const m = this.currentMonth + 1;
                const url = `/events/calendar-data?year=${this.currentYear}&month=${m}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (res.ok) {
                    this.calendarEvents = await res.json();
                }
            } catch {
                // Silent fail — calendar still navigates, just no dots
                this.calendarEvents = {};
            }
        },
    };
}
