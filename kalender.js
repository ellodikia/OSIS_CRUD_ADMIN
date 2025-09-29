    // Real-time clock
        function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        };
        document.getElementById('current-datetime').textContent = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Calendar Data
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        const events = [
        { 
            id: 1, 
            date: '8 Sep', 
            title: 'Hari Olahraga Nasional', 
            time: '08.30 - 12.00 WIB',
            location: 'Lapangan Sekolah', 
            person: ' Ketua Panitia',
            description: 'Lomba memperingati hari olahraga nasional',
            preparations: ['Membawa daftar peain', 'Surat perjanjian', 'Membawa perlengkapan bertanding'] 
        },
        { 
            id: 2, 
            date: '9 Sep', 
            title: 'Hari Olahraga Nasional', 
            time: '08.00 - 12.00 WIB',
            location: 'Lapangan Sekolah', 

            person: ' Ketua Panitia',
            description: 'Lomba memperingati hari olahraga nasional',
            preparations: ['Membawa daftar peain', 'Surat perjanjian', 'Membawa perlengkapan bertanding'] 
        },
        { 
            id: 3, 
            date: '13 Sep', 
            title: 'Senam Pagi', 
            time: '07.30 - s/d',
            location: 'Lapangan sekolah', 
            person: 'OSIS',
            description: 'Senam rutin setiap sabtu pagi unit SMP, SMA',
            preparations: ['Pakaian Olahraga',] 
        },
        { 
            id: 4, 
            date: '', 
            title: '', 
            time: '',
            location: '', 
            person: '',
            description: '',
            preparations: [] 
        }
        ];

        function generateCalendar(month, year) {
        const calendarTable = document.getElementById('calendar-table');
        const monthYearDisplay = document.getElementById('current-month-year');
        calendarTable.innerHTML = '';
        
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        
        monthYearDisplay.textContent = `${monthNames[month]} ${year}`;
        
        const dayNames = ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"];
        const headerRow = document.createElement('tr');
        
        dayNames.forEach(day => { 
            const th = document.createElement('th'); 
            th.textContent = day; 
            headerRow.appendChild(th); 
        });
        
        calendarTable.appendChild(headerRow);

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDay = new Date(year, month, 1).getDay();
        const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;
        
        let date = 1;
        
        for (let i = 0; i < 6; i++) {
            if (date > daysInMonth) break;
            
            const row = document.createElement('tr');
            
            for (let j = 0; j < 7; j++) {
            const cell = document.createElement('td');
            
            if (i === 0 && j < adjustedFirstDay) {
                cell.textContent = '';
            } else if (date > daysInMonth) {
                cell.textContent = '';
            } else {
                cell.textContent = date;
                const today = new Date();
                
                if (date === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                cell.classList.add('today');
                }
                
                const eventDate = `${date} ${monthNames[month].substring(0, 3)}`;
                const event = events.find(e => e.date === eventDate);
                
                if (event) { 
                cell.classList.add('has-event'); 
                cell.setAttribute('data-event-id', event.id); 
                }
                
                date++;
            }
            
            row.appendChild(cell);
            }
            
            calendarTable.appendChild(row);
        }
        
        // Trigger section animation
        const sections = document.querySelectorAll('.section');
        sections.forEach(section => {
            if (isElementInViewport(section)) {
            section.classList.add('visible');
            }
        });
        }

        function renderEventsList() {
        const list = document.getElementById('events-list');
        list.innerHTML = '';
        
        events.forEach(event => {
            const li = document.createElement('li');
            li.innerHTML = `
            <div class="event-date">${event.date}</div>
            <div class="event-details">
                <h4>${event.title}</h4>
                <p>${event.location}, ${event.time}</p>
            </div>
            `;
            
            li.addEventListener('click', () => openModal(event));
            list.appendChild(li);
        });
        }

        document.getElementById('prev-month').addEventListener('click', () => {
        currentMonth--;
        
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        
        generateCalendar(currentMonth, currentYear);
        });
        
        document.getElementById('next-month').addEventListener('click', () => {
        currentMonth++;
        
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        
        generateCalendar(currentMonth, currentYear);
        });

        function openModal(event) {
        document.getElementById('modal-title').textContent = event.title;
        document.getElementById('modal-date').textContent = `${event.date} ${currentYear}`;
        document.getElementById('modal-time').textContent = event.time;
        document.getElementById('modal-location').textContent = event.location;
        document.getElementById('modal-person').textContent = event.person;
        document.getElementById('modal-description').textContent = event.description;
        
        const list = document.getElementById('modal-preparations');
        list.innerHTML = '';
        
        event.preparations.forEach(prep => {
            const li = document.createElement('li');
            li.textContent = prep;
            list.appendChild(li);
        });
        
        document.getElementById('event-modal').style.display = 'flex';
        }

        document.querySelector('.close-modal').addEventListener('click', () => {
        document.getElementById('event-modal').style.display = 'none';
        });
        
        document.querySelector('.share-btn').addEventListener('click', () => {
        alert('Fitur berbagi akan membuka pilihan platform sosial media.');
        });
        
        document.querySelector('.reminder-btn').addEventListener('click', () => {
        alert('Pengingat telah ditambahkan ke kalender Anda!');
        });

        // Initialize calendar and events
        generateCalendar(currentMonth, currentYear);
        renderEventsList();

        // Event delegation for calendar cells
        document.addEventListener('click', e => {
        if (e.target.classList.contains('has-event')) {
            const id = parseInt(e.target.getAttribute('data-event-id'));
            const event = events.find(ev => ev.id === id);
            
            if (event) openModal(event);
        }
        });

        // Mobile menu functionality
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const nav = document.querySelector('.nav');
        const overlay = document.querySelector('.nav-overlay');
        const body = document.body;
        
        if (mobileMenuBtn && nav) {
        mobileMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMobileMenu();
        });
        
        if (overlay) {
            overlay.addEventListener('click', () => {
            closeMobileMenu();
            });
        }
        
        // Close menu when clicking on a link
        nav.addEventListener('click', (e) => {
            if (e.target.tagName === 'A') {
            closeMobileMenu();
            }
        });
        
        function toggleMobileMenu() {
            nav.classList.toggle('active');
            
            if (overlay) {
            overlay.classList.toggle('active');
            }
            
            mobileMenuBtn.innerHTML = nav.classList.contains('active') ? '✕' : '☰';
            
            // Prevent body scroll when menu is open
            if (nav.classList.contains('active')) {
            body.style.overflow = 'hidden';
            } else {
            body.style.overflow = '';
            }
        }
        
        function closeMobileMenu() {
            if (nav.classList.contains('active')) {
            nav.classList.remove('active');
            
            if (overlay) {
                overlay.classList.remove('active');
            }
            
            mobileMenuBtn.innerHTML = '☰';
            body.style.overflow = '';
            }
        }
        }

        // Function to check if element is in viewport for animations
        function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.9 &&
            rect.bottom >= 0
        );
        }

        // Handle scroll animation for sections
        function handleScrollAnimation() {
        const sections = document.querySelectorAll('.section');
        
        sections.forEach(section => {
            if (isElementInViewport(section)) {
            section.classList.add('visible');
            }
        });
        }
        
        window.addEventListener('scroll', handleScrollAnimation);
        window.addEventListener('load', handleScrollAnimation);
        handleScrollAnimation();