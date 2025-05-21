import './bootstrap';
import 'animate.css';

document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('section');
    let lastScrollY = window.scrollY;

    const handleScroll = () => {
        const currentScrollY = window.scrollY;

        // Check if scrolling down
        if (currentScrollY > lastScrollY) {
            sections.forEach((section) => {
                const rect = section.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                // Apply entry animation when the section enters the viewport
                if (rect.top >= 0 && rect.top <= windowHeight * 0.75) {
                    section.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }

        lastScrollY = currentScrollY; // Update the last scroll position
    };

    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Trigger on page load
});
