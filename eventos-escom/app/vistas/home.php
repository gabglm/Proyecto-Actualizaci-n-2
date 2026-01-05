<?php
$conteo = is_array($eventos) ? count($eventos) : 0;

$categorias = [];
if (!empty($eventos)) {
  foreach ($eventos as $e) {
    $cat = (string)($e['categoria'] ?? '');
    if ($cat !== '') $categorias[$cat] = true;
  }
}
$categorias = array_keys($categorias);
sort($categorias);

// Evento destacado = el primero (ya viene ordenado por fecha desde el modelo)
$destacado = (!empty($eventos) && is_array($eventos)) ? $eventos[0] : null;
?>

<section class="hero">
  <div class="hero__fondo" aria-hidden="true"></div>
  <div class="hero__contenido">
    <p class="chip">Eventos ESCOM</p>
    <h1 class="h1">Explora, filtra y encuentra tu próximo evento.</h1>
    <p class="subtitulo">
      Actividades, talleres, conferencias y concursos dentro de la comunidad.
    </p>

    <div class="hero__acciones">
      <a class="boton" href="#lista">Ver eventos</a>
      <button class="boton boton--suave" id="btnScrollTop" type="button">↑ Arriba</button>
    </div>
  </div>
</section>

<?php if ($destacado): ?>
  <?php
    $idD = (int)($destacado['id'] ?? 0);
    $tituloD = (string)($destacado['titulo'] ?? 'Evento');
    $fechaD = (string)($destacado['fecha'] ?? '');
    $horaD  = (string)($destacado['hora'] ?? '');
    $lugarD = (string)($destacado['lugar'] ?? '');
    $catD   = (string)($destacado['categoria'] ?? '');
    $descD  = (string)($destacado['descripcion'] ?? '');
  ?>
  <section class="seccion">
    <div class="seccion__encabezado">
      <h2 class="h2">Evento destacado</h2>
      <p class="texto-suave"><?php echo htmlspecialchars($conteo); ?> eventos disponibles</p>
    </div>

    <article class="tarjeta" style="grid-column: span 12;">
      <div class="tarjeta__barra" aria-hidden="true"></div>
      <div class="tarjeta__contenido">
        <div class="tarjeta__top">
          <span class="etiqueta"><?php echo htmlspecialchars($catD); ?></span>
          <span class="texto-suave tarjeta__meta"><?php echo htmlspecialchars($fechaD); ?><?php echo $horaD ? (' · ' . htmlspecialchars($horaD)) : ''; ?></span>
        </div>

        <h3 class="h3" style="font-size:20px;"><?php echo htmlspecialchars($tituloD); ?></h3>
        <p class="tarjeta__descripcion" style="-webkit-line-clamp: 2;"><?php echo htmlspecialchars($descD); ?></p>

        <div class="tarjeta__pie">
          <span class="texto-suave">📍 <?php echo htmlspecialchars($lugarD); ?></span>
          <a class="boton boton--chico" href="?vista=evento&id=<?php echo $idD; ?>">Ver detalle</a>
        </div>
      </div>
    </article>
  </section>
<?php endif; ?>

<section class="seccion" id="lista">
  <div class="seccion__encabezado">
    <div>
      <h2 class="h2">Próximos eventos</h2>
      <p class="texto-suave">Busca por título o filtra por categoría.</p>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end;">
      <input
        id="buscadorEventos"
        type="search"
        placeholder="Buscar evento..."
        aria-label="Buscar evento"
        style="padding:10px 14px;border-radius:999px;border:1px solid var(--borde);background:rgba(255,255,255,.04);color:var(--texto);min-width:220px;"
      />

      <select
        id="filtroCategoria"
        aria-label="Filtrar por categoría"
        style="padding:10px 14px;border-radius:999px;border:1px solid var(--borde);background:rgba(255,255,255,.04);color:var(--texto);"
      >
        <option value="">Todas</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <?php if (empty($eventos)): ?>
    <div class="estado">
      <strong>No hay eventos disponibles.</strong>
      <p class="texto-suave">Intenta más tarde.</p>
    </div>
  <?php else: ?>
    <div class="rejilla" id="contenedorTarjetas">
      <?php foreach ($eventos as $e): ?>
        <?php
          $id = (int)($e['id'] ?? 0);
          $titulo = (string)($e['titulo'] ?? 'Sin título');
          $fecha = (string)($e['fecha'] ?? '');
          $hora = (string)($e['hora'] ?? '');
          $lugar = (string)($e['lugar'] ?? '');
          $categoria = (string)($e['categoria'] ?? '');
          $descripcion = (string)($e['descripcion'] ?? '');
        ?>
        <article
          class="tarjeta"
          data-titulo="<?php echo htmlspecialchars(mb_strtolower($titulo)); ?>"
          data-categoria="<?php echo htmlspecialchars(mb_strtolower($categoria)); ?>"
        >
          <div class="tarjeta__barra" aria-hidden="true"></div>
          <div class="tarjeta__contenido">
            <div class="tarjeta__top">
              <span class="etiqueta"><?php echo htmlspecialchars($categoria); ?></span>
              <span class="texto-suave tarjeta__meta"><?php echo htmlspecialchars($fecha); ?><?php echo $hora ? (' · ' . htmlspecialchars($hora)) : ''; ?></span>
            </div>

            <h3 class="h3"><?php echo htmlspecialchars($titulo); ?></h3>
            <p class="tarjeta__descripcion"><?php echo htmlspecialchars($descripcion); ?></p>

            <div class="tarjeta__pie">
              <span class="texto-suave">📍 <?php echo htmlspecialchars($lugar); ?></span>
              <a class="boton boton--chico" href="?vista=evento&id=<?php echo $id; ?>">Ver</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="estado" id="estadoSinResultados" style="display:none; margin-top:14px;">
      <strong>Sin resultados.</strong>
      <p class="texto-suave">Prueba con otro texto o cambia la categoría.</p>
    </div>
  <?php endif; ?>
</section>
