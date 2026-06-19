document.addEventListener('DOMContentLoaded', function() {
    console.log('User page loaded');

    const elements = document.querySelectorAll('.profile-card, .stat-card, .score-item');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.animation = `fadeInUp 0.6s ease forwards`;
        element.style.animationDelay = `${index * 0.1}s`;
    });
});

const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);


