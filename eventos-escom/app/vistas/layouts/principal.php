<?php
// Variables esperadas:
// $tituloPagina (string)
// $vista (ruta a la vista)
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($tituloPagina ?? 'Eventos ESCOM'); ?></title>
  <link rel="stylesheet" href="assets/css/estilos.css" />
</head>

<body>

  <!-- =========================
       HEADER / NAVBAR
       ========================= -->
  <header class="topbar">
    <div class="contenedor topbar__fila">
      <a class="marca" href="?vista=home">
        <span class="marca__punto" aria-hidden="true"></span>
        <span>Eventos ESCOM</span>
      </a>

     <nav class="nav">
  <a class="nav__link" href="?vista=home">Home</a>

  <?php if (auth_user()): ?>
    <a class="nav__link" href="?vista=perfil">Perfil</a>
    <a class="nav__link" href="?vista=logout">Salir</a>
  <?php else: ?>
    <a class="nav__link" href="?vista=login">Login</a>
    <a class="nav__link" href="?vista=register">Registro</a>
  <?php endif; ?>

  <button class="boton boton--suave" id="btnTema" type="button">Tema</button>
</nav>

    </div>
  </header>

  <!-- =========================
       BANNER PRINCIPAL
       ========================= -->
  <section class="banner anim-seccion">
    <div
      class="banner__imagen"
      style="background-image:url('assets/img/banner.jpeg');">
    </div>

    <div class="banner__overlay"></div>

    <div class="banner__contenido">
      <p class="chip">ESCOM · IPN</p>
      <h1 class="banner__titulo">Plataforma de Eventos</h1>
      <p class="banner__texto">
        Actividades académicas, culturales y tecnológicas para la comunidad.
      </p>
    </div>
  </section>

  <!-- =========================
       CONTENIDO PRINCIPAL
       ========================= -->
  <main class="contenedor">
    <?php
      if (isset($vista) && file_exists($vista)) {
        require $vista;
      } else {
        echo "
          <div class='estado'>
            <strong>Error:</strong> No se encontró la vista solicitada.
          </div>
        ";
      }
    ?>
  </main>

  <!-- =========================
       FOOTER
       ========================= -->
  <footer class="footer">
    <div class="contenedor footer__fila">
      <small>Proyecto TDAW · <?php echo date('Y'); ?></small>
      <small class="texto-suave">ESCOM · Plataforma de eventos</small>
    </div>
  </footer>

  <!-- =========================
       SCRIPTS
       ========================= -->
  <script src="assets/js/app.js"></script>

</body>
</html>
