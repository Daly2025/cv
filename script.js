const carouselSlide = document.querySelector('.carousel-slide');
const carouselButtons = document.querySelectorAll('.carousel-button');
const projectItems = document.querySelectorAll('.project-item');
const dotsContainer = document.querySelector('.carousel-dots');

let slideIndex = 0;
const totalProjects = projectItems.length;

// Crear dots iniciales
projectItems.forEach((_, index) => {
  const dot = document.createElement('span');
  dot.className = 'dot' + (index === 0 ? ' active' : '');
  dot.addEventListener('click', () => goToSlide(index));
  dotsContainer.appendChild(dot);
});

carouselButtons.forEach(button => {
  button.addEventListener('click', () => {
    slideIndex = button.classList.contains('prev') 
      ? (slideIndex - 1 + totalProjects) % totalProjects
      : (slideIndex + 1) % totalProjects;
    updateCarousel();
  });
});

function updateCarousel() {
  carouselSlide.style.transform = `translateX(-${slideIndex * 25}%)`;
  updateDots();
}

function updateDots() {
  document.querySelectorAll('.dot').forEach((dot, index) => {
    dot.classList.toggle('active', index === slideIndex);
  });
}

function goToSlide(index) {
  slideIndex = index;
  updateCarousel();
}

// Inicializar carrusel
updateCarousel();
