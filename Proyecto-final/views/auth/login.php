<?php
/* Este fragmento de código PHP está creando un formulario de inicio de sesión para un sitio web.
A continuación, se muestra un desglose de lo que hace el código: */
require_once __DIR__ . '/../layouts/header.php';
use Config\Csrf;
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Iniciar sesión
            </div>
            <div class="card-body">
                <form action="/TIENDA/auth/login" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= Csrf::generarToken(); ?>">
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>