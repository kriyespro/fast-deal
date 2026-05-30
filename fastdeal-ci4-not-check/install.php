<?php
declare(strict_types=1);

$rootPath = __DIR__;
$lockFile = $rootPath . '/storage/install.lock';
$envExamplePath = $rootPath . '/.env.example';
$envPath = $rootPath . '/.env';
$storageDir = $rootPath . '/storage';
$writableDir = $rootPath . '/writable';

if (!is_dir($storageDir)) {
    mkdir($storageDir, 0775, true);
}

if (file_exists($lockFile)) {
    http_response_code(403);
    echo '<h2>Installation is locked.</h2><p>This application is already installed.</p>';
    exit;
}

$errors = [];
$old = [
    'app_name' => '',
    'base_url' => '',
    'db_host' => '127.0.0.1',
    'db_name' => '',
    'db_user' => '',
    'db_password' => '',
    'admin_email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'app_name' => trim((string) ($_POST['app_name'] ?? '')),
        'base_url' => trim((string) ($_POST['base_url'] ?? '')),
        'db_host' => trim((string) ($_POST['db_host'] ?? '')),
        'db_name' => trim((string) ($_POST['db_name'] ?? '')),
        'db_user' => trim((string) ($_POST['db_user'] ?? '')),
        'db_password' => (string) ($_POST['db_password'] ?? ''),
        'admin_email' => trim((string) ($_POST['admin_email'] ?? '')),
    ];
    $adminPassword = (string) ($_POST['admin_password'] ?? '');

    if ($old['app_name'] === '') {
        $errors[] = 'App Name is required.';
    }
    if ($old['base_url'] === '' || filter_var($old['base_url'], FILTER_VALIDATE_URL) === false) {
        $errors[] = 'Valid Base URL is required.';
    }
    if ($old['db_host'] === '' || $old['db_name'] === '' || $old['db_user'] === '') {
        $errors[] = 'Database host, name, and user are required.';
    }
    if ($old['admin_email'] === '' || filter_var($old['admin_email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'Valid Admin Email is required.';
    }
    if (strlen($adminPassword) < 8) {
        $errors[] = 'Admin Password must be at least 8 characters.';
    }
    if (!file_exists($envExamplePath)) {
        $errors[] = '.env.example not found in project root.';
    }

    if ($errors === []) {
        mysqli_report(MYSQLI_REPORT_OFF);
        $mysqli = @new mysqli($old['db_host'], $old['db_user'], $old['db_password'], $old['db_name']);
        if ($mysqli->connect_errno) {
            $errors[] = 'Database connection failed: ' . $mysqli->connect_error;
        } else {
            $mysqli->close();
        }
    }

    if ($errors === []) {
        $template = (string) file_get_contents($envExamplePath);
        $replaced = strtr($template, [
            '{{APP_BASE_URL}}' => rtrim($old['base_url'], '/') . '/',
            '{{DB_HOST}}' => $old['db_host'],
            '{{DB_NAME}}' => $old['db_name'],
            '{{DB_USER}}' => $old['db_user'],
            '{{DB_PASSWORD}}' => $old['db_password'],
        ]);

        if (file_put_contents($envPath, $replaced) === false) {
            $errors[] = 'Failed to write .env file.';
        }
    }

    if ($errors === []) {
        $output = [];
        $exitCode = 1;
        exec('php spark migrate --all 2>&1', $output, $exitCode);
        if ($exitCode !== 0) {
            $errors[] = 'Migration failed: ' . implode("\n", $output);
        }
    }

    if ($errors === []) {
        $mysqli = @new mysqli($old['db_host'], $old['db_user'], $old['db_password'], $old['db_name']);
        if ($mysqli->connect_errno) {
            $errors[] = 'Database reconnect failed: ' . $mysqli->connect_error;
        } else {
            $mysqli->set_charset('utf8mb4');

            $adminName = 'Admin';
            $adminRole = 'admin';
            $passwordHash = password_hash($adminPassword, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare(
                'INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())
                 ON DUPLICATE KEY UPDATE name = VALUES(name), password = VALUES(password), role = VALUES(role), updated_at = NOW()'
            );

            if ($stmt === false) {
                $errors[] = 'Failed to prepare admin insert query.';
            } else {
                $stmt->bind_param('ssss', $adminName, $old['admin_email'], $passwordHash, $adminRole);
                if (!$stmt->execute()) {
                    $errors[] = 'Failed to create admin user: ' . $stmt->error;
                }
                $stmt->close();
            }
            $mysqli->close();
        }
    }

    if ($errors === []) {
        @chmod($writableDir, 0775);
        if (file_put_contents($lockFile, (new DateTimeImmutable())->format(DateTimeInterface::ATOM)) === false) {
            $errors[] = 'Failed to create install lock file.';
        }
    }

    if ($errors === []) {
        header('Location: ' . rtrim($old['base_url'], '/') . '/login');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install FastDeal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-3xl bg-white rounded-2xl shadow p-8">
    <h1 class="text-2xl font-bold mb-2">FastDeal Installer</h1>
    <p class="text-slate-600 mb-6">Configure your app and database to finish setup.</p>

    <?php if ($errors !== []): ?>
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700 text-sm whitespace-pre-wrap">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">App Name</label>
            <input name="app_name" required value="<?= htmlspecialchars($old['app_name'], ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Base URL</label>
            <input name="base_url" required value="<?= htmlspecialchars($old['base_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://example.com/" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">DB Host</label>
            <input name="db_host" required value="<?= htmlspecialchars($old['db_host'], ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">DB Name</label>
            <input name="db_name" required value="<?= htmlspecialchars($old['db_name'], ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">DB User</label>
            <input name="db_user" required value="<?= htmlspecialchars($old['db_user'], ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">DB Password</label>
            <input type="password" name="db_password" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Admin Email</label>
            <input type="email" name="admin_email" required value="<?= htmlspecialchars($old['admin_email'], ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Admin Password</label>
            <input type="password" name="admin_password" required class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div class="md:col-span-2 mt-2">
            <button type="submit" class="w-full rounded-lg bg-slate-900 text-white py-2.5 font-semibold hover:bg-black transition-colors">
                Install Application
            </button>
        </div>
    </form>
</div>
</body>
</html>
