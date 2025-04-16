function ready() {
    let theme = localStorage.getItem('theme');

    if (!theme) {
        theme = 'light';
        localStorage.setItem('theme', theme);
    }

    document.querySelector('html')?.setAttribute('data-theme', theme);

    document.querySelectorAll('#themeSwitcher>li>input').forEach(function (input) {
        input.addEventListener('click', function () {
            localStorage.setItem('theme', input.value);
        });
    });
}

document.addEventListener("DOMContentLoaded", ready);
