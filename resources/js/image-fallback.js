// Image fallback handler for broken images
document.addEventListener('DOMContentLoaded', function() {
    // Handle image loading errors
    document.addEventListener('error', function(e) {
        if (e.target.tagName === 'IMG') {
            const img = e.target;
            const src = img.src;
            
            // If it's a Storage URL that failed, try asset URL
            if (src.includes('/storage/') && !img.dataset.fallbackAttempted) {
                img.dataset.fallbackAttempted = 'true';
                const filename = src.split('/').pop();
                const path = src.split('/storage/')[1];
                img.src = window.location.origin + '/uploads/' + path;
            }
            // If asset URL also fails, hide the image or show placeholder
            else if (img.dataset.fallbackAttempted) {
                img.style.display = 'none';
                
                // Show next sibling if it's a fallback element
                if (img.nextElementSibling) {
                    img.nextElementSibling.style.display = 'block';
                }
            }
        }
    }, true);
});