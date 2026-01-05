<?php
require_once __DIR__ . '/../modelos/ModeloEventos.php';

class ControladorEventos
{
  private ModeloEventos $modelo;

  public function __construct()
  {
    $rutaJson = __DIR__ . '/../../storage/eventos.json';
    $this->modelo = new ModeloEventos($rutaJson);
  }

  public function mostrarHome(): void
  {
    $eventos = $this->modelo->obtenerTodos();
    $tituloPagina = 'Home · Eventos ESCOM';
    $vista = __DIR__ . '/../vistas/home.php';

    require __DIR__ . '/../vistas/layouts/principal.php';
  }

  public function mostrarDetalle(int $id): void
  {
    $evento = $this->modelo->obtenerPorId($id);
    $tituloPagina = $evento ? ('Evento · ' . ($evento['titulo'] ?? 'Detalle')) : 'Evento no encontrado';
    $vista = __DIR__ . '/../vistas/evento_detalles.php';

    require __DIR__ . '/../vistas/layouts/principal.php';
  }
}