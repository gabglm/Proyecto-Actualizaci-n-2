<?php if (!empty($errores)): ?>
  <div class="alerta">
    <?php foreach ($errores as $e): ?>
      <p><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<h1>Registro</h1>

<form method="post">
  <input type="text" name="nombre" placeholder="Nombre" required>
  <input type="email" name="correo" placeholder="Correo" required>
  <input type="password" name="password" placeholder="Contraseña" required>
  <button type="submit">Registrarse</button>
</form>