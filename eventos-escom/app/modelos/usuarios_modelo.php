<?php
declare(strict_types=1);

function users_path(): string {
    return __DIR__ . '/../../storage/datos/users.json';
}

function users_all(): array {
    $path = users_path();
    if (!file_exists($path)) {
        @mkdir(dirname($path), 0777, true);
        file_put_contents($path, "[]");
    }
    $json = file_get_contents($path);
    $data = json_decode($json ?: "[]", true);
    return is_array($data) ? $data : [];
}

function users_save_all(array $users): void {
    $path = users_path();
    $dir = dirname($path);

    if (!is_dir($dir)) {
        if (!@mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException("No se pudo crear el directorio: $dir");
        }
    }

    // Si no existe, créalo
    if (!file_exists($path)) {
        if (@file_put_contents($path, "[]") === false) {
            throw new RuntimeException("No se pudo crear el archivo: $path");
        }
    }

    // Si existe pero no se puede escribir
    if (!is_writable($path)) {
        throw new RuntimeException("El archivo no es escribible: $path");
    }

    $fp = @fopen($path, 'c+');
    if (!$fp) {
        throw new RuntimeException("No se pudo abrir users.json (ruta): $path");
    }

    if (!flock($fp, LOCK_EX)) {
        fclose($fp);
        throw new RuntimeException("No se pudo bloquear el archivo: $path");
    }

    ftruncate($fp, 0);
    rewind($fp);

    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) $json = "[]";

    fwrite($fp, $json);
    fflush($fp);

    flock($fp, LOCK_UN);
    fclose($fp);
}


function users_find_by_email(string $correo): ?array {
    $correo = strtolower(trim($correo));
    foreach (users_all() as $u) {
        if (strtolower($u['correo'] ?? '') === $correo) return $u;
    }
    return null;
}

function users_find_by_id($id): ?array {
    $id = (int)$id;
    foreach (users_all() as $u) {
        if ((int)($u['id'] ?? 0) === $id) return $u;
    }
    return null;
}

function users_next_id(array $users): int {
    $max = 0;
    foreach ($users as $u) $max = max($max, (int)($u['id'] ?? 0));
    return $max + 1;
}

function users_create(string $nombre, string $correo, string $password, string $rol = 'estudiante'): array {
    $users = users_all();

    $nuevo = [
        'id' => users_next_id($users),
        'nombre' => $nombre,
        'correo' => strtolower(trim($correo)),
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'rol' => $rol,
        'intereses' => [],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $users[] = $nuevo;
    users_save_all($users);
    return $nuevo;
}

/**
 * Actualiza campos permitidos del usuario.
 * Retorna true si actualizó, false si no encontró al usuario.
 */
function users_update($id, array $campos): bool {
    $id = (int)$id;
    $users = users_all();
    $updated = false;

    foreach ($users as &$u) {
        if ((int)($u['id'] ?? 0) !== $id) continue;

        if (array_key_exists('nombre', $campos)) {
            $u['nombre'] = trim((string)$campos['nombre']);
        }

        if (array_key_exists('intereses', $campos)) {
            $ints = $campos['intereses'];
            if (!is_array($ints)) $ints = [];
            $u['intereses'] = array_values(array_filter(array_map('trim', array_map('strval', $ints)), fn($x) => $x !== ''));
        }

        // Si algún día quieres permitir update de correo/rol/password, se agrega aquí.

        $u['updated_at'] = date('Y-m-d H:i:s');
        $updated = true;
        break;
    }
    unset($u);

    if ($updated) users_save_all($users);
    return $updated;
}


