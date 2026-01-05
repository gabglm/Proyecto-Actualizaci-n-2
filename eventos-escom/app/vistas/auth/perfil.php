<?php
// Vista: Perfil
// Variables esperadas: $user (array), opcional $errores (array)

$user = $user ?? null;
$errores = $errores ?? [];

if (!$user) {
  echo "<div class='estado'><strong>Error:</strong> No hay sesión activa.</div>";
  return;
}

$intereses = $user['intereses'] ?? [];
if (!is_array($intereses)) $intereses = [];
$interesesTexto = implode(', ', $intereses);
?>

<section class="seccion" style="max-width:640px;margin:0 auto;">
  <h1 class="h2">Perfil de usuario</h1>
  <p class="texto-suave">Información de tu cuenta.</p>

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

  <div class="panel" style="margin-top:14px;">
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars((string)($user['nombre'] ?? '')); ?></p>
    <p><strong>Correo:</strong> <?php echo htmlspecialchars((string)($user['correo'] ?? '')); ?></p>
    <p><strong>Rol:</strong> <?php echo htmlspecialchars((string)($user['rol'] ?? 'estudiante')); ?></p>
  </div>

  <div class="panel" style="margin-top:14px;">
    <h2 class="h3" style="margin:0 0 10px;">Editar perfil</h2>

    <form method="POST" action="index.php?vista=perfil">
      <label class="texto-suave">Nombre</label><br>
      <input
        class="input"
        type="text"
        name="nombre"
        value="<?php echo htmlspecialchars((string)($user['nombre'] ?? '')); ?>"
        required
        style="width:100%;margin:6px 0 12px;"
      >

      <label class="texto-suave">Intereses (separados por comas)</label><br>
      <input
        class="input"
        type="text"
        name="intereses"
        value="<?php echo htmlspecialchars($interesesTexto); ?>"
        placeholder="IA, electrónica, deportes..."
        style="width:100%;margin:6px 0 12px;"
      >

      <button class="boton" type="submit">Guardar cambios</button>
      <a class="boton boton--suave" href="index.php?vista=home" style="margin-left:8px;">Cancelar</a>
    </form>
  </div>

  <div style="margin-top:14px;">
    <a class="boton boton--suave" href="index.php?vista=home">Volver</a>
    <a class="boton" href="index.php?vista=logout" style="margin-left:8px;">Cerrar sesión</a>
  </div>
</section>
