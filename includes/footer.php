<?php
// includes/footer.php
?>
    <footer class="footer-custom">
        <div class="footer-container">
            
            <div>
                <h3 class="footer-title">LC Quiromasajes</h3>
                <p class="footer-subtitle">Centro de Terapias y Bienestar</p>
            </div>
            
            <div class="footer-contact">
                <a href="https://www.google.com/maps/search/?api=1&query=Maria+Guerrero+1+04740+Roquetas+de+Mar+Almeria" 
                    target="_blank" rel="noopener noreferrer" 
                    class="footer-contact-item enlace-contacto">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    María Guerrero, 1, 04740 Roquetas de Mar, Almería, Spain
                </a>
                <a href="tel:+34615487598" class="footer-contact-item enlace-telefono">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    615 487 598
                </a>
            </div>

            <div class="footer-social">
                <a href="https://www.instagram.com/lidiacarmonaquiro/" target="_blank" rel="noopener noreferrer" class="social-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                </a>
                <a href="http://facebook.com/lidia.carmonarodriguez.3/" target="_blank" rel="noopener noreferrer" class="social-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                </a>
            </div>
            
            <div class="footer-bottom">
                <p style="margin: 0;">
                    &copy; <?php echo date('Y'); ?> LC Quiromasajes. Proyecto de Fin de Ciclo DAW. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>