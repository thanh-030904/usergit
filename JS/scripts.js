// Hiệu ứng con trỏ chuột
document.querySelectorAll('.btn-view, .btn-add-to-cart').forEach(button => {
    button.addEventListener('mouseover', () => {
        button.style.transform = 'scale(1.1)';
    });
    button.addEventListener('mouseout', () => {
        button.style.transform = 'scale(1)';
    });
});
