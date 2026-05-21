
document.addEventListener('DOMContentLoaded', () => {

    // Efecto en la barra de navegación al hacer scroll
    const navbar = document.querySelector('.navbar-custom');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.padding = '10px 0';
                navbar.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.08)';
            } else {
                navbar.style.padding = '15px 0';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.04)';
            }
        });
    }

    // Menú de navegación móvil (Hamburguesa)
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.querySelector('.nav-links-list');

    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // Menú de Usuario (Dropdown)
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuBtn && userDropdown) {
        // Abrir/Cerrar
        userMenuBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (event) => {
            if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }

    // Simulación de buscador y botones
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (query) console.log(`Buscando terapia: ${query}`);
            }
        });
    }

    // Scroll suave desde el botón del Hero
    const btnReservarHero = document.getElementById('btnReservarHero');
    if (btnReservarHero) {
        btnReservarHero.addEventListener('click', (e) => {
            const servicios = document.querySelector('#servicios');
            if (servicios) {
                e.preventDefault(); 
                servicios.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});