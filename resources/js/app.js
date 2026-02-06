import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function (e) {
            const fileName = e.target.files[0]?.name;
            const labelText = this.closest('label').querySelector('span');
            if (fileName) {
                labelText.textContent = fileName;
            }

            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('image-preview');
                    const previewContainer = document.getElementById('image-preview-container');

                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }

                    if (previewContainer) {
                        previewContainer.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
