// Initialize form handlers and interactive elements
document.addEventListener('DOMContentLoaded', function() {

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


