document.addEventListener('DOMContentLoaded', () => {

    // 1. Efecto en la barra de navegación al hacer scroll
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

    // 2. Menú de navegación móvil
    const mobileMenuBtn = document.getElementById('mobileMenuToggle');
    const navMenu = document.getElementById('navMenu');

    if (mobileMenuBtn && navMenu) {
        // Limpiar spans duplicados — dejar solo 3
        const spans = mobileMenuBtn.querySelectorAll('span');
        spans.forEach((span, i) => {
            if (i >= 3) span.remove();
        });

        mobileMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            navMenu.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
        });
    }

    // 3. Menú de Usuario (Dropdown)
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            userDropdown.classList.toggle('show');
        });
    }

    // Cierre global al hacer clic fuera
    document.addEventListener('click', (event) => {
        if (userDropdown && userDropdown.classList.contains('show')) {
            if (!userMenuBtn?.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('show');
            }
        }
        if (navMenu && navMenu.classList.contains('active')) {
            if (!navMenu.contains(event.target) && !mobileMenuBtn?.contains(event.target)) {
                navMenu.classList.remove('active');
                mobileMenuBtn?.classList.remove('active');
            }
        }
    });

    // 4. Scroll suave
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