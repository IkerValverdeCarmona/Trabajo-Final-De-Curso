<?php
session_start();
require_once '../includes/db.php';

// Seguridad: Solo admin o trabajador pueden acceder
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
?>

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); padding: 60px 20px; text-align: center; border-bottom: 1px solid rgba(235, 98, 80, 0.1);">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.8rem; margin-bottom: 10px;">Panel de Administración</h1>
    <p style="color: #886752; font-family: 'Poppins', sans-serif; font-size: 1.1rem;">Bienvenido al centro de gestión de LC Quiromasajes</p>
</div>

<main style="max-width: 1000px; margin: 50px auto; padding: 0 20px;">
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        
        <a href="admin_citas.php" style="text-decoration: none; transition: transform 0.3s; display: block;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05); text-align: center; border: 1px solid #f0f0f0; height: 100%;">
                <div style="font-size: 4rem; margin-bottom: 20px;">📅</div>
                <h2 style="font-family: 'Playfair Display', serif; color: #333; margin-bottom: 15px;">Gestión de Citas</h2>
                <p style="color: #777; font-family: 'Poppins', sans-serif; line-height: 1.6;">
                    Revisa las próximas reservas, cambia estados de citas (Pendiente, Completada, Cancelada) y organiza la agenda diaria del centro.
                </p>
                <div style="margin-top: 25px; color: #EB6250; font-weight: 600; font-family: 'Poppins', sans-serif;">Acceder ahora →</div>
            </div>
        </a>

        <a href="admin_productos.php" style="text-decoration: none; transition: transform 0.3s; display: block;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05); text-align: center; border: 1px solid #f0f0f0; height: 100%;">
                <div style="font-size: 4rem; margin-bottom: 20px;">🧴</div>
                <h2 style="font-family: 'Playfair Display', serif; color: #333; margin-bottom: 15px;">Gestión de Tienda</h2>
                <p style="color: #777; font-family: 'Poppins', sans-serif; line-height: 1.6;">
                    Controla el inventario de productos, actualiza precios, gestiona el stock disponible y añade nuevos artículos al catálogo.
                </p>
                <div style="margin-top: 25px; color: #EB6250; font-weight: 600; font-family: 'Poppins', sans-serif;">Acceder ahora →</div>
            </div>
        </a>

    </div>

    <div style="margin-top: 50px; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px; border: 1px solid #FFF7EE;">
        <div style="background: #FFF7EE; padding: 15px; border-radius: 50%; color: #EB6250; font-size: 1.2rem;">💡</div>
        <div>
            <h4 style="margin: 0; font-family: 'Playfair Display', serif; color: #333;">Consejo de administración</h4>
            <p style="margin: 5px 0 0 0; color: #888; font-size: 0.9rem; font-family: 'Poppins', sans-serif;">
                Recuerda revisar el stock de los productos más vendidos (como el Palo Santo) regularmente para no quedarte sin existencias en la tienda online.
            </p>
        </div>
    </div>

</main>

<?php include '../includes/footer.php'; ?>