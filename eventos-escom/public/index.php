<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/controladores/sesion_controlador.php';
require_once __DIR__ . '/../app/helpers/render.php';

require_once __DIR__ . '/../app/controladores/ControladorEventos.php';
require_once __DIR__ . '/../app/controladores/ControladorUsuarios.php';

$eventos  = new ControladorEventos();
$usuarios = new ControladorUsuarios();

$vistaGet = $_GET['vista'] ?? 'home';

switch ($vistaGet) {
    case 'login':
        $usuarios->login('render');
        break;

    case 'register':
        $usuarios->register('render');
        break;

    case 'perfil':
        $usuarios->perfil('render');
        break;

    case 'logout':
        $usuarios->logout();
        break;

    case 'evento':
        $id = (int)($_GET['id'] ?? 0);
        $eventos->mostrarDetalle($id);
        break;

    case 'home':
    default:
        $eventos->mostrarHome();
        break;
}
