// Funcionalidad del carrusel
let slideIndex = 0;
const slides = document.querySelector('.carousel-slide');
const items = document.querySelectorAll('.carousel-item');
const totalItems = items.length;

document.querySelector('.prev').addEventListener('click', () => {
  slideIndex = (slideIndex > 0) ? slideIndex - 1 : totalItems - 1;
  slides.style.transform = `translateX(-${slideIndex * 100}%)`;
  updateDots();
});

document.querySelector('.next').addEventListener('click', () => {
  slideIndex = (slideIndex < totalItems - 1) ? slideIndex + 1 : 0;
  slides.style.transform = `translateX(-${slideIndex * 100}%)`;
  updateDots();
});

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