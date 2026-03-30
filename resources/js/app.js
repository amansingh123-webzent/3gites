import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { photoLightbox } from './gallery.js';

Alpine.plugin(collapse);

// Register the photoLightbox component globally
Alpine.data('photoLightbox', photoLightbox);

window.Alpine = Alpine;
Alpine.start();

// Scroll-reveal
document.addEventListener('DOMContentLoaded', () => {
    const revealEls = document.querySelectorAll('.reveal');
    if (!revealEls.length) return;
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    revealEls.forEach(el => observer.observe(el));
});
