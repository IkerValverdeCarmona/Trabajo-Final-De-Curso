<?php 
session_start();
include '../includes/header.php'; 
?>

<div class="hero-seccion">
    <h1>Relaja tu cuerpo, equilibra tu mente</h1>
    <p>En LC Quiromasajes cuidamos de ti con técnicas personalizadas.</p>
</div>

<main class="contenedor-principal" style="padding-top: 40px;">
    <form action="procesar_contacto.php" method="POST" class="tarjeta-base" style="width: 100%; max-width: 600px; padding: 40px;"> 
        
        <div class="grupo-entrada">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ej. María García" required class="input-control">
        </div>

        <div class="grupo-entrada">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required class="input-control">
        </div>

        <div class="grupo-entrada">
            <label for="sexo">Sexo</label>
            <select id="sexo" name="sexo" required class="input-control">
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <div class="grupo-entrada">
            <label for="problema">¿Qué te gustaría tratar?</label>
            <textarea id="problema" name="problema" rows="4" placeholder="Cuéntanos brevemente..." required class="input-control"></textarea>
        </div>

        <div class="seccion-verificacion">
            <div class="opcion-verificar">
                <input type="checkbox" id="info" name="marketing" value="1">
                <label for="info">Deseo recibir información comercial</label>
            </div>
            <div class="opcion-verificar">
                <input type="checkbox" id="privacidad" name="privacidad" required>
                <label for="privacidad">Acepto la política de privacidad</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary boton-enviar">Enviar solicitud</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
