/**
 * Alpine.js data component for the photo gallery lightbox.
 * Registered globally so it works in any _photo-grid partial.
 *
 * Usage:
 *   x-data="photoLightbox([{ src, caption, id }, ...])"
 */
export function photoLightbox(photos) {
    return {
        photos:       photos,
        isOpen:       false,
        currentIndex: 0,

        get currentPhoto() {
            return this.photos[this.currentIndex] ?? null;
        },

        open(index) {
            this.currentIndex = index;
            this.isOpen = true;
            document.body.style.overflow = 'hidden'; // prevent background scroll
        },

        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        prev() {
            if (! this.isOpen) return;
            this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
        },

        next() {
            if (! this.isOpen) return;
            this.currentIndex = (this.currentIndex + 1) % this.photos.length;
        },
    };
}
