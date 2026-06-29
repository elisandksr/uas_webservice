</main>

<style>
    .premium-footer {
        background: linear-gradient(135deg, #FF6B35 0%, #FF8F60 100%); 
        color: white; 
        position: relative; 
        margin-top: 8rem; 
        padding-top: 5rem; 
        overflow: hidden;
    }
    .premium-footer-grid {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 2rem;
        margin-bottom: 3rem;
    }
    .premium-footer-col {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .footer-nav-link {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        padding: 5px 0;
    }
    .footer-nav-link:hover {
        color: white;
        transform: translateX(5px);
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .social-btn {
        width: 45px; 
        height: 45px; 
        background: rgba(255,255,255,0.2); 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: white; 
        font-size: 1.2rem; 
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        border: 1px solid rgba(255,255,255,0.3);
    }
    .social-btn:hover {
        background: white;
        color: #FF6B35;
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .premium-footer-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        .footer-bottom-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>

<footer class="premium-footer">
    <!-- Decorative Wave SVG -->
    <div style="position: absolute; top: -1px; left: 0; width: 100%; overflow: hidden; line-height: 0;">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="position: relative; display: block; width: calc(134% + 1.3px); height: 70px; transform: rotateY(180deg);">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" style="fill: var(--c-bg);"></path>
        </svg>
    </div>

    <div class="container" style="position: relative; z-index: 2; padding-bottom: 2rem;">
        <div class="premium-footer-grid">
            
            <div class="premium-footer-col" style="align-items: center;">
                <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; color: white; display: flex; align-items: center; gap: 12px; font-family: var(--f-heading); text-shadow: 0 2px 10px rgba(0,0,0,0.1); justify-content: center;">
                    <div style="background: white; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                        <i class="fa-solid fa-utensils" style="color: #FF6B35; font-size: 1.5rem;"></i>
                    </div>
                    Recipe<span style="color: #FFD23F;">Hub</span>
                </h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; line-height: 1.7; max-width: 600px; margin-bottom: 1.5rem; text-shadow: 0 1px 3px rgba(0,0,0,0.05); text-align: center;">Buku resep digital andalan keluarga. Menyimpan masakan favorit dan mengeksplorasi ribuan rasa kini lebih mudah, rapi, dan menyenangkan.</p>
                
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

        </div>

        <div class="footer-bottom-flex" style="border-top: 1px solid rgba(255,255,255,0.3); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; color: rgba(255,255,255,0.9); font-size: 1rem;">
            <p style="margin: 0;">&copy; <?= date("Y") ?> <strong style="color: white; font-weight: 700;">RecipeHub</strong>. Hak Cipta Dilindungi.</p>
            <p style="margin: 0; display: flex; align-items: center; gap: 6px; justify-content: center;">Dibuat dengan <i class="fa-solid fa-heart" style="color: white;"></i> untuk pecinta kuliner.</p>
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
