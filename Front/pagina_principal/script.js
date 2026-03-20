document.addEventListener('DOMContentLoaded', () => {

    //Efecto en la barra de navegación al hacer scroll
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

    //Menú de navegación móvil (Menú Hamburguesa)
    var mobileMenuBtn = document.getElementById('mobileMenuBtn');
    var navLinks = document.getElementById('navLinks');

    mobileMenuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Gestión de botones de reserva en las tarjetas
    var botonesReserva = document.querySelectorAll('.btn-reserva');
    botonesReserva.forEach(boton => {
        boton.addEventListener('click', (e) => {
            var servicio = e.target.getAttribute('data-servicio');
            alert(`Has seleccionado el servicio: ${servicio}. ¡Pronto implementaremos la conexión con la base de datos para registrar la cita!`);
        });
    });
    var btnReservarHero = document.getElementById('btnReservarHero');
    if (btnReservarHero) {
        btnReservarHero.addEventListener('click', () => {
            document.querySelector('#servicios').scrollIntoView({
                behavior: 'smooth'
            });
        });
    }

    //Simulación de buscador
    var searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            var query = e.target.value.trim();
            if (query) {
                console.log(`Ejecutando búsqueda en frontend para: ${query}`);
            }
        }
    });
});