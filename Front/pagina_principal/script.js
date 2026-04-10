document.addEventListener('DOMContentLoaded', () => {

    // Efecto en la barra de navegación al hacer scroll
    var navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.padding = '10px 0';
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
        } else {
            navbar.style.padding = '15px 0';
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.03)';
        }
    });

    // Menú de navegación móvil
    var mobileMenuBtn = document.getElementById('mobileMenuBtn');
    var navLinks = document.getElementById('navLinks');

    mobileMenuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Gestión de botones de reserva
    var botonesReserva = document.querySelectorAll('.btn-reserva');
    botonesReserva.forEach(boton => {
        boton.addEventListener('click', (e) => {
            var servicio = e.target.getAttribute('data-servicio');
            alert(`Has seleccionado: ${servicio}. Cuando conectemos PHP, esto guardará la cita en la base de datos.`);
        });
    });

    // Botón principal del Hero
    var btnReservarHero = document.getElementById('btnReservarHero');
    if (btnReservarHero) {
        btnReservarHero.addEventListener('click', () => {
            document.querySelector('#servicios').scrollIntoView({
                behavior: 'smooth'
            });
        });
    }

    // Simulación de buscador
    var searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            var query = e.target.value.trim();
            if (query) {
                console.log(`Buscando terapia: ${query}`);
            }
        }
    });

    // Lógica del Área de Usuario (Login / Logout)
    var guestState = document.getElementById('guestState');
    var loggedState = document.getElementById('loggedState');
    var btnLoginToggle = document.getElementById('btnLoginToggle');
    var btnLogout = document.getElementById('btnLogout');
    var userMenuBtn = document.getElementById('userMenuBtn');
    var userDropdown = document.getElementById('userDropdown');

    // Simular Entrar / Registro
    btnLoginToggle.addEventListener('click', () => {
        guestState.classList.add('hidden');
        loggedState.classList.remove('hidden');
    });

    // Simular Cerrar Sesión
    btnLogout.addEventListener('click', (e) => {
        e.preventDefault();
        loggedState.classList.add('hidden');
        guestState.classList.remove('hidden');
        userDropdown.classList.remove('show');
    });

    // Desplegar menú de perfil
    userMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
    });

    // Cerrar el menú si se hace clic fuera
    document.addEventListener('click', (e) => {
        if (!loggedState.contains(e.target)) {
            userDropdown.classList.remove('show');
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // 1. Funcionalidad del Menú de Usuario (Dropdown)
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuBtn && userDropdown) {
        // Abrir/Cerrar al hacer clic en el botón
        userMenuBtn.addEventListener('click', function(event) {
            event.stopPropagation(); // Evita que el clic se propague al documento
            userDropdown.classList.toggle('show');
        });

        // Cerrar el menú si el usuario hace clic fuera de él
        document.addEventListener('click', function(event) {
            if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }

    // 2. Funcionalidad del Menú Móvil (Hamburguesa) - Ya que estamos, lo dejamos funcionando
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
});