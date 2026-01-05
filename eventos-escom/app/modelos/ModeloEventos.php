<?php
class ModeloEventos
{
  private string $rutaJson;

  public function __construct(string $rutaJson)
  {
    $this->rutaJson = $rutaJson;
  }

  public function obtenerTodos(): array
  {
    $eventos = $this->leerJson();

    usort($eventos, function ($a, $b) {
      return strcmp((string)($a['fecha'] ?? ''), (string)($b['fecha'] ?? ''));
    });

    return $eventos;
  }

  public function obtenerPorId(int $id): ?array
  {
    foreach ($this->leerJson() as $evento) {
      if ((int)($evento['id'] ?? 0) === $id) return $evento;
    }
    return null;
  }

  private function leerJson(): array
  {
    if (!file_exists($this->rutaJson)) return [];
    $contenido = file_get_contents($this->rutaJson);
    if ($contenido === false || trim($contenido) === '') return [];
    $datos = json_decode($contenido, true);
    return is_array($datos) ? $datos : [];
  }
}
