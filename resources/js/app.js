import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Fullscreen image logic for lyrics images

document.addEventListener('DOMContentLoaded', function () {
    // Delegate for all images with the class 'lyrics-fullscreen-img'
    document.body.addEventListener('click', function (e) {
        if (e.target.classList.contains('lyrics-fullscreen-img')) {
            if (!document.fullscreenElement) {
                e.target.requestFullscreen();
            }
        }
    });
    document.body.addEventListener('dblclick', function (e) {
        if (e.target.classList.contains('lyrics-fullscreen-img')) {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }
        }
    });
});
