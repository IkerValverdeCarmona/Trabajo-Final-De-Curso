<?php 
session_start();
include 'includes/header.php'; 
?>

<header class="presentacion" style="padding: 60px 20px; text-align: center;">
    <h1 style="font-family: 'Playfair Display', serif; color: #333;">Relaja tu cuerpo, equilibra tu mente</h1>
    <p>En LcQuiromasajes cuidamos de ti con técnicas personalizadas.</p>
</header>

<main class="contenedor-principal" style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <form action="procesar_contacto.php" method="POST" class="card" style="padding: 30px; border-radius: 20px; background: #FFFFFF; box-shadow: 0 15px 40px rgba(0,0,0,0.05);"> 
        
        <div class="grupo-entrada" style="margin-bottom: 20px;">
            <label for="nombre" style="display: block; margin-bottom: 8px; font-weight: 500;">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ej. María García" required 
                   style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
        </div>

        <div class="grupo-entrada" style="margin-bottom: 20px;">
            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500;">Correo electrónico</label>
            <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required 
                   style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
        </div>

        <div class="grupo-entrada" style="margin-bottom: 20px;">
            <label for="sexo" style="display: block; margin-bottom: 8px; font-weight: 500;">Sexo</label>
            <select id="sexo" name="sexo" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ddd;">
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <div class="grupo-entrada" style="margin-bottom: 20px;">
            <label for="problema" style="display: block; margin-bottom: 8px; font-weight: 500;">¿Qué te gustaría tratar?</label>
            <textarea id="problema" name="problema" rows="4" placeholder="Cuéntanos brevemente..." required 
                      style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ddd; line-height: 1.6;"></textarea>
        </div>

        <div class="seccion-verificacion" style="margin-bottom: 25px; font-size: 0.9rem;">
            <div class="opcion-verificar" style="margin-bottom: 10px;">
                <input type="checkbox" id="info" name="marketing" value="1">
                <label for="info">Deseo recibir información comercial</label>
            </div>
            <div class="opcion-verificar">
                <input type="checkbox" id="privacidad" name="privacidad" required>
                <label for="privacidad">Acepto la política de privacidad</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" 
                style="width: 100%; padding: 15px; border-radius: 50px; background-color: #EB6250; color: white; border: none; font-weight: 600; cursor: pointer;">
            Enviar solicitud
        </button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>