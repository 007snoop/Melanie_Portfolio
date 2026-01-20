// admin page
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.box-form').forEach(form => {
        form.addEventListener('submit', () => {
            form.querySelectorAll('[contenteditable]').forEach(el => {
                const field = el.dataset.field;
                const hidden = form.querySelector(`input[name="${field}"]`);
                if (hidden) {
                    hidden.value = el.innerHTML.trim();
                }
            });
        });
    });
});