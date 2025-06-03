let slideIndex = 0;
const slides = document.querySelectorAll('.carousel-slide');
const prevButton = document.querySelector('.prev-button');
const nextButton = document.querySelector('.next-button');

function showSlides() {
    slides.forEach((slide, index) => {
        slide.style.display = (index === slideIndex) ? 'block' : 'none';
    });
}

function plusSlides(n) {
    slideIndex += n;
    if (slideIndex >= slides.length) {
        slideIndex = 0;
    }
    if (slideIndex < 0) {
        slideIndex = slides.length - 1;
    }
    showSlides();
}

// Event Listeners
if (prevButton) {
    prevButton.addEventListener('click', () => plusSlides(-1));
}

if (nextButton) {
    nextButton.addEventListener('click', () => plusSlides(1));
}

document.addEventListener('DOMContentLoaded', () => {
    showSlides();
});