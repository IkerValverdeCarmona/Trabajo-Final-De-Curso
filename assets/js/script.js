document.addEventListener('DOMContentLoaded', () => {

    // 1. Efecto en la barra de navegación al hacer scroll
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.padding = '10px 0';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
            } else {
                navbar.style.padding = '15px 0';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.03)';
            }
        });
    }

    // 2. Menú de navegación móvil (Hamburguesa)
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // 3. Menú de Usuario (Dropdown)
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            
            if (userDropdown.style.display === "none" || userDropdown.style.display === "") {
                userDropdown.style.display = "block";
                setTimeout(() => userDropdown.classList.add('show'), 10);
            } else {
                userDropdown.classList.remove('show');
                setTimeout(() => userDropdown.style.display = "none", 300);
            }
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (event) => {
            if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('show');
                setTimeout(() => userDropdown.style.display = "none", 300);
            }
        });
    }

    // 4. Simulación de buscador y botones
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (query) console.log(`Buscando terapia: ${query}`);
            }
        });
    }

    const btnReservarHero = document.getElementById('btnReservarHero');
    if (btnReservarHero) {
        btnReservarHero.addEventListener('click', () => {
            document.querySelector('#servicios').scrollIntoView({ behavior: 'smooth' });
        });
    }
});