import './bootstrap';
import './accessibility';

// Listen for image selection events
document.addEventListener('DOMContentLoaded', () => {
    window.addEventListener('image-selected', (event) => {
        console.log('Image selected:', event.detail);
    });
});