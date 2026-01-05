<?php
// Vista: Login
// Variables esperadas: $errores (array)
$errores = $errores ?? [];
?>

<section class="seccion" style="max-width:520px;margin:0 auto;">
  <h1 class="h2">Iniciar sesión</h1>
  <p class="texto-suave">Accede con tu correo y contraseña.</p>

  <?php if (!empty($errores)): ?>
    <div class="estado" style="margin-top:12px;">
      <strong>Revisa lo siguiente:</strong>
      <ul style="margin:8px 0 0 18px;">
        <?php foreach ($errores as $e): ?>
          <li><?php echo htmlspecialchars((string)$e); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="index.php?vista=login" class="panel" style="margin-top:12px;">
    <label class="texto-suave">Correo</label><br>
    <input
      class="input"
      type="email"
      name="correo"
      placeholder="correo@ejemplo.com"
      autocomplete="email"
      required
      style="width:100%;margin:6px 0 12px;"
    >

    <label class="texto-suave">Contraseña</label><br>
    <input
      class="input"
      type="password"
      name="password"
      placeholder="••••••••"
      autocomplete="current-password"
      required
      style="width:100%;margin:6px 0 12px;"
    >

    <button class="boton" name="login" value="1" type="submit">Entrar</button>
    <a class="boton boton--suave" href="index.php?vista=home" style="margin-left:8px;">Cancelar</a>
  </form>

  <p class="texto-suave" style="margin-top:12px;">
    ¿No tienes cuenta?
    <a class="link" href="index.php?vista=register">Regístrate</a>
  </p>
</section>