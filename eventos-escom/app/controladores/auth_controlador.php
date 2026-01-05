<?php
declare(strict_types=1);

require_once __DIR__ . '/../modelos/usuarios_modelo.php';
require_once __DIR__ . '/sesion_controlador.php';
require_once __DIR__ . '/../../config.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ===== REGISTRO =====
    if (isset($_POST['register'])) {
        $nombre   = trim((string)($_POST['nombre'] ?? ''));
        $correo   = strtolower(trim((string)($_POST['correo'] ?? '')));
        $password = (string)($_POST['password'] ?? '');

        if ($nombre === '' || $correo === '' || $password === '') {
            $errores[] = 'Todos los campos son obligatorios';
        }

        if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Correo inválido';
        }

        // Restricción institucional (PHP 7/8)
        $dominio = '@escom.ipn.mx';
        $esInstitucional = ($correo !== '' && substr($correo, -strlen($dominio)) === $dominio);

        if (defined('RESTRINGIR_CORREO_INSTITUCIONAL') && RESTRINGIR_CORREO_INSTITUCIONAL && !$esInstitucional) {
            $errores[] = 'Correo institucional requerido';
        }

        if ($correo !== '' && users_find_by_email($correo)) {
            $errores[] = 'El correo ya está registrado';
        }

        if (!$errores) {
            // ✅ tu firma real: users_create(string $nombre, string $correo, string $password, string $rol='estudiante')
            $user = users_create($nombre, $correo, $password);

            auth_login($user);
            header('Location: index.php?vista=perfil');
            exit;
        }
    }

    // ===== LOGIN =====
    if (isset($_POST['login'])) {
        $correo   = strtolower(trim((string)($_POST['correo'] ?? '')));
        $password = (string)($_POST['password'] ?? '');

        $user = users_find_by_email($correo);

        if (!$user || !password_verify($password, (string)($user['password_hash'] ?? ''))) {
            $errores[] = 'Credenciales incorrectas';
        } else {
            auth_login($user);
            header('Location: index.php?vista=perfil');
            exit;
        }
    }
}
