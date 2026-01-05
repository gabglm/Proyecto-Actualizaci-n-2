<?php
declare(strict_types=1);

require_once __DIR__ . '/../modelos/usuarios_modelo.php';
require_once __DIR__ . '/sesion_controlador.php';

final class ControladorUsuarios
{
    public function login(callable $render): void {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = strtolower(trim($_POST['correo'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
            if ($password === '') $errores[] = "Contraseña requerida.";

            if (!$errores) {
                $u = users_find_by_email($correo);
                if (!$u || !password_verify($password, $u['password_hash'] ?? '')) {
                    $errores[] = "Credenciales incorrectas.";
                } else {
                    auth_login($u);
                    header('Location: index.php?vista=perfil');
                    exit;
                }
            }
        }

        $render('auth/login.php', [
            'tituloPagina' => 'Login',
            'errores' => $errores
        ]);
    }

    public function register(callable $render): void {
    $errores = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre   = trim((string)($_POST['nombre'] ?? ''));
        $correo   = strtolower(trim((string)($_POST['correo'] ?? '')));
        $password = (string)($_POST['password'] ?? '');

        if ($nombre === '') $errores[] = "Nombre requerido.";
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
        if (strlen($password) < 6) $errores[] = "La contraseña debe tener al menos 6 caracteres.";

        if (!$errores && users_find_by_email($correo)) {
            $errores[] = "Ese correo ya está registrado.";
        }

        if (!$errores) {
            try {
                // tu firma real: users_create(string $nombre, string $correo, string $password, string $rol = 'estudiante')
                $nuevo = users_create($nombre, $correo, $password, 'estudiante');

                auth_login($nuevo);
                header('Location: index.php?vista=perfil');
                exit;

            } catch (\Throwable $e) {
                // Esto te dirá EXACTAMENTE qué está fallando (casi siempre permisos/ruta)
                $errores[] = "No se pudo registrar: " . $e->getMessage();
            }
        }
    }

    $render('auth/register.php', [
        'tituloPagina' => 'Registro',
        'errores' => $errores
    ]);
}


   public function perfil(callable $render): void {
    requiere_sesion();

    $userSesion = auth_user();
    $user = users_find_by_id($userSesion['id']);

    $errores = [];

    if (!$user) {
        auth_logout();
        header('Location: index.php?vista=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $raw = (string)($_POST['intereses'] ?? '');
        $intereses = array_values(array_filter(array_map('trim', explode(',', $raw)), fn($x) => $x !== ''));

        if ($nombre === '') $errores[] = "Nombre requerido.";

        if (!$errores) {
            users_update($user['id'], [
                'nombre' => $nombre,
                'intereses' => $intereses,
            ]);

            // refresca sesión para que el navbar muestre el nuevo nombre si lo usas
            $_SESSION['user']['nombre'] = $nombre;

            header('Location: index.php?vista=perfil');
            exit;
        }

        $user = users_find_by_id($userSesion['id']);
    }

    $render('auth/perfil.php', [
        'tituloPagina' => 'Perfil',
        'user' => $user,
        'errores' => $errores,
    ]);
}


    public function logout(): void {
        auth_logout();
        header('Location: index.php?vista=login');
        exit;
    }
}