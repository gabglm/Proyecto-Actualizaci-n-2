<?php
$errores = $errores ?? [];
?>

<section class="auth auth--blue">
  <div class="auth__card">
    <header class="auth__header">
      <p class="auth__chip">🦈 ESCOM · IPN</p>
      <h1 class="auth__title">Iniciar sesión</h1>
      <p class="auth__subtitle">Accede y guarda tus eventos favoritos.</p>
    </header>

    <?php if (!empty($errores)): ?>
      <div class="auth__alert">
        <strong>🦈 Revisa lo siguiente:</strong>
        <ul>
          <?php foreach ($errores as $e): ?>
            <li><?= htmlspecialchars((string)$e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="index.php?vista=login" class="auth__form">
      <label class="auth__label">Correo</label>
      <input class="auth__input" type="email" name="correo" placeholder="correo@ejemplo.com" autocomplete="email" required>

      <label class="auth__label">Contraseña</label>
      <input class="auth__input" type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>

      <div class="auth__actions">
        <button class="auth__btn" name="login" value="1" type="submit">Entrar</button>
        <a class="auth__btn auth__btn--ghost" href="index.php?vista=home">Cancelar</a>
      </div>
    </form>

    <p class="auth__foot">
      ¿No tienes cuenta?
      <a class="auth__link" href="index.php?vista=register">Regístrate 🦈</a>
    </p>
  </div>

  <aside class="auth__side" aria-hidden="true">
    <div class="auth__sideBg" style="background-image:url('assets/img/banner.jpeg')"></div>
    <div class="auth__sideOverlay"></div>
    <div class="auth__sideContent">
      <h2 class="auth__sideTitle">Plataforma de Eventos</h2>
      <p class="auth__sideText">Encuentra actividades por intereses y únete rápido.</p>
      <div class="auth__badges">
        <span class="auth__badge">🦈 Recomendados</span>
        <span class="auth__badge">📌 Intereses</span>
        <span class="auth__badge">🔔 Alertas</span>
      </div>
    </div>
  </aside>
</section>
