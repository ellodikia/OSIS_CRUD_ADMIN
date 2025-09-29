    // JavaScript untuk modal dan navigasi mobile
        document.addEventListener('DOMContentLoaded', function() {
        // Modal functionality
        const modalOverlay = document.querySelector('.modal-overlay');
        const modals = document.querySelectorAll('.modal');
        const clickableElements = document.querySelectorAll('.clickable');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        
        // Open modal
        clickableElements.forEach(element => {
            element.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(`modal-${modalId}`);
            
            if (modal) {
                modal.classList.add('active');
                modalOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            });
        });
        
        // Close modal
        function closeModal() {
            modals.forEach(modal => modal.classList.remove('active'));
            modalOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        closeModalButtons.forEach(button => {
            button.addEventListener('click', closeModal);
        });
        
        modalOverlay.addEventListener('click', closeModal);
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
        
        // Mobile menu functionality
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const nav = document.querySelector('.nav');
        const navOverlay = document.querySelector('.nav-overlay');
        
        mobileMenuBtn.addEventListener('click', function() {
            nav.classList.toggle('active');
            navOverlay.classList.toggle('active');
        });
        
        navOverlay.addEventListener('click', function() {
            nav.classList.remove('active');
            navOverlay.classList.remove('active');
        });
        });