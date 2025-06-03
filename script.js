const carouselSlide = document.querySelector('.carousel-slide');
const carouselButtons = document.querySelectorAll('.carousel-button');
const projectItems = document.querySelectorAll('.carousel-slide .project-item');

let slideIndex = 0;
const totalProjects = projectItems.length;

carouselButtons.forEach(button => {
    button.addEventListener('click', () => {
        if (button.classList.contains('prev')) {
            slideIndex = (slideIndex - 1 + totalProjects) % totalProjects;
        } else {
            slideIndex = (slideIndex + 1) % totalProjects;
        }
        updateCarousel();
    });
});

function updateCarousel() {
    const offset = -slideIndex * 100;
    carouselSlide.style.transform = `translateX(${offset}%)`;
}

// Initial carousel update
updateCarousel();

function updateDots() {
  const dots = document.querySelectorAll('.dot');
  dots.forEach(dot => dot.classList.remove('active'));
  dots[slideIndex].classList.add('active');
}

// Crear dots iniciales
const dotsContainer = document.querySelector('.carousel-dots');
for (let i = 0; i < totalItems; i++) {
  const dot = document.createElement('span');
  dot.classList.add('dot');
  if (i === 0) dot.classList.add('active');
  dot.addEventListener('click', () => {
    slideIndex = i;
    slides.style.transform = `translateX(-${slideIndex * 100}%)`;
    updateDots();
  });
  dotsContainer.appendChild(dot);
}
