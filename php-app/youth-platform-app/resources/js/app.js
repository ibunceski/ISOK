// Initialize form handlers and interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Prevent double form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && form.dataset.submitted) {
                e.preventDefault();
                return false;
            }
            if (submitBtn) {
                form.dataset.submitted = 'true';
                submitBtn.disabled = true;
            }
        });
    });

    // Auto-dismiss flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('[class*="bg-green"], [class*="bg-red"]');
    flashMessages.forEach(msg => {
        if (msg.classList.contains('rounded-lg') && msg.classList.contains('px-4')) {
            setTimeout(() => {
                msg.style.transition = 'opacity 0.3s ease';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            }, 5000);
        }
    });
});


