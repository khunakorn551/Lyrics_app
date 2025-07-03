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

    // Prevent right-click and dragging on all images (protects images from easy download)
    document.querySelectorAll('img').forEach(function(img) {
        // Disable right-click context menu
        img.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        // Disable dragging
        img.setAttribute('draggable', 'false');
    });
});
