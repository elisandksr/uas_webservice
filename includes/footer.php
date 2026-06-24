</main>

<footer class="footer">
    <div class="container">
        <div class="footer-container">
            <div class="footer-brand">
                <h2>Recipe<span>Hub</span></h2>
                <p>Buku resep digital andalan keluarga. Menyimpan masakan favorit dan mengeksplorasi ribuan rasa dari seluruh dunia kini lebih mudah dan menyenangkan.</p>
            </div>
            <div class="footer-links">
                <div class="footer-social">
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date("Y") ?> <strong>RecipeHub</strong>. Dibuat dengan ❤️ untuk pecinta kuliner.</p>
        </div>
    </div>
</footer>

<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
</script>
<script src="<?= $base_url ?>/assets/js/script.js"></script>
</body>
</html>
