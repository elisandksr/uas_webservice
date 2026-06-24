// Script for RecipeHub

document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus resep ini?')) {
                e.preventDefault();
            }
        });
    });
});
