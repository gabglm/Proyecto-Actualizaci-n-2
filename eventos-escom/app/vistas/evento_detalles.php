<?php
// Variable esperada: $evento (array|null)
?>
<section class="seccion">
  <a class="link" href="?vista=home">← Volver</a>

  <?php if (!$evento): ?>
    <div class="estado">
      <strong>Evento no encontrado.</strong>
      <p class="texto-suave">Verifica que el evento exista.</p>
    </div>
  <?php else: ?>
    <?php
      $id = (int)($evento['id'] ?? 0);
      $titulo = (string)($evento['titulo'] ?? 'Sin título');
      $descripcion = (string)($evento['descripcion'] ?? '');
      $fecha = (string)($evento['fecha'] ?? '');
      $hora = (string)($evento['hora'] ?? '');
      $lugar = (string)($evento['lugar'] ?? '');
      $categoria = (string)($evento['categoria'] ?? '');
      $organiza = (string)($evento['organiza'] ?? '');
      $cupo = $evento['cupo'] ?? null;
      $costo = (string)($evento['costo'] ?? '');
    ?>

    <div class="detalle">
      <div>
        <p class="chip">Evento</p>
        <h1 class="h1"><?php echo htmlspecialchars($titulo); ?></h1>

        <div class="panel">
          <div class="panel__fila">
            <span class="etiqueta"><?php echo htmlspecialchars($categoria); ?></span>
            <span class="texto-suave"><?php echo htmlspecialchars($fecha); ?><?php echo $hora ? (' · ' . htmlspecialchars($hora)) : ''; ?></span>
          </div>

          <div class="panel__lista">
            <div>
              <span class="texto-suave">Lugar</span><br>
              <strong><?php echo htmlspecialchars($lugar); ?></strong>
            </div>
            <div>
              <span class="texto-suave">Organiza</span><br>
              <strong><?php echo $organiza ? htmlspecialchars($organiza) : '—'; ?></strong>
            </div>
            <div>
              <span class="texto-suave">Cupo</span><br>
              <strong><?php echo ($cupo !== null) ? (int)$cupo : '—'; ?></strong>
            </div>
            <div>
              <span class="texto-suave">Costo</span><br>
              <strong><?php echo $costo ? htmlspecialchars($costo) : '—'; ?></strong>
            </div>
          </div>

          <p class="panel__texto"><?php echo nl2br(htmlspecialchars($descripcion)); ?></p>
        </div>

        <div class="acciones" style="margin-top:12px;">
  <a class="boton" href="?vista=home">Volver a eventos</a>

  <button
    class="boton boton--suave"
    id="btnCalendario"
    type="button"
    data-titulo="<?php echo htmlspecialchars($titulo, ENT_QUOTES); ?>"
    data-descripcion="<?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?>"
    data-lugar="<?php echo htmlspecialchars($lugar, ENT_QUOTES); ?>"
    data-fecha="<?php echo htmlspecialchars($fecha, ENT_QUOTES); ?>"
    data-hora="<?php echo htmlspecialchars($hora, ENT_QUOTES); ?>"
  >
    Agregar al calendario
  </button>

  <button class="boton boton--suave" id="btnCompartir" type="button">Compartir</button>
</div>


      <aside class="estado">
        <strong>Información</strong>
        <p class="texto-suave" style="margin:10px 0 0; line-height:1.6;">
          ID: <?php echo $id; ?><br>
          Categoría: <?php echo htmlspecialchars($categoria); ?><br>
          Fecha: <?php echo htmlspecialchars($fecha); ?><?php echo $hora ? (' · ' . htmlspecialchars($hora)) : ''; ?>
        </p>
      </aside>
    </div>
  <?php endif; ?>
</section>
