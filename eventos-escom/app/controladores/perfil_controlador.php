<?php
declare(strict_types=1);

require_once __DIR__ . '/../modelos/usuarios_modelo.php';
require_once __DIR__ . '/../controladores/sesion_controlador.php';

session_start();
require_login();

$userSesion = auth_user();
if (!$userSesion || empty($userSesion['id'])) {
    session_destroy();
    header('Location: index.php?vista=login');
    exit;
}

$user = users_find_by_id($userSesion['id']);
if (!$user) {
    session_destroy();
    header('Location: index.php?vista=login');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim((string)($_POST['nombre'] ?? ''));
    $interesesRaw = (string)($_POST['intereses'] ?? '');
    $intereses = array_values(array_filter(array_map('trim', explode(',', $interesesRaw)), fn($x) => $x !== ''));

    if ($nombre === '') $errores[] = 'El nombre no puede ir vacío';

    if (!$errores) {
        $ok = users_update($user['id'], [
            'nombre' => $nombre,
            'intereses' => $intereses,
        ]);

        if (!$ok) {
            $errores[] = 'No se pudo actualizar el usuario (revisa permisos del users.json).';
        } else {
            // refrescar sesión si guardas nombre ahí
            $_SESSION['user']['nombre'] = $nombre;

            header('Location: index.php?vista=perfil');
            exit;
        }
    }

    // recargar usuario actualizado
    $user = users_find_by_id($userSesion['id']);
}
