<?php
declare(strict_types=1);

function render(string $vistaRelativa, array $data = []): void
{
    $baseVistas = __DIR__ . '/../vistas/';
    $rutaVista  = $baseVistas . ltrim($vistaRelativa, '/');

    $tituloPagina = $data['tituloPagina'] ?? 'Eventos ESCOM';
    $vista        = $rutaVista;

    extract($data);

    require $baseVistas . 'layouts/principal.php';
    exit;
}