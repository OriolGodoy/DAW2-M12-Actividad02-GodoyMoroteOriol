let currentIndex = 0;

const slider = document.querySelector('.slider');
const options = document.querySelectorAll('.option');
const totalOptions = options.length;
const itemsPerMove = 3;  // Mover 3 elementos a la vez

const nextArrow = document.getElementById('nextArrow');
const prevArrow = document.getElementById('prevArrow');

nextArrow.addEventListener('click', () => {
    if (currentIndex + itemsPerMove < totalOptions) {
        currentIndex += itemsPerMove;
        slider.style.transform = `translateX(-${(100 / 3) * currentIndex}%)`; // Ajuste para mover de 3 en 3
    }
});

prevArrow.addEventListener('click', () => {
    if (currentIndex - itemsPerMove >= 0) {
        currentIndex -= itemsPerMove;
        slider.style.transform = `translateX(-${(100 / 3) * currentIndex}%)`; // Ajuste para mover de 3 en 3
    }
});
