const options = document.querySelectorAll('.option');

options.forEach(option => {
    option.addEventListener('mouseover', () => {
        options.forEach(opt => {
            if (opt !== option) {
                opt.style.transform = 'scale(0.9)';
            }
        });
        option.style.transform = 'scale(1)';
        option.style.transition = 'transform 0.3s';
    });

    option.addEventListener('mouseout', () => {
        options.forEach(opt => {
            opt.style.transform = 'scale(1)';
        });
    });

});
