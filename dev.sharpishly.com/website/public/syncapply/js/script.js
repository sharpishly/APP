document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    let currentSlide = 0;
    const slideInterval = 5000; // Change image every 5 seconds (5000ms)

    function nextSlide() {
        // Remove 'active' class from current slide
        slides[currentSlide].classList.remove('active');

        // Move to the next slide, loop back to start if at the end
        currentSlide = (currentSlide + 1) % slides.length;

        // Add 'active' class to the new current slide
        slides[currentSlide].classList.add('active');
    }

    // Start the automatic slideshow
    setInterval(nextSlide, slideInterval);

    // Optional: Preload images to prevent flickering
    // This is less critical with background images and object-fit cover,
    // but can be beneficial for very large or complex image sets.
    slides.forEach(slide => {
        const img = slide.querySelector('img');
        if (img && img.complete) {
            // Image is already loaded
        } else if (img) {
            img.addEventListener('load', () => {
                // Image loaded
            });
        }
    });
});