// Additional JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add floating animation to certain elements
    const floatingElements = document.querySelectorAll('.float');
    floatingElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.5}s`;
    });

    // Add glow effect on hover for buttons
    const buttons = document.querySelectorAll('button, .btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.classList.add('pulse-glow');
        });
        
        button.addEventListener('mouseleave', function() {
            this.classList.remove('pulse-glow');
        });
    });

    // Auto-hide mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.querySelector('[x-data*="mobileMenu"]');
        if (mobileMenu && !mobileMenu.contains(event.target)) {
            // This would work with Alpine.js to close the menu
        }
    });
});

// Countdown timer function (enhanced)
function createCountdown(targetDate) {
    return {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        
        init() {
            this.updateCountdown();
            setInterval(() => {
                this.updateCountdown();
            }, 1000);
        },
        
        updateCountdown() {
            const target = new Date(targetDate);
            const now = new Date();
            const diff = target - now;
            
            if (diff > 0) {
                this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
            } else {
                this.days = this.hours = this.minutes = this.seconds = 0;
            }
        }
    }
}

// Enhanced form validation
function validateForm(formElement) {
    const inputs = formElement.querySelectorAll('input[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('border-red-500');
            isValid = false;
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    return isValid;
}

// Add loading states to forms
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
        }
    });
});