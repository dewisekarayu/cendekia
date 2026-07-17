<!-- Calendar Script & Alpine.js Data -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('calendar', () => ({
        // State
        currentMonth: {{ $selectedMonth }},
        currentYear: {{ $selectedYear }},
        today: new Date(),
        events: @json($events),
        eventsByDate: @json($eventsByDate ?? []),
        selectedSemesterId: "{{ $selectedSemesterId ?? '' }}",
        visibleCategories: new Set(['uts', 'uas', 'libur_nasional', 'libur_akademik', 'deadline_tugas', 'deadline_skripsi', 'pengumuman_nilai', 'praktikum', 'wisuda', 'orientasi_mahasiswa_baru', 'pembayaran_ukt', 'pengisian_krs', 'pengisian_khs', 'cuti_akademik', 'seminar', 'presentasi_proyek', 'sidang', 'workshop', 'pengumuman_akademik', 'lainnya']),
        selectedEvent: null,
        
        // Computed
        get currentMonthYear() {
            const date = new Date(this.currentYear, this.currentMonth - 1);
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
        },

        get calendarWeeks() {
            const weeks = [];
            const firstDay = new Date(this.currentYear, this.currentMonth - 1, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            const endDate = new Date(lastDay);
            endDate.setDate(endDate.getDate() + (6 - lastDay.getDay()));

            let currentDate = new Date(startDate);
            const weekList = [];

            while (currentDate <= endDate) {
                const week = [];
                for (let i = 0; i < 7; i++) {
                    const isCurrentMonth = currentDate.getMonth() === firstDay.getMonth();
                    week.push(this.createDayObject(new Date(currentDate), isCurrentMonth));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                weekList.push(week);
            }

            return weekList;
        },

        get todaysEvents() {
            const todayStr = this.formatDate(this.today);
            const events = this.eventsByDate[todayStr] || [];
            
            // Filter and sort by time
            const filtered = this.filterEvents(events);
            return filtered.sort((a, b) => {
                // Events that haven't started yet come first (ascending by time)
                // Then completed events
                const timeA = a.waktu_mulai ? this.parseTime(a.waktu_mulai) : 0;
                const timeB = b.waktu_mulai ? this.parseTime(b.waktu_mulai) : 0;
                const now = this.getCurrentTime();
                
                // If both are future or both are past, sort by time ascending
                const aIsFuture = timeA >= now || !a.waktu_mulai;
                const bIsFuture = timeB >= now || !b.waktu_mulai;
                
                if (aIsFuture && bIsFuture) return timeA - timeB;
                if (!aIsFuture && !bIsFuture) return timeA - timeB;
                return aIsFuture ? -1 : 1; // Future events first
            });
        },

        get upcomingEvents() {
            const upcoming = [];
            const seen = new Set();
            
            // Iterate through events and collect upcoming ones
            this.events.forEach(event => {
                if (!this.visibleCategories.has(event.jenis_kegiatan) || seen.has(event.id)) return;
                
                const eventDate = new Date(event.tanggal_mulai);
                const tomorrow = new Date(this.today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                const limit = new Date(this.today);
                limit.setDate(limit.getDate() + 60);
                
                if (eventDate >= tomorrow && eventDate <= limit) {
                    upcoming.push(event);
                    seen.add(event.id);
                }
            });
            
            return upcoming
                .sort((a, b) => new Date(a.tanggal_mulai).getTime() - new Date(b.tanggal_mulai).getTime())
                .slice(0, 15);
        },

        get semesterStats() {
            const filtered = this.filterEvents(this.events);
            return {
                total: filtered.length,
                exams: filtered.filter(e => ['uts', 'uas'].includes(e.jenis_kegiatan)).length,
                holidays: filtered.filter(e => ['libur_nasional', 'libur_akademik', 'cuti_akademik'].includes(e.jenis_kegiatan)).length,
                deadlines: filtered.filter(e => ['deadline_tugas', 'deadline_skripsi'].includes(e.jenis_kegiatan)).length,
                others: filtered.filter(e => ['praktikum', 'seminar', 'workshop', 'presentasi_proyek', 'sidang', 'wisuda', 'orientasi_mahasiswa_baru', 'pembayaran_ukt', 'pengisian_krs', 'pengisian_khs', 'pengumuman_nilai', 'pengumuman_akademik', 'lainnya'].includes(e.jenis_kegiatan)).length
            };
        },

        // Methods
        createDayObject(date, isCurrentMonth) {
            const dateStr = this.formatDate(date);
            let dayEvents = (this.eventsByDate[dateStr] || []).filter(e => this.visibleCategories.has(e.jenis_kegiatan));
            
            // Sort events by time (waktu_mulai), with all-day events first
            dayEvents = dayEvents.sort((a, b) => {
                // All-day events first
                if (a.is_all_day && !b.is_all_day) return -1;
                if (!a.is_all_day && b.is_all_day) return 1;
                
                // Both all-day or both timed - sort by time
                const timeA = a.waktu_mulai || '00:00';
                const timeB = b.waktu_mulai || '00:00';
                return timeA.localeCompare(timeB);
            });
            
            return {
                date: date.getDate(),
                dateStr: dateStr,
                dateObj: date,
                isCurrentMonth: isCurrentMonth,
                isToday: this.isSameDay(date, this.today),
                isWeekend: date.getDay() === 0 || date.getDay() === 6,
                events: dayEvents,
                eventCount: dayEvents.length
            };
        },

        formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },

        isSameDay(date1, date2) {
            return this.formatDate(date1) === this.formatDate(date2);
        },

        filterEvents(events) {
            return events.filter(e => this.visibleCategories.has(e.jenis_kegiatan));
        },

        parseTime(timeStr) {
            if (!timeStr) return 0;
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + (minutes || 0);
        },

        getCurrentTime() {
            const now = new Date();
            return now.getHours() * 60 + now.getMinutes();
        },

        changeMonth(direction) {
            this.currentMonth += direction;
            if (this.currentMonth > 12) {
                this.currentMonth = 1;
                this.currentYear++;
            } else if (this.currentMonth < 1) {
                this.currentMonth = 12;
                this.currentYear--;
            }
            this.updateURL();
        },

        goToToday() {
            this.currentMonth = this.today.getMonth() + 1;
            this.currentYear = this.today.getFullYear();
            this.updateURL();
        },

        updateURL() {
            const params = new URLSearchParams({
                semester_id: this.selectedSemesterId,
                month: this.currentMonth,
                year: this.currentYear
            });
            window.location.href = `{{ route('mahasiswa.kalender-akademik.index') }}?${params.toString()}`;
        },

        showEventDetail(event) {
            this.selectedEvent = event;
            this.$nextTick(() => {
                document.getElementById('eventModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        },

        closeEventModal() {
            this.selectedEvent = null;
            document.getElementById('eventModal').classList.add('hidden');
            document.body.style.overflow = '';
        },

        toggleCategory(category) {
            // Create new Set to ensure reactivity
            const newCategories = new Set(this.visibleCategories);
            if (newCategories.has(category)) {
                newCategories.delete(category);
            } else {
                newCategories.add(category);
            }
            // Assign back to trigger Alpine reactivity on all computed properties
            this.visibleCategories = newCategories;
            // Explicitly access computed properties to ensure reactivity
            void this.calendarWeeks;
            void this.todaysEvents;
            void this.upcomingEvents;
            void this.semesterStats;
        }
    }));
});
</script>
