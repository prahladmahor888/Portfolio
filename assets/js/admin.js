/**
 * Admin Panel JavaScript
 * Form validation and UI interactions
 */

// Image preview on file select
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                let preview = input.parentElement.querySelector('.image-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.className = 'image-preview';
                    preview.style.cssText = 'margin-top: 1rem;';
                    input.parentElement.appendChild(preview);
                }
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 300px; border-radius: 8px;">`;
            };
            reader.readAsDataURL(file);
        }
    });
});

// Auto-hide flash messages
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.3s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);

// Confirm delete actions
document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
    form.addEventListener('submit', function (e) {
        if (!confirm(form.getAttribute('onsubmit').match(/'([^']+)'/)[1])) {
            e.preventDefault();
        }
    });
});
