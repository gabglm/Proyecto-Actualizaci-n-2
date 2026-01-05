<?php
$errores = $errores ?? [];
?>

<section class="auth auth--blue">
  <div class="auth__card">
    <header class="auth__header">
      <p class="auth__chip">🦈 ESCOM · IPN</p>
      <h1 class="auth__title">Crear cuenta</h1>
      <p class="auth__subtitle">Tu perfil, tus intereses, tus eventos.</p>
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

    <form method="POST" action="index.php?vista=register" class="auth__form">
      <label class="auth__label">Nombre</label>
      <input class="auth__input" type="text" name="nombre" placeholder="Tu nombre" autocomplete="name" required>

      <label class="auth__label">Correo</label>
      <input class="auth__input" type="email" name="correo" placeholder="correo@ejemplo.com" autocomplete="email" required>

      <label class="auth__label">Contraseña</label>
      <input class="auth__input" type="password" name="password" placeholder="Mínimo 6 caracteres" autocomplete="new-password" required>

      <div class="auth__actions">
        <button class="auth__btn" name="register" value="1" type="submit">Crear cuenta</button>
        <a class="auth__btn auth__btn--ghost" href="index.php?vista=home">Cancelar</a>
      </div>
    </form>

    <p class="auth__foot">
      ¿Ya tienes cuenta?
      <a class="auth__link" href="index.php?vista=login">Inicia sesión 🦈</a>
    </p>
  </div>

  <aside class="auth__side" aria-hidden="true">
    <div class="auth__sideBg" style="background-image:url('assets/img/banner.jpeg')"></div>
    <div class="auth__sideOverlay"></div>
    <div class="auth__sideContent">
      <h2 class="auth__sideTitle">Modo tiburón</h2>
      <p class="auth__sideText">Agrega intereses (IA, robótica, cultura) y atrapa eventos buenos.</p>
      <div class="auth__badges">
        <span class="auth__badge">🦈 IA</span>
        <span class="auth__badge">🤖 Robótica</span>
        <span class="auth__badge">🎭 Cultura</span>
      </div>
    </div>
  </aside>
</section>
