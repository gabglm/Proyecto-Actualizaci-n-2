<?php

require_once __DIR__ . '/../controladores/sesion_controlador.php';

function require_login() {
    if (!auth_user()) {
        header('Location: index.php?vista=perfil');
        exit;
    }
}

function require_role($roles) {
    $user = auth_user();
    if (!$user || !in_array($user['rol'], $roles)) {
        http_response_code(403);
        echo "Acceso denegado";
        exit;
    }
}