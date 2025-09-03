// Accessibility enhancements for Lovely Paws Rescue

document.addEventListener('DOMContentLoaded', function() {
    // Screen reader announcements
    const announcements = document.getElementById('sr-announcements');
    
    function announce(message) {
        if (announcements) {
            announcements.textContent = message;
            setTimeout(() => {
                announcements.textContent = '';
            }, 1000);
        }
    }

    // Focus management for Livewire updates
    document.addEventListener('livewire:navigated', function() {
        // Focus main content after navigation
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            mainContent.focus();
        }
    });

    // Handle form submission announcements
    document.addEventListener('livewire:init', function() {
        Livewire.on('form-submitted', function(data) {
            announce(data.message || 'Form submitted successfully');
        });

        Livewire.on('form-error', function(data) {
            announce(data.message || 'Please check the form for errors');
        });
    });

    // Keyboard navigation for mobile menu
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close mobile menu on escape
            const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
            if (mobileMenuButton && mobileMenuButton.getAttribute('aria-expanded') === 'true') {
                mobileMenuButton.click();
                mobileMenuButton.focus();
            }
        }
    });

    // Enhanced focus management for dynamic content
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                // Check for new form errors
                const newErrors = mutation.target.querySelectorAll('[role="alert"]:not([data-announced])');
                newErrors.forEach(function(error) {
                    error.setAttribute('data-announced', 'true');
                    announce('Form error: ' + error.textContent);
                });
            }
        });
    });

    // Observe the document for changes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Improve button accessibility
    document.querySelectorAll('button[aria-pressed]').forEach(function(button) {
        button.addEventListener('click', function() {
            const pressed = this.getAttribute('aria-pressed') === 'true';
            this.setAttribute('aria-pressed', !pressed);
        });
    });

    // Handle loading states
    document.addEventListener('livewire:loading', function(e) {
        const target = e.target;
        if (target.tagName === 'BUTTON' || target.tagName === 'FORM') {
            announce('Loading, please wait');
        }
    });

    document.addEventListener('livewire:loaded', function(e) {
        announce('Content loaded');
    });

    // Skip link functionality
    const skipLink = document.querySelector('.skip-nav');
    if (skipLink) {
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.focus();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Enhance form validation feedback
    document.querySelectorAll('input, textarea, select').forEach(function(field) {
        field.addEventListener('invalid', function(e) {
            e.preventDefault();
            const errorMessage = this.validationMessage;
            announce('Validation error: ' + errorMessage);
        });
    });

    // Improve carousel/slider accessibility if present
    document.querySelectorAll('[role="region"][aria-label*="carousel"], [role="region"][aria-label*="slider"]').forEach(function(carousel) {
        // Add keyboard navigation
        carousel.addEventListener('keydown', function(e) {
            const items = this.querySelectorAll('[role="tabpanel"], .carousel-item');
            const currentIndex = Array.from(items).findIndex(item => item.classList.contains('active'));
            
            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                e.preventDefault();
                items[currentIndex - 1].focus();
            } else if (e.key === 'ArrowRight' && currentIndex < items.length - 1) {
                e.preventDefault();
                items[currentIndex + 1].focus();
            }
        });
    });

    // Enhance modal accessibility if present
    document.querySelectorAll('[role="dialog"]').forEach(function(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            // Focus first element when modal opens
            focusableElements[0].focus();
            
            // Trap focus within modal
            modal.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            });
        }
    });

    // Reduce motion for users who prefer it
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.style.setProperty('--animation-duration', '0.01ms');
        document.documentElement.style.setProperty('--transition-duration', '0.01ms');
    }

    // High contrast mode detection and adjustments
    if (window.matchMedia('(prefers-contrast: high)').matches) {
        document.body.classList.add('high-contrast');
    }

    // Color scheme preference
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('prefers-dark');
    }
});

// Export functions for use in other scripts
window.AccessibilityHelpers = {
    announce: function(message) {
        const announcements = document.getElementById('sr-announcements');
        if (announcements) {
            announcements.textContent = message;
            setTimeout(() => {
                announcements.textContent = '';
            }, 1000);
        }
    },
    
    focusElement: function(selector) {
        const element = document.querySelector(selector);
        if (element) {
            element.focus();
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    },
    
    trapFocus: function(container) {
        const focusableElements = container.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        container.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        });
        
        firstElement.focus();
    }
};