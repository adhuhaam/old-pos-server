<?php

class AppError extends RuntimeException
{
    public int $status;

    public function __construct(string $message, int $status = 400)
    {
        parent::__construct($message);
        $this->status = $status;
    }
}

function config(): array
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
        date_default_timezone_set($config['timezone'] ?? 'UTC');
    }
    return $config;
}

function db(): PDO
{
    static $pdo = null;
    static $ready = false;

    if ($pdo === null) {
        $c = config();
        $pdo = new PDO(
            'mysql:host=' . $c['db_host'] . ';dbname=' . $c['db_name'] . ';charset=utf8mb4',
            $c['db_user'],
            $c['db_pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        $offset = (new DateTime('now'))->format('P');
        $pdo->exec('SET time_zone = ' . $pdo->quote($offset));
        ensure_schema($pdo);
    }

    if (!$ready) {
        $ready = true;
        $row = $pdo->query('SELECT owner_password_hash, staff_pin_hash FROM settings WHERE id = 1')->fetch();
        if ($row && $row['owner_password_hash'] === '' && $row['staff_pin_hash'] === '') {
            $password = password_hash('admin', PASSWORD_DEFAULT);
            $pin = password_hash('1234', PASSWORD_DEFAULT);
            if ($password === false || $pin === false) {
                throw new RuntimeException('Could not create the default password.');
            }
            $stmt = $pdo->prepare(
                'UPDATE settings SET owner_password_hash = ?, staff_pin_hash = ? WHERE id = 1 AND owner_password_hash = ""'
            );
            $stmt->execute([$password, $pin]);
        }
        ensure_users($pdo);
    }

    return $pdo;
}

function settings(): array
{
    $row = db()->query('SELECT owner_password_hash, staff_pin_hash, currency_symbol, cafe_name, bank_image, order_host, default_credit_limit FROM settings WHERE id = 1')->fetch();
    if (!$row) {
        throw new AppError('Settings are missing. Import database/schema.sql.', 500);
    }
    return $row;
}

function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function money_label(string $amount): string
{
    $symbol = trim(settings()['currency_symbol']);
    $gap = preg_match('/[A-Za-z]$/', $symbol) ? ' ' : '';
    $number = str_replace([',', ' '], '', trim($amount));
    return $symbol . $gap . number_format((float) $number, 2, '.', ',');
}

function english_month(int $month): string
{
    $names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return $names[$month - 1] ?? '';
}

function format_date(?string $value): string
{
    if ($value === null || trim($value) === '') {
        return '';
    }
    $parsed = date_create($value);
    if (!$parsed) {
        return $value;
    }
    $month = english_month((int) $parsed->format('n'));
    return $month === '' ? $value : $parsed->format('d') . ' ' . $month . ' ' . $parsed->format('Y');
}

function format_month(?string $value): string
{
    if ($value === null || trim($value) === '') {
        return '';
    }
    $parsed = date_create($value);
    if (!$parsed) {
        return $value;
    }
    $month = english_month((int) $parsed->format('n'));
    return $month === '' ? $value : $month . ' ' . $parsed->format('Y');
}

function format_when(?string $value): string
{
    if ($value === null || trim($value) === '') {
        return '';
    }
    $parsed = date_create($value);
    if (!$parsed) {
        return $value;
    }
    return format_date($parsed->format('Y-m-d')) . ' ' . $parsed->format('g:i A');
}

function parse_date(string $value): string
{
    $value = trim(preg_replace('/\s+/', ' ', $value) ?? '');
    if (preg_match('/^(\d{1,2}) ([A-Za-z]{3}) (\d{4})$/', $value, $match)) {
        $names = [
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
            'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12,
        ];
        $month = $names[strtolower($match[2])] ?? 0;
        $day = (int) $match[1];
        $year = (int) $match[3];
        if ($month > 0 && checkdate($month, $day, $year)) {
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        }
    }
    foreach (['!d m Y', '!d/m/Y', '!d-m-Y', '!Y-m-d'] as $format) {
        $parsed = DateTime::createFromFormat($format, $value);
        $plain = substr($format, 1);
        if ($parsed && $parsed->format($plain) === $value) {
            return $parsed->format('Y-m-d');
        }
    }
    throw new AppError('Use a date like 21 Oct 2026.');
}

function payment_label(?string $method): string
{
    if ($method === 'cash') {
        return 'Cash';
    }
    if ($method === 'online') {
        return 'Bank';
    }
    if ($method === 'credit' || $method === 'card') {
        return 'Credit/Hold';
    }
    return '';
}

function atom_time(?string $value): ?string
{
    if ($value === null || $value === '') {
        return null;
    }
    return (new DateTime($value))->format(DateTime::ATOM);
}

function duration_label(?string $start, ?string $end = null): ?string
{
    if ($start === null || $start === '') {
        return null;
    }
    $from = new DateTime($start);
    $to = ($end === null || $end === '') ? new DateTime('now') : new DateTime($end);
    $seconds = $to->getTimestamp() - $from->getTimestamp();
    if ($seconds < 0) {
        $seconds = 0;
    }
    $hours = intdiv($seconds, 3600);
    $minutes = intdiv($seconds % 3600, 60);
    $secs = $seconds % 60;
    $minutesText = str_pad((string) $minutes, 2, '0', STR_PAD_LEFT);
    $secsText = str_pad((string) $secs, 2, '0', STR_PAD_LEFT);
    if ($hours > 0) {
        return $hours . ':' . $minutesText . ':' . $secsText;
    }
    return $minutes . ':' . $secsText;
}

function to_cents(string $amount): int
{
    $amount = str_replace([',', ' '], '', trim($amount));
    if (!preg_match('/^(\d+)(?:\.(\d{1,2}))?$/', $amount, $matches)) {
        throw new AppError('Invalid amount.');
    }
    $fraction = str_pad($matches[2] ?? '0', 2, '0');
    return ((int) $matches[1]) * 100 + (int) $fraction;
}

function from_cents(int $cents): string
{
    $sign = $cents < 0 ? '-' : '';
    $cents = abs($cents);
    return $sign . intdiv($cents, 100) . '.' . str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
}

function normalize_price(string $price): string
{
    $cents = to_cents($price);
    if ($cents <= 0 || $cents > 9999999) {
        throw new AppError('Price must be between 0.01 and 99999.99.');
    }
    return from_cents($cents);
}

function json_out(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(int $code, string $message): void
{
    json_out(['error' => $message], $code);
}

function read_json(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        throw new AppError('Invalid JSON.');
    }
    return $data;
}

function request_path(): string
{
    if (isset($_GET['route']) && is_string($_GET['route']) && str_starts_with($_GET['route'], '/')) {
        $route = '/' . trim($_GET['route'], '/');
        return $route === '/' ? '/' : $route;
    }
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (!is_string($uri) || $uri === '') {
        $uri = '/';
    }
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = rtrim($scriptDir, '/');
    if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
        $uri = substr($uri, strlen($scriptDir));
    }
    $uri = '/' . trim($uri, '/');
    if ($uri === '/index.php') {
        return '/';
    }
    if (str_starts_with($uri, '/index.php/')) {
        $uri = substr($uri, strlen('/index.php'));
        $uri = '/' . trim($uri, '/');
    }
    return $uri === '' ? '/' : $uri;
}

function normalize_ip(string $ip): string
{
    if (str_starts_with($ip, '::ffff:')) {
        return substr($ip, 7);
    }
    return $ip;
}

function is_tailscale_ip(string $ip): bool
{
    $value = ip2long($ip);
    if ($value === false) {
        return false;
    }
    $value = (float) sprintf('%u', $value);
    $from = (float) sprintf('%u', ip2long('100.64.0.0'));
    $to = (float) sprintf('%u', ip2long('100.127.255.255'));
    return $value >= $from && $value <= $to;
}

function is_local_request(): bool
{
    $ip = normalize_ip($_SERVER['REMOTE_ADDR'] ?? '');
    if ($ip === '127.0.0.1' || $ip === '::1' || is_tailscale_ip($ip)) {
        return true;
    }
    $server = normalize_ip($_SERVER['SERVER_ADDR'] ?? '');
    return $ip !== '' && $ip === $server;
}

function is_private_ipv4(string $ip): bool
{
    $value = ip2long($ip);
    if ($value === false) {
        return false;
    }
    $value = (float) sprintf('%u', $value);
    $ranges = [
        ['10.0.0.0', '10.255.255.255'],
        ['172.16.0.0', '172.31.255.255'],
        ['192.168.0.0', '192.168.255.255'],
    ];
    foreach ($ranges as [$start, $end]) {
        $from = (float) sprintf('%u', ip2long($start));
        $to = (float) sprintf('%u', ip2long($end));
        if ($value >= $from && $value <= $to) {
            return true;
        }
    }
    return false;
}

function is_cafe_network(?string $ip = null): bool
{
    $ip = normalize_ip($ip ?? ($_SERVER['REMOTE_ADDR'] ?? ''));
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return true;
    }
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return is_private_ipv4($ip) || is_tailscale_ip($ip);
    }
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        return false;
    }
    $packed = inet_pton($ip);
    if ($packed === false) {
        return false;
    }
    $first = ord($packed[0]);
    return ($first & 0xfe) === 0xfc;
}

function is_shop_floor(?string $ip = null): bool
{
    $ip = normalize_ip($ip ?? ($_SERVER['REMOTE_ADDR'] ?? ''));
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return true;
    }
    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && is_private_ipv4($ip);
}

function require_shop_floor(): void
{
    if (!is_shop_floor()) {
        throw new AppError('Open this on the cafe Wi-Fi.', 403);
    }
}

function role_uses_tailscale(string $role): bool
{
    return $role === 'superadmin' || $role === 'manager' || $role === 'supply' || role_opens_admin($role);
}

function assert_staff_network(array $staff): void
{
    if (role_uses_tailscale((string) ($staff['role'] ?? ''))) {
        require_cafe_network();
        return;
    }
    require_shop_floor();
}

function require_cafe_network(): void
{
    if (!is_cafe_network()) {
        throw new AppError('Open this POS on the cafe Wi-Fi or over Tailscale.', 403);
    }
}

function require_local(): void
{
    if (!is_cafe_network()) {
        throw new AppError('Open this POS on the cafe Wi-Fi or over Tailscale.', 403);
    }
}

function app_cookie_path(): string
{
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/'));
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
    $folder = strtolower(basename($dir));
    if (in_array($folder, ['admin', 'api', 'waiter', 'customer', 'supply', 'supplier', 'manager'], true)) {
        $dir = rtrim(str_replace('\\', '/', dirname($dir)), '/');
    }
    if ($dir === '' || $dir === '.' || $dir === '\\') {
        return '/';
    }
    return $dir;
}

function expire_stray_session_cookie(string $path): void
{
    if ($path === '/' || headers_sent()) {
        return;
    }
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function start_app_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $path = app_cookie_path();
    session_name('CAFEPOS');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $path,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
    expire_stray_session_cookie($path);
}

function bearer_token(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if ($header === '' && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (is_array($headers)) {
            $header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }
    }
    if (preg_match('/Bearer\s+([a-f0-9]{64})/i', (string) $header, $matches)) {
        return strtolower($matches[1]);
    }
    $custom = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
    if (preg_match('/^[a-f0-9]{64}$/i', (string) $custom)) {
        return strtolower($custom);
    }
    return null;
}

function staff_user(): ?array
{
    $token = bearer_token();
    if ($token === null) {
        return null;
    }
    $stmt = db()->prepare(
        'SELECT u.id, u.name, u.role FROM tokens t
         JOIN users u ON u.id = t.user_id
         WHERE t.token = ? AND t.expires_at > ? AND u.active = 1 AND u.role IN ("waiter", "manager", "superadmin")'
    );
    $stmt->execute([$token, date('Y-m-d H:i:s')]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    return [
        'id' => (int) $row['id'],
        'name' => $row['name'],
        'role' => $row['role'],
    ];
}

function require_staff(): void
{
    $staff = staff_user();
    if ($staff === null) {
        throw new AppError('Sign in required.', 401);
    }
    assert_staff_network($staff);
}

function require_manager(): array
{
    return require_staff_area('expenses');
}

function access_catalog(): array
{
    return [
        'Phone' => [
            'orders' => 'Take orders and payments',
            'today' => 'See sales and profit',
            'expenses' => 'Add expenses and supplies',
            'supply.quote' => 'Quote the weekly supply',
            'credit.limit' => 'Set customer credit limits',
        ],
        'Admin' => [
            'admin.today' => 'Today',
            'admin.sales' => 'Sales',
            'admin.customers' => 'Customers',
            'admin.menu' => 'Menu',
            'admin.floor' => 'Floor',
            'admin.books' => 'Books and salaries',
            'admin.expenses' => 'Expenses',
            'admin.reports' => 'Reports',
            'admin.hours' => 'Hours',
            'admin.settings' => 'Settings',
            'admin.users' => 'Users and roles',
        ],
    ];
}

function access_keys(): array
{
    $keys = [];
    foreach (access_catalog() as $group) {
        foreach ($group as $key => $label) {
            $keys[] = $key;
        }
    }
    return $keys;
}

function default_role_areas(string $slug): array
{
    if ($slug === 'superadmin') {
        return access_keys();
    }
    if ($slug === 'manager') {
        return ['orders', 'today', 'expenses'];
    }
    if ($slug === 'waiter') {
        return ['orders', 'today'];
    }
    if ($slug === 'supply') {
        return ['supply.quote'];
    }
    return [];
}

function ensure_access(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS roles (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            slug VARCHAR(40) NOT NULL,
            name VARCHAR(80) NOT NULL,
            built_in TINYINT(1) NOT NULL DEFAULT 0,
            sort_order INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY roles_slug (slug)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS role_access (
            role_id INT UNSIGNED NOT NULL,
            area VARCHAR(40) NOT NULL,
            PRIMARY KEY (role_id, area)
        ) ENGINE=InnoDB'
    );
    $seed = $pdo->prepare('INSERT IGNORE INTO roles (slug, name, built_in, sort_order) VALUES (?, ?, 1, ?)');
    $seed->execute(['superadmin', 'Superadmin', 1]);
    $seed->execute(['manager', 'Manager', 2]);
    $seed->execute(['waiter', 'Waiter', 3]);
    $seed->execute(['supply', 'Supply', 4]);
    $find = $pdo->prepare('SELECT id FROM roles WHERE slug = ?');
    $count = $pdo->prepare('SELECT COUNT(*) FROM role_access WHERE role_id = ?');
    $grant = $pdo->prepare('INSERT IGNORE INTO role_access (role_id, area) VALUES (?, ?)');
    foreach (['superadmin', 'manager', 'waiter'] as $slug) {
        $find->execute([$slug]);
        $roleId = (int) $find->fetchColumn();
        $count->execute([$roleId]);
        if ((int) $count->fetchColumn() > 0) {
            continue;
        }
        foreach (default_role_areas($slug) as $area) {
            $grant->execute([$roleId, $area]);
        }
    }
}

function areas_for_role(string $slug): array
{
    if ($slug === 'superadmin') {
        return access_keys();
    }
    if ($slug === '') {
        return [];
    }
    $stmt = db()->prepare(
        'SELECT a.area FROM role_access a JOIN roles r ON r.id = a.role_id WHERE r.slug = ? ORDER BY a.area'
    );
    $stmt->execute([$slug]);
    $areas = [];
    foreach ($stmt->fetchAll() as $row) {
        $areas[] = (string) $row['area'];
    }
    if ($areas === []) {
        return default_role_areas($slug);
    }
    return $areas;
}

function role_opens_admin(string $slug): bool
{
    if (!empty($_SESSION['owner_ok']) && $slug === '') {
        return true;
    }
    foreach (areas_for_role($slug) as $area) {
        if (str_starts_with($area, 'admin.')) {
            return true;
        }
    }
    return $slug === 'superadmin';
}

function session_areas(): array
{
    $role = (string) ($_SESSION['role'] ?? '');
    if ($role === '' && !empty($_SESSION['owner_ok'])) {
        return access_keys();
    }
    return areas_for_role($role);
}

function session_can(string $area): bool
{
    return in_array($area, session_areas(), true);
}

function role_label(string $slug): string
{
    $stmt = db()->prepare('SELECT name FROM roles WHERE slug = ?');
    $stmt->execute([$slug]);
    $name = $stmt->fetchColumn();
    return is_string($name) && $name !== '' ? $name : $slug;
}

function list_roles(): array
{
    $rows = db()->query('SELECT id, slug, name, built_in, sort_order FROM roles ORDER BY sort_order, name')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $slug = (string) $row['slug'];
        $out[] = [
            'id' => (int) $row['id'],
            'slug' => $slug,
            'name' => (string) $row['name'],
            'built_in' => (int) $row['built_in'] === 1,
            'areas' => areas_for_role($slug),
        ];
    }
    return $out;
}

function role_by_slug(string $slug): ?array
{
    foreach (list_roles() as $role) {
        if ($role['slug'] === $slug) {
            return $role;
        }
    }
    return null;
}

function save_role(?string $slug, string $name, array $areas): string
{
    if ((string) ($_SESSION['role'] ?? '') !== 'superadmin') {
        throw new AppError('Only a superadmin can change roles.', 403);
    }
    $name = trim($name);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Role name must be 1 to 80 characters.');
    }
    $clean = [];
    foreach ($areas as $area) {
        $area = (string) $area;
        if (in_array($area, access_keys(), true) && !in_array($area, $clean, true)) {
            $clean[] = $area;
        }
    }
    if ($clean === []) {
        throw new AppError('Choose at least one access level.');
    }
    $pdo = db();
    if ($slug === null || $slug === '') {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        $slug = trim($slug, '-');
        if ($slug === '' || strlen($slug) > 40) {
            throw new AppError('Use a role name with letters or numbers.');
        }
        if (role_by_slug($slug) !== null) {
            throw new AppError('That role already exists.');
        }
        $sort = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM roles')->fetchColumn();
        $pdo->prepare('INSERT INTO roles (slug, name, built_in, sort_order) VALUES (?, ?, 0, ?)')->execute([$slug, $name, $sort]);
    } else {
        $role = role_by_slug($slug);
        if ($role === null) {
            throw new AppError('Role not found.', 404);
        }
        if ($slug === 'superadmin') {
            throw new AppError('Superadmin keeps full access.');
        }
        $pdo->prepare('UPDATE roles SET name = ? WHERE slug = ?')->execute([$name, $slug]);
    }
    if ($slug === 'superadmin') {
        return $slug;
    }
    $idStmt = $pdo->prepare('SELECT id FROM roles WHERE slug = ?');
    $idStmt->execute([$slug]);
    $roleId = (int) $idStmt->fetchColumn();
    $pdo->prepare('DELETE FROM role_access WHERE role_id = ?')->execute([$roleId]);
    $grant = $pdo->prepare('INSERT INTO role_access (role_id, area) VALUES (?, ?)');
    foreach ($clean as $area) {
        $grant->execute([$roleId, $area]);
    }
    return $slug;
}

function delete_role(string $slug): void
{
    if ((string) ($_SESSION['role'] ?? '') !== 'superadmin') {
        throw new AppError('Only a superadmin can change roles.', 403);
    }
    $role = role_by_slug($slug);
    if ($role === null) {
        throw new AppError('Role not found.', 404);
    }
    if ($role['built_in']) {
        throw new AppError('This role stays in the system.');
    }
    $used = db()->prepare('SELECT COUNT(*) FROM users WHERE role = ?');
    $used->execute([$slug]);
    if ((int) $used->fetchColumn() > 0) {
        throw new AppError('Move the people on this role before deleting it.');
    }
    $pdo = db();
    $pdo->prepare('DELETE FROM role_access WHERE role_id = ?')->execute([$role['id']]);
    $pdo->prepare('DELETE FROM roles WHERE id = ?')->execute([$role['id']]);
}

function require_staff_area(string $area): array
{
    $staff = staff_user();
    if ($staff === null) {
        throw new AppError('Sign in required.', 401);
    }
    assert_staff_network($staff);
    if (!in_array($area, areas_for_role((string) $staff['role']), true)) {
        throw new AppError('Your role cannot open that.', 403);
    }
    return $staff;
}

function require_owner_api(): void
{
    start_app_session();
    if (empty($_SESSION['owner_ok'])) {
        throw new AppError('Owner sign-in required.', 401);
    }
}

function normalize_username(string $username): string
{
    $username = strtolower(trim($username));
    if (!preg_match('/^[a-z0-9._-]{2,40}$/', $username)) {
        throw new AppError('Username must be 2 to 40 letters, numbers, dots, or dashes.');
    }
    return $username;
}

function authenticate_user(string $username, string $password): array
{
    try {
        $username = normalize_username($username);
    } catch (AppError $error) {
        throw new AppError('Wrong username or password.', 401);
    }
    $stmt = db()->prepare('SELECT id, name, username, password_hash, role, active FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!$user || (int) $user['active'] !== 1 || !password_verify($password, (string) $user['password_hash'])) {
        throw new AppError('Wrong username or password.', 401);
    }
    return [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'username' => $user['username'],
        'role' => $user['role'],
    ];
}

function issue_token(int $userId): string
{
    $pdo = db();
    $pdo->prepare('DELETE FROM tokens WHERE expires_at <= ?')->execute([date('Y-m-d H:i:s')]);
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 12 * 3600);
    $pdo->prepare('INSERT INTO tokens (token, expires_at, user_id) VALUES (?, ?, ?)')->execute([$token, $expires, $userId]);
    return $token;
}

function login_session(array $user): void
{
    start_app_session();
    session_regenerate_id(true);
    $_SESSION['owner_ok'] = $user['role'] === 'superadmin';
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['user_name'] = $user['name'];
}

function pos_root(): string
{
    $root = app_cookie_path();
    return $root === '/' ? '' : $root;
}

function role_desk(string $role): string
{
    if ($role === 'manager' || $role === 'waiter') {
        return 'pos';
    }
    if ($role === 'supply') {
        return 'supplier';
    }
    if ($role === 'waiter') {
        return 'pos';
    }
    if ($role === 'superadmin' || role_opens_admin($role)) {
        return 'admin';
    }
    $areas = $role === '' ? [] : areas_for_role($role);
    $floor = array_intersect($areas, ['orders', 'today', 'expenses', 'credit.limit']) !== [];
    if (in_array('supply.quote', $areas, true) && !$floor) {
        return 'supplier';
    }
    return 'pos';
}

function admin_home_path(): string
{
    $pages = [
        'admin.today' => 'admin/index.php',
        'admin.sales' => 'admin/sales.php',
        'admin.customers' => 'admin/customers.php',
        'admin.menu' => 'admin/items.php',
        'admin.floor' => 'admin/tables.php',
        'admin.books' => 'admin/books.php',
        'admin.expenses' => 'admin/expenses.php',
        'admin.reports' => 'admin/reports.php',
        'admin.hours' => 'admin/hr.php',
        'admin.settings' => 'admin/settings.php',
        'admin.users' => 'admin/users.php',
    ];
    foreach ($pages as $area => $path) {
        if (session_can($area)) {
            return $path;
        }
    }
    return 'admin/account.php';
}

function desk_home(string $desk): string
{
    $root = pos_root();
    if ($desk === 'admin') {
        return $root . '/' . admin_home_path();
    }
    if ($desk === 'supplier') {
        return $root . '/supplier/';
    }
    if ($desk === 'manager') {
        return $root . '/manager/';
    }
    return $root . '/waiter/';
}

function manager_opens_floor(): bool
{
    foreach (['orders', 'today', 'expenses'] as $area) {
        if (in_array($area, session_areas(), true)) {
            return true;
        }
    }
    return false;
}

function manager_links(): array
{
    $root = pos_root();
    $pages = [
        'admin.today' => ['Dashboard', 'admin/index.php'],
        'admin.sales' => ['Sales', 'admin/sales.php'],
        'admin.customers' => ['Customers', 'admin/customers.php'],
        'admin.menu' => ['Menu', 'admin/items.php'],
        'admin.floor' => ['Floor plan', 'admin/tables.php'],
        'admin.books' => ['Books', 'admin/books.php'],
        'admin.expenses' => ['Expenses', 'admin/expenses.php'],
        'admin.reports' => ['Reports', 'admin/reports.php'],
        'admin.hours' => ['Hours', 'admin/hr.php'],
        'admin.settings' => ['Settings', 'admin/settings.php'],
        'admin.users' => ['Users', 'admin/users.php'],
    ];
    $links = [];
    foreach ($pages as $area => $item) {
        if (session_can($area)) {
            $links[] = ['label' => $item[0], 'href' => $root . '/' . $item[1]];
        }
    }
    if (session_can('admin.expenses')) {
        $links[] = ['label' => 'Supplies', 'href' => $root . '/admin/supply.php'];
    }
    return $links;
}

function login_door(): string
{
    $folder = strtolower(basename(rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/')));
    if (in_array($folder, ['admin', 'manager', 'supplier', 'supply'], true)) {
        return $folder === 'supply' ? 'supplier' : $folder;
    }
    $base = strtolower(basename((string) ($_SERVER['SCRIPT_NAME'] ?? '')));
    return $base === 'index.php' ? 'pos' : '';
}

function login_prefix(): string
{
    return login_door() === '' || login_door() === 'pos' ? '' : '../';
}

function cafe_title(): string
{
    try {
        $name = trim((string) (settings()['cafe_name'] ?? ''));
        if ($name !== '') {
            return $name;
        }
    } catch (Throwable $ignored) {
    }
    return 'Ithaa Corner';
}

function refuse_outside_network(): void
{
    if (is_cafe_network()) {
        return;
    }
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><title>Cafe network</title>';
    echo '<body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#111111;color:#f5f5f5;font-family:Segoe UI,sans-serif;padding:1.5rem;">';
    echo '<p>Open this POS on the cafe Wi-Fi or over Tailscale.</p></body></html>';
    exit;
}

function login_door_links(): string
{
    $root = pos_root();
    $links = [
        'Floor' => $root . '/',
        'Admin' => $root . '/admin/',
        'Manager' => $root . '/manager/',
        'Supply' => $root . '/supplier/',
    ];
    $html = '<nav class="doors">';
    foreach ($links as $label => $href) {
        $html .= '<a href="' . h($href) . '">' . h($label) . '</a>';
    }
    return $html . '</nav>';
}

function send_floor_token(string $token): void
{
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');
    echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><title>Sign in</title><script>';
    echo 'localStorage.setItem("cafe_pos_token",' . json_encode($token) . ');';
    echo 'location.replace(' . json_encode(pos_root() . '/waiter/') . ');';
    echo '</script></html>';
    exit;
}

function finish_login(array $user): void
{
    $desk = role_desk((string) $user['role']);
    if ($desk === 'pos') {
        send_floor_token(issue_token((int) $user['id']));
    }
    login_session($user);
    header('Location: ' . desk_home($desk));
    exit;
}

function resume_desk(): void
{
    start_app_session();
    $role = (string) ($_SESSION['role'] ?? '');
    if ($role === '') {
        return;
    }
    $desk = role_desk($role);
    if ($desk === 'pos') {
        logout_owner();
        start_app_session();
        return;
    }
    if ($desk !== 'admin' && login_door() === $desk) {
        return;
    }
    header('Location: ' . desk_home($desk));
    exit;
}

function serve_login(string $door): void
{
    refuse_outside_network();
    resume_desk();
    $error = null;
    $username = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim((string) ($_POST['username'] ?? ''));
        try {
            $sent = (string) ($_POST['csrf'] ?? '');
            $token = (string) ($_SESSION['csrf'] ?? '');
            if ($sent === '' || $token === '' || !hash_equals($token, $sent)) {
                throw new AppError('The form expired. Reload the page and try again.');
            }
            $user = authenticate_user($username, (string) ($_POST['password'] ?? ''));
            if (!role_uses_tailscale((string) $user['role']) && !is_shop_floor()) {
                throw new AppError('Waiters sign in on the cafe Wi-Fi.');
            }
            finish_login($user);
        } catch (AppError $errorObject) {
            $error = $errorObject->getMessage();
        } catch (PDOException $errorObject) {
            $error = 'Database error. Start MySQL and import database/schema.sql.';
        }
    }
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    $labels = [
        '' => ['Sign in', 'Use your own username. This opens the place for your role.'],
        'pos' => ['Floor', 'Waiter sign in for tables, bills, and today.'],
        'admin' => ['Admin', 'Superadmin sign in for the whole site.'],
        'manager' => ['Manager', 'Manager sign in for the floor and the pages your role can open.'],
        'supplier' => ['Supply', 'Supply sign in to price this week.'],
    ];
    [$heading, $blurb] = $labels[$door] ?? $labels[''];
    $cafe = cafe_title();
    $prefix = login_prefix();
    $watchToken = $door === '' || $door === 'pos';
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
    echo '<meta name="theme-color" content="#e6e6e6">';
    echo '<meta name="mobile-web-app-capable" content="yes">';
    echo '<meta name="apple-mobile-web-app-capable" content="yes">';
    echo '<meta name="apple-mobile-web-app-title" content="Ithaa">';
    echo '<title>' . h($heading) . ' · ' . h($cafe) . '</title>';
    echo '<link rel="manifest" href="' . h($prefix) . 'manifest.webmanifest">';
    echo '<link rel="icon" href="' . h($prefix) . 'icon-192.png">';
    echo '<link rel="apple-touch-icon" href="' . h($prefix) . 'apple-touch-icon.png">';
    echo '<link rel="stylesheet" href="' . h($prefix) . 'admin/style.css?v=31">';
    echo '<link rel="stylesheet" href="' . h($prefix) . 'admin/mobile.css?v=7" media="(max-width: 860px)">';
    if ($watchToken) {
        echo '<script>try{if(localStorage.getItem("cafe_pos_token"))location.replace('
            . json_encode(pos_root() . '/waiter/') . ')}catch(error){}</script>';
    }
    echo '</head><body class="login"><main><form class="panel" method="post"><header><h1>'
        . h($cafe) . '</h1><p>' . h($blurb) . '</p></header>';
    if ($error) {
        echo '<p class="error">' . h($error) . '</p>';
    }
    echo '<input type="hidden" name="csrf" value="' . h((string) $_SESSION['csrf']) . '">';
    echo '<label><span>Username</span><input name="username" autocomplete="username" required value="' . h($username) . '"></label>';
    echo '<label><span>Password</span><input type="password" name="password" autocomplete="current-password" required></label>';
    echo '<button type="submit">Sign in</button>';
    echo '<button type="button" id="pwa-install" hidden>Install app</button></form>';
    echo login_door_links();
    echo '</main><script src="' . h($prefix) . 'pwa.js"></script></body></html>';
    exit;
}

function staff_login(string $pin): array
{
    throw new AppError('Sign in with your username and password.', 401);
}

function owner_login(string $password): void
{
    $user = authenticate_user('admin', $password);
    if ($user['role'] !== 'superadmin') {
        throw new AppError('Wrong password.', 401);
    }
    login_session($user);
}

function logout_owner(): void
{
    start_app_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        if (($params['path'] ?? '') !== '/') {
            $clear = [
                'expires' => time() - 42000,
                'path' => '/',
                'secure' => (bool) ($params['secure'] ?? false),
                'httponly' => (bool) ($params['httponly'] ?? true),
                'samesite' => 'Lax',
            ];
            if (!empty($params['domain'])) {
                $clear['domain'] = (string) $params['domain'];
            }
            setcookie(session_name(), '', $clear);
        }
    }
    session_destroy();
}

function ensure_users(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(80) NOT NULL,
            username VARCHAR(40) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            role ENUM(\'superadmin\', \'waiter\', \'manager\') NOT NULL,
            active TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (id),
            UNIQUE KEY users_username (username)
        ) ENGINE=InnoDB'
    );
    if (!column_exists($pdo, 'tokens', 'user_id')) {
        $pdo->exec('ALTER TABLE tokens ADD COLUMN user_id INT UNSIGNED NULL');
        $pdo->exec('DELETE FROM tokens');
    }
    $roleColumn = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'")->fetch();
    $roleType = is_array($roleColumn) ? (string) $roleColumn['Type'] : '';
    if ($roleType !== '' && str_contains(strtolower($roleType), 'enum')) {
        $pdo->exec("ALTER TABLE users MODIFY role VARCHAR(40) NOT NULL");
    }

    $settings = $pdo->query('SELECT owner_password_hash, staff_pin_hash FROM settings WHERE id = 1')->fetch();
    $adminHash = is_array($settings) ? (string) $settings['owner_password_hash'] : '';
    $waiterHash = is_array($settings) ? (string) $settings['staff_pin_hash'] : '';
    if ($adminHash === '') {
        $adminHash = password_hash('admin', PASSWORD_DEFAULT) ?: '';
    }
    if ($waiterHash === '') {
        $waiterHash = password_hash('1234', PASSWORD_DEFAULT) ?: '';
    }
    $insert = $pdo->prepare('INSERT INTO users (name, username, password_hash, role, active) VALUES (?, ?, ?, ?, 1)');
    $hasAdmin = $pdo->query("SELECT id FROM users WHERE role = 'superadmin' LIMIT 1")->fetch();
    if (!$hasAdmin && $adminHash !== '') {
        $insert->execute(['Admin', 'admin', $adminHash, 'superadmin']);
    }
    $hasWaiter = $pdo->query("SELECT id FROM users WHERE role = 'waiter' LIMIT 1")->fetch();
    if (!$hasWaiter && $waiterHash !== '') {
        $insert->execute(['Waiter', 'waiter', $waiterHash, 'waiter']);
    }
    if (!column_exists($pdo, 'users', 'hourly_rate')) {
        $pdo->exec('ALTER TABLE users ADD COLUMN hourly_rate DECIMAL(10,2) NULL');
    }
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS shifts (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id INT UNSIGNED NOT NULL,
            clock_in DATETIME NOT NULL,
            clock_out DATETIME NULL,
            PRIMARY KEY (id),
            KEY shifts_user_open (user_id, clock_out),
            KEY shifts_user_in (user_id, clock_in)
        ) ENGINE=InnoDB'
    );
    ensure_access($pdo);
}

function list_users(): array
{
    $rows = db()->query('SELECT id, name, username, role, active FROM users ORDER BY name')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'username' => $row['username'],
            'role' => $row['role'],
            'active' => (int) $row['active'] === 1,
        ];
    }
    return $out;
}

function user_by_id(int $id): ?array
{
    foreach (list_users() as $user) {
        if ($user['id'] === $id) {
            return $user;
        }
    }
    return null;
}

function active_superadmin_count(int $exceptId = 0): int
{
    $stmt = db()->prepare('SELECT COUNT(*) AS n FROM users WHERE role = "superadmin" AND active = 1 AND id <> ?');
    $stmt->execute([$exceptId]);
    return (int) $stmt->fetch()['n'];
}

function save_user(?int $id, string $name, string $username, string $password, string $role, bool $active): int
{
    $name = trim($name);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Name must be 1 to 80 characters.');
    }
    $username = normalize_username($username);
    if (role_by_slug($role) === null) {
        throw new AppError('Choose a role.');
    }
    if ($role === 'superadmin' && (string) ($_SESSION['role'] ?? '') !== 'superadmin') {
        throw new AppError('Only a superadmin can assign that role.');
    }
    if ($id === null && ($password === '' || strlen($password) < 4 || strlen($password) > 72)) {
        throw new AppError('Password must be 4 to 72 characters.');
    }
    if ($password !== '' && (strlen($password) < 4 || strlen($password) > 72)) {
        throw new AppError('Password must be 4 to 72 characters.');
    }
    $pdo = db();
    $taken = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id <> ?');
    $taken->execute([$username, $id ?? 0]);
    if ($taken->fetch()) {
        throw new AppError('That username is already used.');
    }
    if ($id !== null) {
        $current = user_by_id($id);
        if ($current === null) {
            throw new AppError('User not found.', 404);
        }
        if ($current['role'] === 'superadmin' && (string) ($_SESSION['role'] ?? '') !== 'superadmin') {
            throw new AppError('Only a superadmin can change that account.');
        }
        $staysSuper = $role === 'superadmin' && $active;
        if ($current['role'] === 'superadmin' && $current['active'] && !$staysSuper && active_superadmin_count($id) === 0) {
            throw new AppError('Keep at least one active superadmin.');
        }
    }
    $hash = null;
    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false) {
            throw new AppError('Could not save the password.', 500);
        }
    }
    if ($id === null) {
        $stmt = $pdo->prepare('INSERT INTO users (name, username, password_hash, role, active) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $username, $hash, $role, $active ? 1 : 0]);
        return (int) $pdo->lastInsertId();
    }
    if ($hash === null) {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, username = ?, role = ?, active = ? WHERE id = ?');
        $stmt->execute([$name, $username, $role, $active ? 1 : 0, $id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, username = ?, password_hash = ?, role = ?, active = ? WHERE id = ?');
        $stmt->execute([$name, $username, $hash, $role, $active ? 1 : 0, $id]);
        $pdo->prepare('DELETE FROM tokens WHERE user_id = ?')->execute([$id]);
    }
    if (!$active) {
        $pdo->prepare('DELETE FROM tokens WHERE user_id = ?')->execute([$id]);
    }
    return $id;
}

function delete_user(int $id, int $actorId): void
{
    if ($id === $actorId) {
        throw new AppError('You cannot delete the account you are using.');
    }
    $current = user_by_id($id);
    if ($current === null) {
        throw new AppError('User not found.', 404);
    }
    if ($current['role'] === 'superadmin' && $current['active'] && active_superadmin_count($id) === 0) {
        throw new AppError('Keep at least one active superadmin.');
    }
    $pdo = db();
    $pdo->prepare('DELETE FROM tokens WHERE user_id = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM shifts WHERE user_id = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
}

function update_own_account(int $userId, string $name, string $currentPassword, string $newPassword, ?string $keepToken): void
{
    $name = trim($name);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Name must be 1 to 80 characters.');
    }
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE id = ? AND active = 1');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row) {
        throw new AppError('Sign in required.', 401);
    }
    if ($newPassword !== '') {
        if (strlen($newPassword) < 4 || strlen($newPassword) > 72) {
            throw new AppError('Password must be 4 to 72 characters.');
        }
        if ($currentPassword === '' || !password_verify($currentPassword, (string) $row['password_hash'])) {
            throw new AppError('Current password is wrong.');
        }
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($hash === false) {
            throw new AppError('Could not save the password.', 500);
        }
        $pdo->prepare('UPDATE users SET name = ?, password_hash = ? WHERE id = ?')->execute([$name, $hash, $userId]);
        if ($keepToken !== null && $keepToken !== '') {
            $pdo->prepare('DELETE FROM tokens WHERE user_id = ? AND token <> ?')->execute([$userId, $keepToken]);
        } else {
            $pdo->prepare('DELETE FROM tokens WHERE user_id = ?')->execute([$userId]);
        }
    } else {
        $pdo->prepare('UPDATE users SET name = ? WHERE id = ?')->execute([$name, $userId]);
    }
    if (session_status() === PHP_SESSION_ACTIVE && (int) ($_SESSION['user_id'] ?? 0) === $userId) {
        $_SESSION['user_name'] = $name;
    }
}

function staff_profile(int $userId): array
{
    $stmt = db()->prepare('SELECT id, name, username, role FROM users WHERE id = ? AND active = 1');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row) {
        throw new AppError('Sign in required.', 401);
    }
    return [
        'id' => (int) $row['id'],
        'name' => $row['name'],
        'username' => $row['username'],
        'role' => $row['role'],
        'role_name' => role_label((string) $row['role']),
        'areas' => areas_for_role((string) $row['role']),
        'shift' => open_shift((int) $row['id']),
    ];
}

function open_shift(int $userId): ?array
{
    $stmt = db()->prepare(
        'SELECT id, clock_in FROM shifts WHERE user_id = ? AND clock_out IS NULL ORDER BY id DESC LIMIT 1'
    );
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    return [
        'id' => (int) $row['id'],
        'clock_in' => atom_time($row['clock_in']) ?? (string) $row['clock_in'],
    ];
}

function clock_in_user(int $userId): array
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $lock = $pdo->prepare('SELECT id FROM users WHERE id = ? AND active = 1 FOR UPDATE');
        $lock->execute([$userId]);
        if (!$lock->fetch()) {
            throw new AppError('Sign in required.', 401);
        }
        $existing = open_shift($userId);
        if ($existing === null) {
            $pdo->prepare('INSERT INTO shifts (user_id, clock_in) VALUES (?, ?)')->execute([$userId, date('Y-m-d H:i:s')]);
            $existing = open_shift($userId);
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    if ($existing === null) {
        throw new AppError('Could not clock in.', 500);
    }
    return $existing;
}

function clock_out_user(int $userId): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $lock = $pdo->prepare('SELECT id FROM users WHERE id = ? AND active = 1 FOR UPDATE');
        $lock->execute([$userId]);
        if (!$lock->fetch()) {
            throw new AppError('Sign in required.', 401);
        }
        if (open_shift($userId) === null) {
            throw new AppError('You are not clocked in.');
        }
        $pdo->prepare('UPDATE shifts SET clock_out = ? WHERE user_id = ? AND clock_out IS NULL')->execute([date('Y-m-d H:i:s'), $userId]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
}

function credit_stamp(): string
{
    $row = db()->query(
        'SELECT COUNT(*) AS n, COALESCE(MAX(id), 0) AS last_id, COALESCE(SUM(total), 0) AS amount
         FROM sales WHERE status = "credit"'
    )->fetch();
    return (int) $row['n'] . ':' . (int) $row['last_id'] . ':' . $row['amount'];
}

function hr_minutes_label(int $minutes): string
{
    return intdiv(max(0, $minutes), 60) . ' h ' . (max(0, $minutes) % 60) . ' m';
}

function save_hourly_rate(int $userId, string $raw): void
{
    $check = db()->prepare('SELECT id FROM users WHERE id = ?');
    $check->execute([$userId]);
    if (!$check->fetch()) {
        throw new AppError('User not found.', 404);
    }
    $raw = trim($raw);
    if ($raw === '' || to_cents(book_amount($raw)) === 0) {
        db()->prepare('UPDATE users SET hourly_rate = NULL WHERE id = ?')->execute([$userId]);
        return;
    }
    db()->prepare('UPDATE users SET hourly_rate = ? WHERE id = ?')->execute([book_amount($raw), $userId]);
}

function hr_month_rows(int $year, int $month): array
{
    if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
        throw new AppError('Choose a real month.');
    }
    $start = sprintf('%04d-%02d-01 00:00:00', $year, $month);
    $end = (new DateTime($start))->modify('first day of next month')->format('Y-m-d H:i:s');
    $now = date('Y-m-d H:i:s');
    $users = db()->query(
        "SELECT id, name, username, role, hourly_rate FROM users WHERE active = 1 ORDER BY FIELD(role, 'superadmin', 'manager', 'waiter'), name"
    )->fetchAll();
    $stmt = db()->prepare(
        'SELECT user_id, clock_in, clock_out FROM shifts
         WHERE clock_in < ? AND (clock_out IS NULL OR clock_out > ?)
         ORDER BY clock_in'
    );
    $stmt->execute([$end, $start]);
    $byUser = [];
    foreach ($stmt as $row) {
        $byUser[(int) $row['user_id']][] = $row;
    }
    $out = [];
    foreach ($users as $user) {
        $minutes = 0;
        $open = false;
        $shifts = [];
        foreach ($byUser[(int) $user['id']] ?? [] as $shift) {
            $in = (string) $shift['clock_in'];
            $rawOut = $shift['clock_out'] !== null ? (string) $shift['clock_out'] : null;
            $openShift = $rawOut === null;
            if ($openShift) {
                $open = true;
                $cap = date('Y-m-d H:i:s', strtotime($in) + 16 * 3600);
                $rawOut = $now < $cap ? $now : $cap;
            }
            $clipIn = max($in, $start);
            $clipOut = min((string) $rawOut, $end);
            $part = 0;
            $inTime = strtotime($clipIn);
            $outTime = strtotime($clipOut);
            if ($inTime !== false && $outTime !== false && $outTime >= $inTime) {
                $part = (int) ceil(($outTime - $inTime) / 60);
                if ($part === 0 && !$openShift) {
                    $part = 1;
                }
            }
            $minutes += $part;
            $shifts[] = [
                'in' => (string) $shift['clock_in'],
                'out' => $shift['clock_out'] !== null ? (string) $shift['clock_out'] : null,
                'minutes' => $part,
                'open' => $openShift,
            ];
        }
        $rate = $user['hourly_rate'];
        $payCents = 0;
        if ($rate !== null && $rate !== '') {
            $payCents = (int) round($minutes * to_cents((string) $rate) / 60);
        }
        $out[] = [
            'user' => $user,
            'minutes' => $minutes,
            'pay' => from_cents($payCents),
            'open' => $open,
            'shifts' => $shifts,
        ];
    }
    return $out;
}

function menu(): array
{
    $pdo = db();
    $categories = $pdo->query('SELECT id, name FROM categories ORDER BY sort_order, name')->fetchAll();
    $items = $pdo->query('SELECT id, category_id, name, price, photo FROM items WHERE active = 1 ORDER BY sort_order, name')->fetchAll();
    $byCategory = [];
    foreach ($items as $item) {
        $byCategory[(int) $item['category_id']][] = [
            'id' => (int) $item['id'],
            'name' => $item['name'],
            'price' => from_cents(to_cents((string) $item['price'])),
            'photo' => item_photo_token((int) $item['id'], (string) $item['photo']),
        ];
    }
    $out = [];
    foreach ($categories as $category) {
        $id = (int) $category['id'];
        if (empty($byCategory[$id])) {
            continue;
        }
        $out[] = [
            'id' => $id,
            'name' => $category['name'],
            'items' => $byCategory[$id],
        ];
    }
    return [
        'currency_symbol' => settings()['currency_symbol'],
        'cafe_name' => settings()['cafe_name'],
        'categories' => $out,
    ];
}

function menu_stamp(): string
{
    $rows = db()->query('SELECT id, category_id, name, price, active, sort_order, photo FROM items ORDER BY id')->fetchAll();
    foreach ($rows as &$row) {
        $row['photo_token'] = item_photo_token((int) $row['id'], (string) $row['photo']);
    }
    unset($row);
    return md5(json_encode($rows));
}

function column_exists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '` LIKE ?');
    $stmt->execute([$column]);
    return (bool) $stmt->fetch();
}

function ensure_ithaa_menu(PDO $pdo): void
{
    if (!column_exists($pdo, 'items', 'sort_order')) {
        $pdo->exec('ALTER TABLE items ADD COLUMN sort_order INT NOT NULL DEFAULT 0');
    }
    if (!column_exists($pdo, 'items', 'photo')) {
        $pdo->exec("ALTER TABLE items ADD COLUMN photo VARCHAR(80) NOT NULL DEFAULT ''");
    }

    $check = $pdo->prepare('SELECT id FROM items WHERE name = ? LIMIT 1');
    $check->execute(['Black Coffee Hard']);
    if ($check->fetch()) {
        return;
    }

    $hide = $pdo->prepare('UPDATE items SET active = 0 WHERE name = ?');
    foreach ([
        'Espresso', 'Americano', 'Cappuccino', 'Tea', 'Orange juice',
        'Croissant', 'Muffin', 'Sandwich', 'Brownie',
        'Black Coffee', 'Water bottle', 'Nuts',
    ] as $name) {
        $hide->execute([$name]);
    }

    $drinks = $pdo->query("SELECT id FROM categories WHERE name = 'Drinks' ORDER BY id LIMIT 1")->fetch();
    $food = $pdo->query("SELECT id FROM categories WHERE name = 'Food' ORDER BY id LIMIT 1")->fetch();
    if (!$drinks) {
        $pdo->exec("INSERT INTO categories (name, sort_order) VALUES ('Drinks', 1)");
        $drinksId = (int) $pdo->lastInsertId();
    } else {
        $drinksId = (int) $drinks['id'];
    }
    if (!$food) {
        $pdo->exec("INSERT INTO categories (name, sort_order) VALUES ('Food', 2)");
        $foodId = (int) $pdo->lastInsertId();
    } else {
        $foodId = (int) $food['id'];
    }

    $find = $pdo->prepare('SELECT id FROM items WHERE name = ? LIMIT 1');
    $update = $pdo->prepare('UPDATE items SET category_id = ?, price = ?, active = 1, sort_order = ? WHERE id = ?');
    $insert = $pdo->prepare('INSERT INTO items (category_id, name, price, active, sort_order) VALUES (?, ?, ?, 1, ?)');
    $rows = [
        [$drinksId, 'Black Coffee Hard', '10.00', 1],
        [$drinksId, 'Black Coffee Normal', '7.00', 2],
        [$drinksId, 'Milk Coffee', '10.00', 3],
        [$drinksId, 'Cereal Coffee', '15.00', 4],
        [$drinksId, 'Lemon Tea', '10.00', 5],
        [$drinksId, 'Black Tea Large', '5.00', 6],
        [$drinksId, 'Black Tea Small', '3.00', 7],
        [$drinksId, 'Water Bottle Small', '5.00', 8],
        [$drinksId, 'Water Bottle Big', '10.00', 9],
        [$foodId, 'Nuts Half', '5.00', 1],
        [$foodId, 'Nuts Full', '10.00', 2],
        [$foodId, 'Hedhika', '5.00', 3],
        [$foodId, 'Bisket', '5.00', 4],
    ];
    foreach ($rows as [$categoryId, $name, $price, $sort]) {
        $find->execute([$name]);
        $existing = $find->fetch();
        if ($existing) {
            $update->execute([$categoryId, $price, $sort, $existing['id']]);
            continue;
        }
        $insert->execute([$categoryId, $name, $price, $sort]);
    }
}

function ensure_ithaa_tables(PDO $pdo): void
{
    $check = $pdo->prepare('SELECT id FROM cafe_tables WHERE name = ? LIMIT 1');
    $check->execute(['Hut 1']);
    if ($check->fetch()) {
        return;
    }

    $hide = $pdo->prepare('UPDATE cafe_tables SET active = 0 WHERE name = ?');
    foreach (['Table 1', 'Table 2', 'Table 3', 'Table 4', 'Table 5', 'Table 6'] as $name) {
        $hide->execute([$name]);
    }

    $insert = $pdo->prepare('INSERT INTO cafe_tables (name, sort_order, active) VALUES (?, ?, 1)');
    foreach (
        [
            ['Hut 1', 1],
            ['Hut 2', 2],
            ['Hut 3', 3],
            ['Hut 4', 4],
            ['Outdoor 1', 5],
            ['Outdoor 2', 6],
            ['Outdoor 3', 7],
            ['Outdoor 4', 8],
            ['Indoor 1', 9],
        ] as $table
    ) {
        $insert->execute($table);
    }
}

function ensure_schema(PDO $pdo): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    $sales = $pdo->query("SHOW TABLES LIKE 'sales'")->fetch();
    if ($sales && (!column_exists($pdo, 'sales', 'status') || column_exists($pdo, 'sales', 'created_at'))) {
        if (!column_exists($pdo, 'sales', 'table_id')) {
            $pdo->exec('ALTER TABLE sales ADD COLUMN table_id INT UNSIGNED NULL');
        }
        if (!column_exists($pdo, 'sales', 'table_name')) {
            $pdo->exec("ALTER TABLE sales ADD COLUMN table_name VARCHAR(40) NOT NULL DEFAULT 'Counter'");
        }
        if (!column_exists($pdo, 'sales', 'opened_at')) {
            $pdo->exec('ALTER TABLE sales ADD COLUMN opened_at DATETIME NULL');
        }
        if (!column_exists($pdo, 'sales', 'closed_at')) {
            $pdo->exec('ALTER TABLE sales ADD COLUMN closed_at DATETIME NULL');
        }
        if (!column_exists($pdo, 'sales', 'status')) {
            $pdo->exec("ALTER TABLE sales ADD COLUMN status ENUM('open','paid') NOT NULL DEFAULT 'paid'");
        }
        if (column_exists($pdo, 'sales', 'created_at')) {
            $pdo->exec('UPDATE sales SET opened_at = created_at, closed_at = created_at WHERE opened_at IS NULL');
            $pdo->exec('ALTER TABLE sales DROP COLUMN created_at');
        }
        $pdo->exec('UPDATE sales SET opened_at = NOW() WHERE opened_at IS NULL');
        $pdo->exec('ALTER TABLE sales MODIFY opened_at DATETIME NOT NULL');
    }

    if ($sales && !column_exists($pdo, 'sales', 'service_started_at')) {
        $pdo->exec('ALTER TABLE sales ADD COLUMN service_started_at DATETIME NULL');
        $pdo->exec(
            'UPDATE sales s
             SET service_started_at = opened_at
             WHERE service_started_at IS NULL
               AND EXISTS (SELECT 1 FROM sale_lines l WHERE l.sale_id = s.id)'
        );
    }

    $settings = $pdo->query("SHOW TABLES LIKE 'settings'")->fetch();
    if ($settings) {
        if (!column_exists($pdo, 'settings', 'cafe_name')) {
            $pdo->exec("ALTER TABLE settings ADD COLUMN cafe_name VARCHAR(80) NOT NULL DEFAULT 'Ithaa Corner'");
        }
        $pdo->exec("UPDATE settings SET currency_symbol = 'MVR' WHERE id = 1 AND currency_symbol = '$'");
        $pdo->exec("UPDATE settings SET cafe_name = 'Ithaa Corner' WHERE id = 1 AND cafe_name = ''");
        if (!column_exists($pdo, 'settings', 'bank_image')) {
            $pdo->exec("ALTER TABLE settings ADD COLUMN bank_image VARCHAR(80) NOT NULL DEFAULT ''");
        }
        if (!column_exists($pdo, 'settings', 'order_host')) {
            $pdo->exec("ALTER TABLE settings ADD COLUMN order_host VARCHAR(80) NOT NULL DEFAULT ''");
        }
        if (!column_exists($pdo, 'settings', 'default_credit_limit')) {
            $pdo->exec('ALTER TABLE settings ADD COLUMN default_credit_limit DECIMAL(10,2) NULL DEFAULT 200.00');
            $pdo->exec('UPDATE settings SET default_credit_limit = 200.00 WHERE id = 1 AND default_credit_limit IS NULL');
        }
    }
    $customers = $pdo->query("SHOW TABLES LIKE 'customers'")->fetch();
    if ($customers && !column_exists($pdo, 'customers', 'password_hash')) {
        $pdo->exec('ALTER TABLE customers ADD COLUMN password_hash VARCHAR(255) NULL');
    }
    if (column_exists($pdo, 'tokens', 'token') && !column_exists($pdo, 'tokens', 'customer_id')) {
        $pdo->exec('ALTER TABLE tokens ADD COLUMN customer_id INT UNSIGNED NULL');
        $pdo->exec('ALTER TABLE tokens ADD KEY tokens_customer (customer_id)');
    }

    $items = $pdo->query("SHOW TABLES LIKE 'items'")->fetch();
    if ($items) {
        ensure_ithaa_menu($pdo);
    }

    if ($sales) {
        $payment = $pdo->query("SHOW COLUMNS FROM sales LIKE 'payment_method'")->fetch();
        $type = is_array($payment) ? (string) $payment['Type'] : '';
        if ($type !== '' && !str_contains($type, 'online')) {
            $pdo->exec("ALTER TABLE sales MODIFY payment_method ENUM('cash','card','online','credit') NULL");
            $pdo->exec("UPDATE sales SET payment_method = 'credit' WHERE payment_method = 'card'");
            $pdo->exec("ALTER TABLE sales MODIFY payment_method ENUM('cash','online','credit') NULL");
        }
        if (!column_exists($pdo, 'sales', 'slip_image')) {
            $pdo->exec("ALTER TABLE sales ADD COLUMN slip_image VARCHAR(80) NOT NULL DEFAULT ''");
        }
        if (!column_exists($pdo, 'sales', 'pay_review')) {
            $pdo->exec("ALTER TABLE sales ADD COLUMN pay_review VARCHAR(12) NOT NULL DEFAULT ''");
        }
        if (!column_exists($pdo, 'sales', 'waiter_id')) {
            $pdo->exec('ALTER TABLE sales ADD COLUMN waiter_id INT UNSIGNED NULL');
        }
        if (!column_exists($pdo, 'sales', 'customer_id')) {
            $pdo->exec('ALTER TABLE sales ADD COLUMN customer_id INT UNSIGNED NULL');
        }
        if (!column_exists($pdo, 'sales', 'held')) {
            $pdo->exec('ALTER TABLE sales ADD COLUMN held TINYINT(1) NOT NULL DEFAULT 0');
            $pdo->exec('UPDATE sales SET held = 1 WHERE status = "credit"');
        }
        $status = $pdo->query("SHOW COLUMNS FROM sales LIKE 'status'")->fetch();
        $statusType = is_array($status) ? (string) $status['Type'] : '';
        if ($statusType !== '' && !str_contains($statusType, 'credit')) {
            $pdo->exec("ALTER TABLE sales MODIFY status ENUM('open','credit','paid') NOT NULL DEFAULT 'open'");
        }
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS customers (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(80) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            credit_limit DECIMAL(10,2) NULL,
            PRIMARY KEY (id),
            UNIQUE KEY customers_phone (phone)
        ) ENGINE=InnoDB'
    );
    if (!column_exists($pdo, 'customers', 'credit_limit')) {
        $pdo->exec('ALTER TABLE customers ADD COLUMN credit_limit DECIMAL(10,2) NULL');
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS settlements (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            reference VARCHAR(24) NOT NULL DEFAULT "",
            customer_id INT UNSIGNED NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            payment_method ENUM("cash","online") NOT NULL,
            payment_slip VARCHAR(80) NOT NULL DEFAULT "",
            settled_by INT UNSIGNED NULL,
            settled_at DATETIME NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY settlements_reference (reference)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS settlement_bills (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            settlement_id INT UNSIGNED NOT NULL,
            sale_id INT UNSIGNED NOT NULL,
            amount_applied DECIMAL(10,2) NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY settlement_bills_sale (sale_id),
            KEY settlement_bills_settlement (settlement_id)
        ) ENGINE=InnoDB'
    );

    $tables = $pdo->query("SHOW TABLES LIKE 'cafe_tables'")->fetch();
    if (!$tables) {
        $pdo->exec(
            'CREATE TABLE cafe_tables (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(40) NOT NULL,
                sort_order INT NOT NULL DEFAULT 0,
                active TINYINT(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB'
        );
        $pdo->exec(
            "INSERT INTO cafe_tables (name, sort_order, active) VALUES
            ('Hut 1', 1, 1), ('Hut 2', 2, 1), ('Hut 3', 3, 1), ('Hut 4', 4, 1),
            ('Outdoor 1', 5, 1), ('Outdoor 2', 6, 1), ('Outdoor 3', 7, 1), ('Outdoor 4', 8, 1),
            ('Indoor 1', 9, 1)"
        );
    } else {
        ensure_ithaa_tables($pdo);
    }
    ensure_floor_areas($pdo);

    $open = $pdo->query("SHOW TABLES LIKE 'open_tables'")->fetch();
    if (!$open) {
        $pdo->exec(
            'CREATE TABLE open_tables (
                table_id INT UNSIGNED NOT NULL,
                sale_id INT UNSIGNED NOT NULL,
                PRIMARY KEY (table_id),
                UNIQUE KEY open_tables_sale (sale_id)
            ) ENGINE=InnoDB'
        );
    }
    $lineTable = $pdo->query("SHOW TABLES LIKE 'sale_lines'")->fetch();
    if ($lineTable && !column_exists($pdo, 'sale_lines', 'added_at')) {
        $pdo->exec('ALTER TABLE sale_lines ADD COLUMN added_at DATETIME NULL');
        $pdo->exec(
            'UPDATE sale_lines l
             JOIN sales s ON s.id = l.sale_id
             SET l.added_at = s.opened_at
             WHERE l.added_at IS NULL'
        );
        $pdo->exec('UPDATE sale_lines SET added_at = NOW() WHERE added_at IS NULL');
        $pdo->exec('ALTER TABLE sale_lines MODIFY added_at DATETIME NOT NULL');
    }

    ensure_books($pdo);
}

function sales_for_date(string $date): array
{
    return sales_for_range($date, $date);
}

function sales_for_range(string $from, string $to): array
{
    $from = parse_date($from);
    $to = parse_date($to);
    if ($to < $from) {
        throw new AppError('Choose a valid date range.');
    }
    if ($to > date('Y-m-d')) {
        $to = date('Y-m-d');
    }
    $date = $from;
    $start = $from . ' 00:00:00';
    $end = (new DateTime($to))->modify('+1 day')->format('Y-m-d') . ' 00:00:00';

    $stmt = db()->prepare(
        'SELECT s.id, s.opened_at, s.closed_at, s.service_started_at, s.table_id, s.table_name, s.payment_method, s.customer_id, s.total, s.slip_image, s.held,
                u.name AS waiter_name, c.name AS customer_name, c.phone AS customer_phone, st.reference AS settlement_reference,
                l.item_id, l.item_name, l.unit_price, l.qty, l.line_total, l.added_at
         FROM sales s
         LEFT JOIN users u ON u.id = s.waiter_id
         LEFT JOIN customers c ON c.id = s.customer_id
         LEFT JOIN settlement_bills sb ON sb.sale_id = s.id
         LEFT JOIN settlements st ON st.id = sb.settlement_id
         LEFT JOIN sale_lines l ON l.sale_id = s.id
         WHERE s.status = "paid" AND s.closed_at >= ? AND s.closed_at < ?
         ORDER BY s.closed_at DESC, l.added_at, l.id ASC'
    );
    $stmt->execute([$start, $end]);

    $sales = [];
    $count = 0;
    $total = 0;
    $cash = 0;
    $online = 0;
    $credit = 0;
    foreach ($stmt->fetchAll() as $row) {
        $id = (int) $row['id'];
        if (!isset($sales[$id])) {
            $cents = to_cents((string) $row['total']);
            if ($row['payment_method'] === 'cash') {
                $count++;
                $total += $cents;
                $cash += $cents;
            } elseif ($row['payment_method'] === 'online') {
                $count++;
                $total += $cents;
                $online += $cents;
            } elseif ($row['payment_method'] === 'credit' || $row['payment_method'] === 'card') {
                $credit += $cents;
            }
            $sales[$id] = [
                'id' => $id,
                'opened_at' => $row['opened_at'],
                'closed_at' => $row['closed_at'],
                'table_id' => $row['table_id'] !== null ? (int) $row['table_id'] : 0,
                'table_name' => $row['table_name'],
                'customer_id' => $row['customer_id'] !== null ? (int) $row['customer_id'] : 0,
                'payment_method' => $row['payment_method'],
                'payment_label' => payment_label($row['payment_method']),
                'has_slip' => trim((string) ($row['slip_image'] ?? '')) !== '',
                'duration_label' => duration_label($row['service_started_at'], $row['closed_at']),
                'waiter_name' => (string) ($row['waiter_name'] ?? ''),
                'customer_name' => (string) ($row['customer_name'] ?? ''),
                'customer_phone' => (string) ($row['customer_phone'] ?? ''),
                'settlement_reference' => (string) ($row['settlement_reference'] ?? ''),
                'held' => (int) ($row['held'] ?? 0) === 1,
                'status' => 'paid',
                'total' => from_cents($cents),
                'lines' => [],
            ];
        }
        if ($row['item_name'] !== null) {
            $sales[$id]['lines'][] = [
                'item_id' => $row['item_id'] !== null ? (int) $row['item_id'] : 0,
                'item_name' => $row['item_name'],
                'unit_price' => from_cents(to_cents((string) $row['unit_price'])),
                'qty' => (int) $row['qty'],
                'line_total' => from_cents(to_cents((string) $row['line_total'])),
                'added_at' => (string) ($row['added_at'] ?? ''),
            ];
        }
    }

    $creditStmt = db()->prepare(
        'SELECT s.id, s.opened_at, s.table_id, s.table_name, s.customer_id, s.total,
                c.name AS customer_name, c.phone AS customer_phone, c.credit_limit,
                u.name AS waiter_name,
                l.item_id, l.item_name, l.unit_price, l.qty, l.line_total, l.added_at
         FROM sales s
         LEFT JOIN customers c ON c.id = s.customer_id
         LEFT JOIN users u ON u.id = s.waiter_id
         LEFT JOIN sale_lines l ON l.sale_id = s.id
         WHERE s.status = "credit" AND s.opened_at >= ? AND s.opened_at < ?
         ORDER BY s.opened_at DESC, l.added_at, l.id ASC'
    );
    $creditStmt->execute([$start, $end]);
    $credits = [];
    $creditOpen = 0;
    foreach ($creditStmt->fetchAll() as $row) {
        $id = (int) $row['id'];
        if (!isset($credits[$id])) {
            $cents = to_cents((string) $row['total']);
            $creditOpen += $cents;
            $limit = $row['credit_limit'];
            $credits[$id] = [
                'id' => $id,
                'opened_at' => $row['opened_at'],
                'closed_at' => '',
                'table_id' => $row['table_id'] !== null ? (int) $row['table_id'] : 0,
                'table_name' => $row['table_name'],
                'customer_id' => $row['customer_id'] !== null ? (int) $row['customer_id'] : 0,
                'payment_method' => 'credit',
                'waiter_name' => (string) ($row['waiter_name'] ?? ''),
                'customer_name' => (string) ($row['customer_name'] ?? ''),
                'customer_phone' => (string) ($row['customer_phone'] ?? ''),
                'credit_limit' => $limit === null || $limit === '' ? null : from_cents(to_cents((string) $limit)),
                'status' => 'credit',
                'total' => from_cents($cents),
                'lines' => [],
            ];
        }
        if ($row['item_name'] !== null) {
            $credits[$id]['lines'][] = [
                'item_id' => $row['item_id'] !== null ? (int) $row['item_id'] : 0,
                'item_name' => $row['item_name'],
                'unit_price' => from_cents(to_cents((string) $row['unit_price'])),
                'qty' => (int) $row['qty'],
                'line_total' => from_cents(to_cents((string) $row['line_total'])),
                'added_at' => (string) ($row['added_at'] ?? ''),
            ];
        }
    }

    $openRows = db()->query(
        'SELECT s.id, s.opened_at, s.table_name, s.customer_id, s.total, c.name AS customer_name
         FROM sales s
         LEFT JOIN customers c ON c.id = s.customer_id
         WHERE s.status = "open" AND EXISTS (SELECT 1 FROM sale_lines l WHERE l.sale_id = s.id)
         ORDER BY s.opened_at DESC'
    )->fetchAll();
    $open = [];
    foreach ($openRows as $row) {
        $open[] = [
            'id' => (int) $row['id'],
            'opened_at' => $row['opened_at'],
            'closed_at' => '',
            'table_name' => $row['table_name'],
            'customer_id' => $row['customer_id'] !== null ? (int) $row['customer_id'] : 0,
            'customer_name' => (string) ($row['customer_name'] ?? ''),
            'payment_method' => '',
            'status' => 'open',
            'total' => from_cents(to_cents((string) $row['total'])),
        ];
    }

    return [
        'date' => $date,
        'to' => $to,
        'currency_symbol' => settings()['currency_symbol'],
        'summary' => [
            'count' => $count,
            'total' => from_cents($total),
            'cash_total' => from_cents($cash),
            'online_total' => from_cents($online),
            'credit_total' => from_cents($credit),
            'credit_open' => from_cents($creditOpen),
        ],
        'sales' => array_values($sales),
        'credits' => array_values($credits),
        'open' => $open,
    ];
}

function list_categories(): array
{
    $rows = db()->query(
        'SELECT c.id, c.name, c.sort_order, COUNT(i.id) AS item_count
         FROM categories c
         LEFT JOIN items i ON i.category_id = c.id
         GROUP BY c.id, c.name, c.sort_order
         ORDER BY c.sort_order, c.name'
    )->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'sort_order' => (int) $row['sort_order'],
            'item_count' => (int) $row['item_count'],
        ];
    }
    return $out;
}

function save_category(?int $id, string $name, int $sortOrder): int
{
    $name = trim($name);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Category name must be 1 to 80 characters.');
    }
    if ($sortOrder < 0 || $sortOrder > 9999) {
        throw new AppError('Sort order must be from 0 to 9999.');
    }
    $pdo = db();
    if ($id === null) {
        $stmt = $pdo->prepare('INSERT INTO categories (name, sort_order) VALUES (?, ?)');
        $stmt->execute([$name, $sortOrder]);
        return (int) $pdo->lastInsertId();
    }
    $stmt = $pdo->prepare('UPDATE categories SET name = ?, sort_order = ? WHERE id = ?');
    $stmt->execute([$name, $sortOrder, $id]);
    if ($stmt->rowCount() === 0) {
        $check = $pdo->prepare('SELECT id FROM categories WHERE id = ?');
        $check->execute([$id]);
        if (!$check->fetch()) {
            throw new AppError('Category not found.', 404);
        }
    }
    return $id;
}

function delete_category(int $id): void
{
    $pdo = db();
    $count = $pdo->prepare('SELECT COUNT(*) AS n FROM items WHERE category_id = ?');
    $count->execute([$id]);
    if ((int) $count->fetch()['n'] > 0) {
        throw new AppError('Move or remove this category’s items before deleting it.');
    }
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) {
        throw new AppError('Category not found.', 404);
    }
}

function list_items(): array
{
    $rows = db()->query(
        'SELECT i.id, i.category_id, i.name, i.price, i.active, i.sort_order, i.photo, c.name AS category_name
         FROM items i
         JOIN categories c ON c.id = i.category_id
         ORDER BY c.sort_order, c.name, i.sort_order, i.name'
    )->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'category_id' => (int) $row['category_id'],
            'category_name' => $row['category_name'],
            'name' => $row['name'],
            'price' => from_cents(to_cents((string) $row['price'])),
            'active' => (int) $row['active'] === 1,
            'sort_order' => (int) $row['sort_order'],
            'photo' => item_photo_token((int) $row['id'], (string) ($row['photo'] ?? '')) !== '',
        ];
    }
    return $out;
}

function save_item(?int $id, array $input): int
{
    $name = trim((string) ($input['name'] ?? ''));
    if ($name === '' || strlen($name) > 120) {
        throw new AppError('Item name must be 1 to 120 characters.');
    }
    $categoryId = (int) ($input['category_id'] ?? 0);
    $price = normalize_price((string) ($input['price'] ?? ''));
    $active = true;
    if (array_key_exists('active', $input)) {
        $flag = $input['active'];
        $active = !($flag === false || $flag === 0 || $flag === '0' || $flag === '');
    }

    $pdo = db();
    $category = $pdo->prepare('SELECT id FROM categories WHERE id = ?');
    $category->execute([$categoryId]);
    if (!$category->fetch()) {
        throw new AppError('Choose a category.');
    }

    if ($id === null) {
        $sort = $pdo->prepare('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM items WHERE category_id = ?');
        $sort->execute([$categoryId]);
        $sortOrder = (int) $sort->fetchColumn();
        $stmt = $pdo->prepare('INSERT INTO items (category_id, name, price, active, sort_order) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$categoryId, $name, $price, $active ? 1 : 0, $sortOrder]);
        return (int) $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare('UPDATE items SET category_id = ?, name = ?, price = ?, active = ? WHERE id = ?');
    $stmt->execute([$categoryId, $name, $price, $active ? 1 : 0, $id]);
    if ($stmt->rowCount() === 0) {
        $check = $pdo->prepare('SELECT id FROM items WHERE id = ?');
        $check->execute([$id]);
        if (!$check->fetch()) {
            throw new AppError('Item not found.', 404);
        }
    }
    return $id;
}

function move_item(int $id, int $direction): void
{
    if ($direction !== -1 && $direction !== 1) {
        throw new AppError('Choose where to move this item.');
    }
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, category_id, sort_order FROM items WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        throw new AppError('Item not found.', 404);
    }
    $sql = $direction < 0
        ? 'SELECT id, sort_order FROM items WHERE category_id = ? AND (sort_order < ? OR (sort_order = ? AND id < ?)) ORDER BY sort_order DESC, id DESC LIMIT 1'
        : 'SELECT id, sort_order FROM items WHERE category_id = ? AND (sort_order > ? OR (sort_order = ? AND id > ?)) ORDER BY sort_order ASC, id ASC LIMIT 1';
    $neighbor = $pdo->prepare($sql);
    $neighbor->execute([(int) $item['category_id'], (int) $item['sort_order'], (int) $item['sort_order'], (int) $item['id']]);
    $other = $neighbor->fetch();
    if (!$other) {
        return;
    }
    $here = (int) $item['sort_order'];
    $there = (int) $other['sort_order'];
    if ($here === $there) {
        $there = $here + $direction;
    }
    $pdo->beginTransaction();
    try {
        $update = $pdo->prepare('UPDATE items SET sort_order = ? WHERE id = ?');
        $update->execute([$there, (int) $item['id']]);
        $update->execute([$here, (int) $other['id']]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
}

function update_settings(array $input): void
{
    $current = settings();
    $currency = array_key_exists('currency_symbol', $input)
        ? trim((string) $input['currency_symbol'])
        : $current['currency_symbol'];
    if ($currency === '' || strlen($currency) > 8) {
        throw new AppError('Currency symbol must be 1 to 8 characters.');
    }

    $pinHash = $current['staff_pin_hash'];
    $passwordHash = $current['owner_password_hash'];
    $pinChanged = false;

    if (isset($input['staff_pin']) && (string) $input['staff_pin'] !== '') {
        $pin = (string) $input['staff_pin'];
        if (!preg_match('/^\d{4,8}$/', $pin)) {
            throw new AppError('PIN must be 4 to 8 digits.');
        }
        $pinHash = password_hash($pin, PASSWORD_DEFAULT);
        if ($pinHash === false) {
            throw new AppError('Could not save the PIN.', 500);
        }
        $pinChanged = true;
    }

    if (isset($input['owner_password']) && (string) $input['owner_password'] !== '') {
        $password = (string) $input['owner_password'];
        if (strlen($password) < 4 || strlen($password) > 72) {
            throw new AppError('Password must be 4 to 72 characters.');
        }
        if (!password_verify((string) ($input['current_password'] ?? ''), $current['owner_password_hash'])) {
            throw new AppError('Current password is wrong.');
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if ($passwordHash === false) {
            throw new AppError('Could not save the password.', 500);
        }
    }

    $stmt = db()->prepare('UPDATE settings SET owner_password_hash = ?, staff_pin_hash = ?, currency_symbol = ? WHERE id = 1');
    $stmt->execute([$passwordHash, $pinHash, $currency]);
    if (array_key_exists('default_credit_limit', $input)) {
        $raw = trim((string) $input['default_credit_limit']);
        if ($raw === '') {
            $stored = null;
        } else {
            $cents = to_cents($raw);
            if ($cents > 9999999999) {
                throw new AppError('Credit limit is too large.');
            }
            $stored = from_cents($cents);
        }
        db()->prepare('UPDATE settings SET default_credit_limit = ? WHERE id = 1')->execute([$stored]);
    }
    if ($pinChanged) {
        db()->exec('DELETE FROM tokens');
    }
}

function ensure_floor_areas(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS floor_areas (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(40) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY floor_areas_name (name)
        ) ENGINE=InnoDB'
    );
    $added = !column_exists($pdo, 'cafe_tables', 'area_id');
    if ($added) {
        $pdo->exec('ALTER TABLE cafe_tables ADD COLUMN area_id INT UNSIGNED NULL');
        $pdo->exec('ALTER TABLE cafe_tables ADD KEY cafe_tables_area (area_id)');
    }
    $count = (int) $pdo->query('SELECT COUNT(*) FROM floor_areas')->fetchColumn();
    if ($count === 0) {
        $insert = $pdo->prepare('INSERT INTO floor_areas (name, sort_order) VALUES (?, ?)');
        $insert->execute(['Hut', 1]);
        $insert->execute(['Outdoor', 2]);
        $insert->execute(['Indoor', 3]);
    }
    if ($added || $count === 0) {
        $areas = $pdo->query('SELECT id, name FROM floor_areas')->fetchAll();
        $find = $pdo->prepare('SELECT id, name FROM cafe_tables WHERE area_id IS NULL AND name LIKE ?');
        $set = $pdo->prepare('UPDATE cafe_tables SET area_id = ? WHERE id = ?');
        foreach ($areas as $area) {
            $prefix = $area['name'] . ' ';
            $find->execute([$prefix . '%']);
            foreach ($find->fetchAll() as $table) {
                if (str_starts_with((string) $table['name'], $prefix)) {
                    $set->execute([(int) $area['id'], (int) $table['id']]);
                }
            }
        }
    }
}

function list_floor_areas(): array
{
    $rows = db()->query('SELECT id, name, sort_order FROM floor_areas ORDER BY sort_order, name')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'sort_order' => (int) $row['sort_order'],
        ];
    }
    return $out;
}

function floor_area_row(int $id): ?array
{
    $stmt = db()->prepare('SELECT id, name, sort_order FROM floor_areas WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    return [
        'id' => (int) $row['id'],
        'name' => (string) $row['name'],
        'sort_order' => (int) $row['sort_order'],
    ];
}

function table_seat_label(string $name, string $areaName): string
{
    $prefix = $areaName !== '' ? $areaName . ' ' : '';
    if ($prefix !== '' && str_starts_with($name, $prefix)) {
        return substr($name, strlen($prefix));
    }
    return $name;
}

function save_floor_area(?int $id, string $name, int $sortOrder): int
{
    $name = trim($name);
    if ($name === '' || strlen($name) > 40) {
        throw new AppError('Area name must be 1 to 40 characters.');
    }
    if ($sortOrder < 0 || $sortOrder > 9999) {
        throw new AppError('Sort order must be from 0 to 9999.');
    }
    $pdo = db();
    $taken = $pdo->prepare('SELECT id FROM floor_areas WHERE name = ? AND id <> ?');
    $taken->execute([$name, $id ?? 0]);
    if ($taken->fetch()) {
        throw new AppError('That area already exists.');
    }
    if ($id === null) {
        $pdo->prepare('INSERT INTO floor_areas (name, sort_order) VALUES (?, ?)')->execute([$name, $sortOrder]);
        return (int) $pdo->lastInsertId();
    }
    $current = floor_area_row($id);
    if ($current === null) {
        throw new AppError('That area was not found.', 404);
    }
    $pdo->beginTransaction();
    try {
        $pdo->prepare('UPDATE floor_areas SET name = ?, sort_order = ? WHERE id = ?')->execute([$name, $sortOrder, $id]);
        if ($current['name'] !== $name) {
            $oldPrefix = $current['name'] . ' ';
            $rows = $pdo->prepare('SELECT id, name FROM cafe_tables WHERE area_id = ?');
            $rows->execute([$id]);
            $rename = $pdo->prepare('UPDATE cafe_tables SET name = ? WHERE id = ?');
            $open = $pdo->prepare('UPDATE sales SET table_name = ? WHERE table_id = ? AND status = "open"');
            foreach ($rows->fetchAll() as $table) {
                $tableName = (string) $table['name'];
                if (!str_starts_with($tableName, $oldPrefix)) {
                    continue;
                }
                $next = $name . ' ' . substr($tableName, strlen($oldPrefix));
                if (strlen($next) > 40) {
                    throw new AppError('A table name would be longer than 40 characters.');
                }
                $rename->execute([$next, (int) $table['id']]);
                $open->execute([$next, (int) $table['id']]);
            }
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return $id;
}

function delete_floor_area(int $id): void
{
    $pdo = db();
    if (floor_area_row($id) === null) {
        throw new AppError('That area was not found.', 404);
    }
    $used = $pdo->prepare('SELECT COUNT(*) FROM cafe_tables WHERE area_id = ?');
    $used->execute([$id]);
    if ((int) $used->fetchColumn() > 0) {
        throw new AppError('Move the tables out of this area before deleting it.');
    }
    $pdo->prepare('DELETE FROM floor_areas WHERE id = ?')->execute([$id]);
}

function list_tables(): array
{
    $rows = db()->query(
        'SELECT t.id, t.name, t.sort_order, t.active, t.area_id, a.name AS area_name, a.sort_order AS area_sort,
                o.sale_id AS order_id
         FROM cafe_tables t
         LEFT JOIN floor_areas a ON a.id = t.area_id
         LEFT JOIN open_tables o ON o.table_id = t.id
         ORDER BY (t.area_id IS NULL), a.sort_order, a.name, t.sort_order, t.name'
    )->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'sort_order' => (int) $row['sort_order'],
            'active' => (int) $row['active'] === 1,
            'area_id' => $row['area_id'] !== null ? (int) $row['area_id'] : null,
            'area_name' => (string) ($row['area_name'] ?? ''),
            'area_sort' => $row['area_sort'] !== null ? (int) $row['area_sort'] : 100000,
            'order_id' => $row['order_id'] !== null ? (int) $row['order_id'] : null,
        ];
    }
    return $out;
}

function floor_tables(): array
{
    $staff = staff_user();
    $rows = db()->query(
        'SELECT t.id, t.name, t.area_id, a.name AS area_name, a.sort_order AS area_sort,
                o.sale_id AS order_id, s.total, s.service_started_at, s.waiter_id, s.pay_review,
                u.name AS waiter_name,
                COALESCE(SUM(l.qty), 0) AS item_count
         FROM cafe_tables t
         LEFT JOIN floor_areas a ON a.id = t.area_id
         LEFT JOIN open_tables o ON o.table_id = t.id
         LEFT JOIN sales s ON s.id = o.sale_id
         LEFT JOIN sale_lines l ON l.sale_id = s.id
         LEFT JOIN users u ON u.id = s.waiter_id
         WHERE t.active = 1 OR o.sale_id IS NOT NULL
         GROUP BY t.id, t.name, t.sort_order, t.area_id, a.name, a.sort_order, o.sale_id, s.total, s.service_started_at, s.waiter_id, s.pay_review, u.name
         ORDER BY (t.area_id IS NULL), a.sort_order, a.name, t.sort_order, t.name'
    )->fetchAll();
    $saleIds = [];
    foreach ($rows as $row) {
        if ($row['order_id'] !== null && (int) $row['item_count'] > 0) {
            $saleIds[] = (int) $row['order_id'];
        }
    }
    $linesBySale = [];
    if ($saleIds !== []) {
        $marks = implode(',', array_fill(0, count($saleIds), '?'));
        $lineStmt = db()->prepare(
            'SELECT sale_id, item_name, qty FROM sale_lines WHERE sale_id IN (' . $marks . ') ORDER BY added_at, id'
        );
        $lineStmt->execute($saleIds);
        foreach ($lineStmt->fetchAll() as $line) {
            $saleId = (int) $line['sale_id'];
            $name = (string) $line['item_name'];
            if (!isset($linesBySale[$saleId][$name])) {
                $linesBySale[$saleId][$name] = 0;
            }
            $linesBySale[$saleId][$name] += (int) $line['qty'];
        }
    }
    $tables = [];
    foreach ($rows as $row) {
        $count = (int) $row['item_count'];
        $owner = (int) ($row['waiter_id'] ?? 0);
        $mine = $staff !== null && $owner > 0 && $owner === $staff['id'];
        $locked = false;
        $orderId = $row['order_id'] !== null ? (int) $row['order_id'] : null;
        $lines = [];
        foreach ($linesBySale[$orderId] ?? [] as $name => $qty) {
            $lines[] = ['name' => $name, 'qty' => $qty];
        }
        $tables[] = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'area_id' => $row['area_id'] !== null ? (int) $row['area_id'] : null,
            'area_name' => (string) ($row['area_name'] ?? ''),
            'area_sort' => $row['area_sort'] !== null ? (int) $row['area_sort'] : 100000,
            'order_id' => $orderId,
            'total' => from_cents(to_cents((string) ($row['total'] ?? '0'))),
            'item_count' => $count,
            'lines' => $lines,
            'service_started_at' => $count > 0 ? atom_time($row['service_started_at']) : null,
            'waiter_name' => (string) ($row['waiter_name'] ?? ''),
            'payment_waiting' => (string) ($row['pay_review'] ?? '') === 'pending',
            'mine' => $mine,
            'locked' => $locked,
        ];
    }
    return [
        'currency_symbol' => settings()['currency_symbol'],
        'cafe_name' => settings()['cafe_name'],
        'tables' => $tables,
        'credit_stamp' => credit_stamp(),
        'menu_stamp' => menu_stamp(),
        'pending_payments' => (int) db()->query('SELECT COUNT(*) FROM sales WHERE pay_review = "pending" AND status IN ("open", "credit")')->fetchColumn(),
        'shift' => $staff !== null ? open_shift((int) $staff['id']) : null,
    ];
}

function save_table(?int $id, string $name, int $sortOrder, bool $active = true, ?int $areaId = null, bool $setArea = false): int
{
    $name = trim($name);
    if ($sortOrder < 0 || $sortOrder > 9999) {
        throw new AppError('Sort order must be from 0 to 9999.');
    }
    $pdo = db();
    $area = null;
    if ($setArea && $areaId !== null && $areaId > 0) {
        $area = floor_area_row($areaId);
        if ($area === null) {
            throw new AppError('Choose an area.');
        }
        $prefix = $area['name'] . ' ';
        if (!str_starts_with($name, $prefix)) {
            $name = $prefix . $name;
        }
    }
    if ($name === '' || strlen($name) > 40) {
        throw new AppError('Table name must be 1 to 40 characters.');
    }
    $storedArea = $setArea ? ($area['id'] ?? null) : null;
    if ($id === null) {
        $stmt = $pdo->prepare('INSERT INTO cafe_tables (name, sort_order, active, area_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $sortOrder, $active ? 1 : 0, $setArea ? $storedArea : null]);
        return (int) $pdo->lastInsertId();
    }
    if (!$setArea) {
        $keep = $pdo->prepare('SELECT area_id FROM cafe_tables WHERE id = ?');
        $keep->execute([$id]);
        $storedArea = $keep->fetchColumn();
        $storedArea = $storedArea !== false && $storedArea !== null ? (int) $storedArea : null;
    }
    if (!$active) {
        $open = $pdo->prepare('SELECT sale_id FROM open_tables WHERE table_id = ?');
        $open->execute([$id]);
        if ($open->fetch()) {
            throw new AppError('Close this table before hiding it.');
        }
    }
    $stmt = $pdo->prepare('UPDATE cafe_tables SET name = ?, sort_order = ?, active = ?, area_id = ? WHERE id = ?');
    $stmt->execute([$name, $sortOrder, $active ? 1 : 0, $storedArea, $id]);
    if ($stmt->rowCount() === 0) {
        $check = $pdo->prepare('SELECT id FROM cafe_tables WHERE id = ?');
        $check->execute([$id]);
        if (!$check->fetch()) {
            throw new AppError('Table not found.', 404);
        }
    }
    if ($active) {
        $pdo->prepare('UPDATE sales SET table_name = ? WHERE table_id = ? AND status = "open"')->execute([$name, $id]);
    }
    return $id;
}

function delete_table(int $id): void
{
    $pdo = db();
    $open = $pdo->prepare('SELECT sale_id FROM open_tables WHERE table_id = ?');
    $open->execute([$id]);
    if ($open->fetch()) {
        throw new AppError('Close this table before deleting it.');
    }
    $used = $pdo->prepare('SELECT id FROM sales WHERE table_id = ? LIMIT 1');
    $used->execute([$id]);
    if ($used->fetch()) {
        throw new AppError('This table has sales. Hide it instead of deleting it.');
    }
    $stmt = $pdo->prepare('DELETE FROM cafe_tables WHERE id = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) {
        throw new AppError('Table not found.', 404);
    }
}

function assign_bill_customer(int $saleId, int $customerId): array
{
    if ($customerId < 1) {
        throw new AppError('Choose a customer.');
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT id, status, customer_id, total FROM sales WHERE id = ? FOR UPDATE');
        $stmt->execute([$saleId]);
        $sale = $stmt->fetch();
        if (!$sale) {
            throw new AppError('Order not found.', 404);
        }
        if ($sale['status'] !== 'open' && $sale['status'] !== 'credit') {
            throw new AppError('That bill is already settled.');
        }
        $customer = $pdo->prepare('SELECT id FROM customers WHERE id = ?');
        $customer->execute([$customerId]);
        if (!$customer->fetch()) {
            throw new AppError('Choose a customer.');
        }
        if ($sale['status'] === 'credit' && (int) $sale['customer_id'] !== $customerId) {
            $open = customer_open_credit_cents($pdo, $customerId) + to_cents((string) $sale['total']);
            assert_credit_limit($pdo, $customerId, $open);
        }
        $pdo->prepare('UPDATE sales SET customer_id = ? WHERE id = ?')->execute([$customerId, $saleId]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return order_payload($saleId);
}

function order_payload(int $orderId): array
{
    $pdo = db();
    $sale = $pdo->prepare(
        'SELECT s.id, s.table_id, s.table_name, s.opened_at, s.service_started_at, s.closed_at,
                s.payment_method, s.total, s.status, s.waiter_id, s.customer_id, u.name AS waiter_name,
                c.name AS customer_name, c.phone AS customer_phone, c.credit_limit
         FROM sales s
         LEFT JOIN users u ON u.id = s.waiter_id
         LEFT JOIN customers c ON c.id = s.customer_id
         WHERE s.id = ?'
    );
    $sale->execute([$orderId]);
    $row = $sale->fetch();
    if (!$row) {
        throw new AppError('Order not found.', 404);
    }
    $lines = $pdo->prepare(
        'SELECT id, item_id, item_name, unit_price, qty, line_total, added_at
         FROM sale_lines WHERE sale_id = ? ORDER BY added_at, id'
    );
    $lines->execute([$orderId]);
    $outLines = [];
    foreach ($lines->fetchAll() as $line) {
        $outLines[] = [
            'id' => (int) $line['id'],
            'item_id' => $line['item_id'] !== null ? (int) $line['item_id'] : null,
            'item_name' => $line['item_name'],
            'unit_price' => from_cents(to_cents((string) $line['unit_price'])),
            'qty' => (int) $line['qty'],
            'line_total' => from_cents(to_cents((string) $line['line_total'])),
            'added_at' => (string) $line['added_at'],
        ];
    }
    return [
        'id' => (int) $row['id'],
        'table_id' => $row['table_id'] !== null ? (int) $row['table_id'] : null,
        'table_name' => $row['table_name'],
        'opened_at' => $row['opened_at'],
        'service_started_at' => atom_time($row['service_started_at']),
        'closed_at' => $row['closed_at'],
        'duration_label' => duration_label($row['service_started_at'], $row['closed_at']),
        'payment_method' => $row['payment_method'],
        'payment_label' => payment_label($row['payment_method']),
        'status' => $row['status'],
        'waiter_name' => (string) ($row['waiter_name'] ?? ''),
        'customer_id' => $row['customer_id'] !== null ? (int) $row['customer_id'] : null,
        'customer_name' => (string) ($row['customer_name'] ?? ''),
        'customer_phone' => (string) ($row['customer_phone'] ?? ''),
        'credit_limit' => $row['credit_limit'] === null ? null : from_cents(to_cents((string) $row['credit_limit'])),
        'can_edit' => bill_can_edit($row, count($outLines)),
        'total' => from_cents(to_cents((string) $row['total'])),
        'currency_symbol' => settings()['currency_symbol'],
        'cafe_name' => settings()['cafe_name'],
        'lines' => $outLines,
    ];
}

function bill_can_edit(array $sale, int $lineCount): bool
{
    $staff = staff_user();
    if ($staff === null) {
        return false;
    }
    $status = (string) ($sale['status'] ?? '');
    if ($status === 'credit') {
        return (int) ($sale['customer_id'] ?? 0) > 0;
    }
    return $status === 'open';
}

function guard_bill(PDO $pdo, array $order): void
{
    $staff = staff_user();
    if ($staff === null) {
        throw new AppError('Sign in required.', 401);
    }
    $owner = (int) ($order['waiter_id'] ?? 0);
    if ($owner === 0) {
        $pdo->prepare('UPDATE sales SET waiter_id = ? WHERE id = ? AND (waiter_id IS NULL OR waiter_id = 0)')->execute([
            $staff['id'],
            (int) $order['id'],
        ]);
    }
}

function recalc_order(PDO $pdo, int $orderId): void
{
    $stmt = $pdo->prepare('SELECT line_total FROM sale_lines WHERE sale_id = ?');
    $stmt->execute([$orderId]);
    $cents = 0;
    foreach ($stmt->fetchAll() as $line) {
        $cents += to_cents((string) $line['line_total']);
    }
    $update = $pdo->prepare('UPDATE sales SET total = ? WHERE id = ?');
    $update->execute([from_cents($cents), $orderId]);
}

function open_table(int $tableId): array
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $table = $pdo->prepare('SELECT id, name, active FROM cafe_tables WHERE id = ? FOR UPDATE');
        $table->execute([$tableId]);
        $row = $table->fetch();
        if (!$row || (int) $row['active'] !== 1) {
            throw new AppError('That table is not available.');
        }
        $existing = $pdo->prepare('SELECT sale_id FROM open_tables WHERE table_id = ?');
        $existing->execute([$tableId]);
        $open = $existing->fetch();
        if ($open) {
            $sale = $pdo->prepare('SELECT id, table_id, status, waiter_id FROM sales WHERE id = ? FOR UPDATE');
            $sale->execute([(int) $open['sale_id']]);
            $held = $sale->fetch();
            if (!$held || $held['status'] !== 'open') {
                throw new AppError('This table is already closed.', 409);
            }
            guard_bill($pdo, $held);
            $pdo->commit();
            return order_payload((int) $open['sale_id']);
        }
        $staff = staff_user();
        $now = date('Y-m-d H:i:s');
        $insert = $pdo->prepare(
            'INSERT INTO sales (table_id, table_name, opened_at, total, status, waiter_id) VALUES (?, ?, ?, 0.00, "open", ?)'
        );
        $insert->execute([(int) $row['id'], $row['name'], $now, $staff ? $staff['id'] : null]);
        $saleId = (int) $pdo->lastInsertId();
        $pdo->prepare('INSERT INTO open_tables (table_id, sale_id) VALUES (?, ?)')->execute([$tableId, $saleId]);
        $pdo->commit();
        return order_payload($saleId);
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        if ($error instanceof PDOException && $error->getCode() === '23000') {
            $again = db()->prepare('SELECT sale_id FROM open_tables WHERE table_id = ?');
            $again->execute([$tableId]);
            $open = $again->fetch();
            if ($open) {
                $payload = order_payload((int) $open['sale_id']);
                if ($payload['can_edit'] === false) {
                    throw new AppError('This table is already open.', 409);
                }
                return $payload;
            }
        }
        throw $error;
    }
}

function lock_open_order(PDO $pdo, int $orderId): array
{
    $stmt = $pdo->prepare('SELECT id, table_id, status, waiter_id FROM sales WHERE id = ? FOR UPDATE');
    $stmt->execute([$orderId]);
    $row = $stmt->fetch();
    if (!$row || !in_array($row['status'], ['open', 'credit'], true)) {
        throw new AppError('This bill is already settled.', 409);
    }
    return $row;
}

function add_order_item(int $orderId, int $itemId, int $qty): array
{
    if ($itemId <= 0 || $qty < 1 || $qty > 99) {
        throw new AppError('Choose an item and a quantity from 1 to 99.');
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $held = lock_open_order($pdo, $orderId);
        guard_bill($pdo, $held);
        $beforeOpen = open_credit_before_change($pdo, $held);
        $itemStmt = $pdo->prepare('SELECT id, name, price, active FROM items WHERE id = ?');
        $itemStmt->execute([$itemId]);
        $item = $itemStmt->fetch();
        if (!$item || (int) $item['active'] !== 1) {
            throw new AppError('That item is not available.');
        }
        $today = date('Y-m-d');
        $lineStmt = $pdo->prepare('SELECT id, qty, unit_price, added_at FROM sale_lines WHERE sale_id = ? AND item_id = ? FOR UPDATE');
        $lineStmt->execute([$orderId, $itemId]);
        $line = null;
        foreach ($lineStmt->fetchAll() as $candidate) {
            if (substr((string) $candidate['added_at'], 0, 10) === $today) {
                $line = $candidate;
                break;
            }
        }
        if ($line) {
            $next = (int) $line['qty'] + $qty;
            if ($next > 99) {
                throw new AppError('Quantity cannot exceed 99 for one item.');
            }
            $unitCents = to_cents((string) $line['unit_price']);
            $update = $pdo->prepare('UPDATE sale_lines SET qty = ?, line_total = ? WHERE id = ?');
            $update->execute([$next, from_cents($unitCents * $next), (int) $line['id']]);
        } else {
            $unitCents = to_cents((string) $item['price']);
            $insert = $pdo->prepare(
                'INSERT INTO sale_lines (sale_id, item_id, item_name, unit_price, qty, line_total, added_at) VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $insert->execute([
                $orderId,
                $itemId,
                $item['name'],
                from_cents($unitCents),
                $qty,
                from_cents($unitCents * $qty),
                date('Y-m-d H:i:s'),
            ]);
        }
        $pdo->prepare(
            'UPDATE sales SET service_started_at = ? WHERE id = ? AND service_started_at IS NULL'
        )->execute([date('Y-m-d H:i:s'), $orderId]);
        recalc_order($pdo, $orderId);
        guard_credit_increase($pdo, $orderId, $beforeOpen);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return order_payload($orderId);
}

function set_order_line_qty(int $orderId, int $lineId, int $qty): array
{
    if ($qty < 0 || $qty > 99) {
        throw new AppError('Quantity must be from 0 to 99.');
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $held = lock_open_order($pdo, $orderId);
        guard_bill($pdo, $held);
        $beforeOpen = open_credit_before_change($pdo, $held);
        $lineStmt = $pdo->prepare('SELECT id, item_id, item_name, unit_price, qty, added_at FROM sale_lines WHERE id = ? AND sale_id = ? FOR UPDATE');
        $lineStmt->execute([$lineId, $orderId]);
        $line = $lineStmt->fetch();
        if (!$line) {
            throw new AppError('That line is no longer on the bill.', 404);
        }
        $current = (int) $line['qty'];
        $addedDay = substr((string) $line['added_at'], 0, 10);
        $today = date('Y-m-d');
        if ($qty > $current && $addedDay !== '' && $addedDay !== $today) {
            $delta = $qty - $current;
            $unitCents = to_cents((string) $line['unit_price']);
            $price = from_cents($unitCents);
            if ($line['item_id'] === null) {
                $same = $pdo->prepare(
                    'SELECT id, qty FROM sale_lines WHERE sale_id = ? AND item_id IS NULL AND unit_price = ? AND DATE(added_at) = ? AND id <> ? FOR UPDATE'
                );
                $same->execute([$orderId, $price, $today, $lineId]);
            } else {
                $same = $pdo->prepare(
                    'SELECT id, qty FROM sale_lines WHERE sale_id = ? AND item_id = ? AND unit_price = ? AND DATE(added_at) = ? AND id <> ? FOR UPDATE'
                );
                $same->execute([$orderId, (int) $line['item_id'], $price, $today, $lineId]);
            }
            $todayLine = $same->fetch();
            $next = ($todayLine ? (int) $todayLine['qty'] : 0) + $delta;
            if ($next > 99) {
                throw new AppError('Quantity cannot exceed 99 for one item.');
            }
            if ($todayLine) {
                $pdo->prepare('UPDATE sale_lines SET qty = ?, line_total = ? WHERE id = ?')->execute([
                    $next,
                    from_cents($unitCents * $next),
                    (int) $todayLine['id'],
                ]);
            } else {
                $pdo->prepare(
                    'INSERT INTO sale_lines (sale_id, item_id, item_name, unit_price, qty, line_total, added_at) VALUES (?, ?, ?, ?, ?, ?, ?)'
                )->execute([
                    $orderId,
                    $line['item_id'] !== null ? (int) $line['item_id'] : null,
                    $line['item_name'],
                    $price,
                    $delta,
                    from_cents($unitCents * $delta),
                    date('Y-m-d H:i:s'),
                ]);
            }
        } elseif ($qty === 0) {
            $pdo->prepare('DELETE FROM sale_lines WHERE id = ?')->execute([$lineId]);
        } else {
            $unitCents = to_cents((string) $line['unit_price']);
            $pdo->prepare('UPDATE sale_lines SET qty = ?, line_total = ? WHERE id = ?')->execute([
                $qty,
                from_cents($unitCents * $qty),
                $lineId,
            ]);
        }
        $left = $pdo->prepare('SELECT COUNT(*) AS n FROM sale_lines WHERE sale_id = ?');
        $left->execute([$orderId]);
        if ((int) $left->fetch()['n'] === 0) {
            $pdo->prepare('UPDATE sales SET service_started_at = NULL WHERE id = ?')->execute([$orderId]);
        }
        recalc_order($pdo, $orderId);
        guard_credit_increase($pdo, $orderId, $beforeOpen);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return order_payload($orderId);
}

function uploads_root(): string
{
    $dir = dirname(__DIR__) . '/data/uploads';
    foreach ([$dir, $dir . '/slips', $dir . '/items'] as $folder) {
        if (!is_dir($folder) && !mkdir($folder, 0775, true) && !is_dir($folder)) {
            throw new AppError('Could not store the photo.', 500);
        }
    }
    return $dir;
}

function bank_image_file(): string
{
    $path = uploads_root() . '/bank.jpg';
    return is_file($path) ? $path : '';
}

function slip_file(int $saleId): string
{
    if ($saleId < 1) {
        return '';
    }
    $path = uploads_root() . '/slips/' . $saleId . '.jpg';
    if (is_file($path)) {
        return $path;
    }
    $stmt = db()->prepare('SELECT slip_image FROM sales WHERE id = ?');
    $stmt->execute([$saleId]);
    $name = basename((string) $stmt->fetchColumn());
    if (!preg_match('/^s\d+\.jpg$/', $name)) {
        return '';
    }
    $shared = uploads_root() . '/slips/' . $name;
    return is_file($shared) ? $shared : '';
}

function item_photo_file(int $itemId): string
{
    if ($itemId < 1) {
        return '';
    }
    $path = uploads_root() . '/items/' . $itemId . '.jpg';
    return is_file($path) ? $path : '';
}

function item_photo_token(int $itemId, string $photo): string
{
    if ($photo === '') {
        return '';
    }
    $path = item_photo_file($itemId);
    if ($path === '') {
        return '';
    }
    $mtime = filemtime($path);
    return $mtime === false ? '' : (string) $mtime;
}

function save_item_photo(int $itemId, array $file): void
{
    $check = db()->prepare('SELECT id FROM items WHERE id = ?');
    $check->execute([$itemId]);
    if (!$check->fetch()) {
        throw new AppError('Item not found.', 404);
    }
    store_jpeg_upload($file, uploads_root() . '/items/' . $itemId . '.jpg');
    db()->prepare('UPDATE items SET photo = ? WHERE id = ?')->execute([$itemId . '.jpg', $itemId]);
}

function clear_item_photo(int $itemId): void
{
    $path = uploads_root() . '/items/' . $itemId . '.jpg';
    if (is_file($path)) {
        unlink($path);
    }
    db()->prepare('UPDATE items SET photo = "" WHERE id = ?')->execute([$itemId]);
}

function send_stored_file(string $path, string $mime): void
{
    if (!is_file($path)) {
        throw new AppError('Not found.', 404);
    }
    header('Content-Type: ' . $mime);
    header('X-Content-Type-Options: nosniff');
    header('Content-Disposition: inline; filename="' . basename($path) . '"');
    header('Cache-Control: private, no-store');
    header('Content-Length: ' . (string) filesize($path));
    readfile($path);
    exit;
}

function send_jpeg(string $path, bool $cacheable = false): void
{
    if (!is_file($path)) {
        throw new AppError('Not found.', 404);
    }
    header('Content-Type: image/jpeg');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: ' . ($cacheable ? 'private, max-age=86400' : 'private, no-store'));
    header('Content-Length: ' . (string) filesize($path));
    readfile($path);
    exit;
}

function store_jpeg_upload(array $file, string $dest): void
{
    $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error !== UPLOAD_ERR_OK) {
        throw new AppError('The photo did not upload. Try again.');
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    $size = (int) ($file['size'] ?? 0);
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        throw new AppError('The photo did not upload. Try again.');
    }
    if ($size < 1 || $size > 8 * 1024 * 1024) {
        throw new AppError('Use a photo under 8 MB.');
    }
    $info = @getimagesize($tmp);
    $mime = is_array($info) ? (string) ($info['mime'] ?? '') : '';
    if (!is_array($info) || !in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
        throw new AppError('Use a JPG, PNG, or WebP photo.');
    }
    if ((int) $info[0] < 1 || (int) $info[1] < 1 || (int) $info[0] * (int) $info[1] > 16000000) {
        throw new AppError('That photo is too large.');
    }
    if (!function_exists('imagecreatefromstring') || !function_exists('imagejpeg')) {
        throw new AppError('The server cannot save photos.', 500);
    }
    $raw = file_get_contents($tmp);
    $image = $raw === false ? false : @imagecreatefromstring($raw);
    if ($image === false) {
        throw new AppError('Use a JPG, PNG, or WebP photo.');
    }
    if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
        $exif = @exif_read_data($tmp);
        $orientation = is_array($exif) ? (int) ($exif['Orientation'] ?? 1) : 1;
        $angle = $orientation === 3 ? 180 : ($orientation === 6 ? -90 : ($orientation === 8 ? 90 : 0));
        if ($angle !== 0) {
            $turned = imagerotate($image, $angle, 0);
            if ($turned !== false) {
                imagedestroy($image);
                $image = $turned;
            }
        }
    }
    $width = imagesx($image);
    $height = imagesy($image);
    $max = 1600;
    $scale = min(1, $max / max($width, $height));
    $newW = max(1, (int) round($width * $scale));
    $newH = max(1, (int) round($height * $scale));
    $flat = imagecreatetruecolor($newW, $newH);
    $white = imagecolorallocate($flat, 255, 255, 255);
    imagefilledrectangle($flat, 0, 0, $newW, $newH, $white);
    imagecopyresampled($flat, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
    imagedestroy($image);
    $dir = dirname($dest);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        imagedestroy($flat);
        throw new AppError('Could not store the photo.', 500);
    }
    $saved = imagejpeg($flat, $dest, 82);
    imagedestroy($flat);
    if (!$saved) {
        throw new AppError('Could not store the photo.', 500);
    }
}

function save_bank_image(array $file): void
{
    store_jpeg_upload($file, uploads_root() . '/bank.jpg');
    db()->exec("UPDATE settings SET bank_image = 'bank.jpg' WHERE id = 1");
}

function like_term(string $value): string
{
    $value = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    return '%' . $value . '%';
}

function search_customers(string $query): array
{
    $query = trim($query);
    $digits = preg_replace('/\D+/', '', $query) ?? '';
    $sql = 'SELECT c.id, c.name, c.phone, c.credit_limit,
            (SELECT COUNT(*) FROM sales s WHERE s.customer_id = c.id AND s.status = "credit") AS open_bills,
            (SELECT COALESCE(SUM(s.total), 0) FROM sales s WHERE s.customer_id = c.id AND s.status = "credit") AS open_total
            FROM customers c';
    $params = [];
    if ($query !== '') {
        $sql .= " WHERE c.name LIKE ? ESCAPE '\\\\' OR c.phone LIKE ? ESCAPE '\\\\'";
        $params[] = like_term($query);
        $params[] = like_term($digits !== '' ? $digits : $query);
    }
    $sql .= ' ORDER BY open_bills DESC, c.name LIMIT 40';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $out = [];
    foreach ($stmt->fetchAll() as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'phone' => $row['phone'],
            'open_bills' => (int) $row['open_bills'],
            'open_total' => from_cents(to_cents((string) $row['open_total'])),
            'credit_limit' => $row['credit_limit'] === null ? null : from_cents(to_cents((string) $row['credit_limit'])),
        ];
    }
    return $out;
}

function customer_row(PDO $pdo, int $id): array
{
    $stmt = $pdo->prepare('SELECT id, name, phone, credit_limit FROM customers WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        throw new AppError('Choose a customer.');
    }
    return $row;
}

function customer_open_credit_cents(PDO $pdo, int $customerId): int
{
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(total), 0) AS total FROM sales WHERE customer_id = ? AND status = "credit"');
    $stmt->execute([$customerId]);
    return to_cents((string) $stmt->fetch()['total']);
}

function assert_credit_limit(PDO $pdo, int $customerId, int $openCents): void
{
    $customer = customer_row($pdo, $customerId);
    if ($customer['credit_limit'] === null || $customer['credit_limit'] === '') {
        return;
    }
    $limit = to_cents((string) $customer['credit_limit']);
    if ($openCents <= $limit) {
        return;
    }
    throw new AppError(
        'Credit limit is ' . money_label(from_cents($limit)) . '. This would leave ' . money_label(from_cents($openCents)) . ' still open.'
    );
}

function open_credit_before_change(PDO $pdo, array $held): int
{
    if (($held['status'] ?? '') !== 'credit') {
        return 0;
    }
    $stmt = $pdo->prepare('SELECT customer_id FROM sales WHERE id = ?');
    $stmt->execute([(int) $held['id']]);
    $customerId = (int) $stmt->fetchColumn();
    if ($customerId <= 0) {
        return 0;
    }
    return customer_open_credit_cents($pdo, $customerId);
}

function guard_credit_increase(PDO $pdo, int $orderId, int $beforeOpenCents): void
{
    $stmt = $pdo->prepare('SELECT status, customer_id FROM sales WHERE id = ?');
    $stmt->execute([$orderId]);
    $sale = $stmt->fetch();
    if (!$sale || $sale['status'] !== 'credit' || $sale['customer_id'] === null) {
        return;
    }
    $after = customer_open_credit_cents($pdo, (int) $sale['customer_id']);
    if ($after <= $beforeOpenCents) {
        return;
    }
    assert_credit_limit($pdo, (int) $sale['customer_id'], $after);
}

function assert_sale_editor(): void
{
    if ((string) ($_SESSION['role'] ?? '') !== 'superadmin') {
        throw new AppError('Only a superadmin can change a sale.', 403);
    }
}

function sale_book_date(string $date): string
{
    $date = parse_date($date);
    $max = (new DateTime('today'))->modify('+2 years')->format('Y-m-d');
    if ($date < '2020-01-01' || $date > $max) {
        throw new AppError('Choose a day from 2020 up to two years ahead.');
    }
    return $date;
}

function sale_book_stamp(string $date): string
{
    return $date === date('Y-m-d') ? date('Y-m-d H:i:s') : $date . ' 12:00:00';
}

function sale_lines_from_menu(PDO $pdo, array $itemIds, array $qtys): array
{
    $itemStmt = $pdo->prepare('SELECT id, name, price FROM items WHERE id = ?');
    $lines = [];
    $total = 0;
    $count = max(count($itemIds), count($qtys));
    for ($index = 0; $index < $count; $index++) {
        $itemId = (int) ($itemIds[$index] ?? 0);
        $qty = (int) ($qtys[$index] ?? 0);
        if ($itemId <= 0 && $qty <= 0) {
            continue;
        }
        if ($itemId <= 0 || $qty < 1 || $qty > 99) {
            throw new AppError('Each line needs an item and a quantity from 1 to 99.');
        }
        $itemStmt->execute([$itemId]);
        $item = $itemStmt->fetch();
        if (!$item) {
            throw new AppError('Choose an item from the menu.');
        }
        $unit = to_cents((string) $item['price']);
        $line = $unit * $qty;
        $total += $line;
        $lines[] = [
            'item_id' => (int) $item['id'],
            'item_name' => (string) $item['name'],
            'unit_price' => from_cents($unit),
            'qty' => $qty,
            'line_total' => from_cents($line),
        ];
    }
    if ($lines === []) {
        throw new AppError('Add at least one item.');
    }
    return ['lines' => $lines, 'total' => $total];
}

function sale_table_choice(PDO $pdo, int $tableId): array
{
    $tableStmt = $pdo->prepare('SELECT id, name FROM cafe_tables WHERE id = ?');
    $tableStmt->execute([$tableId]);
    $table = $tableStmt->fetch();
    if (!$table) {
        throw new AppError('Choose a table.');
    }
    return $table;
}

function sale_customer_choice(PDO $pdo, string $payment, int $customerId): int
{
    if ($payment === 'credit') {
        if ($customerId <= 0) {
            throw new AppError('Choose a customer for credit.');
        }
        customer_row($pdo, $customerId);
        return $customerId;
    }
    if ($customerId > 0) {
        customer_row($pdo, $customerId);
        return $customerId;
    }
    return 0;
}

function sale_row_for_change(PDO $pdo, int $id): array
{
    assert_sale_editor();
    $stmt = $pdo->prepare('SELECT * FROM sales WHERE id = ?');
    $stmt->execute([$id]);
    $sale = $stmt->fetch();
    if (!$sale) {
        throw new AppError('That bill was not found.', 404);
    }
    if ($sale['status'] === 'open') {
        throw new AppError('This bill is still open on a table.');
    }
    $settled = $pdo->prepare('SELECT id FROM settlement_bills WHERE sale_id = ?');
    $settled->execute([$id]);
    if ($settled->fetch()) {
        throw new AppError('This bill is part of a settlement, so it stays as it was recorded.');
    }
    return $sale;
}

function record_manual_sale(string $date, int $tableId, string $payment, int $customerId, array $itemIds, array $qtys): array
{
    assert_sale_editor();
    $date = sale_book_date($date);
    if ($payment !== 'cash' && $payment !== 'online' && $payment !== 'credit') {
        throw new AppError('Choose cash, bank, or credit.');
    }
    $pdo = db();
    $table = sale_table_choice($pdo, $tableId);
    $built = sale_lines_from_menu($pdo, $itemIds, $qtys);
    $lines = $built['lines'];
    $total = $built['total'];
    $customerId = sale_customer_choice($pdo, $payment, $customerId);
    if ($payment === 'credit') {
        assert_credit_limit($pdo, $customerId, customer_open_credit_cents($pdo, $customerId) + $total);
    }
    $stamp = sale_book_stamp($date);
    $waiterId = (int) ($_SESSION['user_id'] ?? 0);
    $pdo->beginTransaction();
    try {
        $insert = $pdo->prepare(
            'INSERT INTO sales (table_id, table_name, opened_at, service_started_at, closed_at, payment_method, waiter_id, customer_id, held, total, status)
             VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?)'
        );
        $insert->execute([
            (int) $table['id'],
            (string) $table['name'],
            $stamp,
            $payment === 'credit' ? null : $stamp,
            $payment,
            $waiterId > 0 ? $waiterId : null,
            $customerId > 0 ? $customerId : null,
            $payment === 'credit' ? 1 : 0,
            from_cents($total),
            $payment === 'credit' ? 'credit' : 'paid',
        ]);
        $saleId = (int) $pdo->lastInsertId();
        $lineInsert = $pdo->prepare(
            'INSERT INTO sale_lines (sale_id, item_id, item_name, unit_price, qty, line_total, added_at) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        foreach ($lines as $line) {
            $lineInsert->execute([$saleId, $line['item_id'], $line['item_name'], $line['unit_price'], $line['qty'], $line['line_total'], $stamp]);
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return ['id' => $saleId, 'date' => $date];
}

function update_recorded_sale(int $id, string $date, int $tableId, string $payment, int $customerId, array $itemIds, array $qtys): array
{
    $date = sale_book_date($date);
    if ($payment !== 'cash' && $payment !== 'online' && $payment !== 'credit') {
        throw new AppError('Choose cash, bank, or credit.');
    }
    $pdo = db();
    $sale = sale_row_for_change($pdo, $id);
    $table = sale_table_choice($pdo, $tableId);
    $built = sale_lines_from_menu($pdo, $itemIds, $qtys);
    $lines = $built['lines'];
    $total = $built['total'];
    $customerId = sale_customer_choice($pdo, $payment, $customerId);
    if ($payment === 'credit') {
        $open = customer_open_credit_cents($pdo, $customerId);
        if ($sale['status'] === 'credit' && (int) $sale['customer_id'] === $customerId) {
            $open -= to_cents((string) $sale['total']);
            if ($open < 0) {
                $open = 0;
            }
        }
        assert_credit_limit($pdo, $customerId, $open + $total);
    }
    $listed = $sale['status'] === 'credit'
        ? substr((string) $sale['opened_at'], 0, 10)
        : substr((string) ($sale['closed_at'] ?: $sale['opened_at']), 0, 10);
    if ($date === $listed && $payment !== 'credit' && $sale['closed_at']) {
        $opened = (string) $sale['opened_at'];
        $closed = (string) $sale['closed_at'];
        $lineStamp = $closed;
    } elseif ($date === $listed && $payment === 'credit') {
        $opened = (string) $sale['opened_at'];
        $closed = null;
        $lineStamp = $opened;
    } else {
        $opened = sale_book_stamp($date);
        $closed = $payment === 'credit' ? null : $opened;
        $lineStamp = $opened;
    }
    $kept = [];
    if ($date === $listed) {
        $old = $pdo->prepare('SELECT item_id, added_at FROM sale_lines WHERE sale_id = ? ORDER BY added_at, id');
        $old->execute([$id]);
        foreach ($old->fetchAll() as $row) {
            $kept[(int) $row['item_id']][] = (string) $row['added_at'];
        }
    }
    $held = ($payment === 'credit' || $sale['status'] === 'credit' || (int) $sale['held'] === 1) ? 1 : 0;
    $pdo->beginTransaction();
    try {
        $pdo->prepare(
            'UPDATE sales
             SET table_id = ?, table_name = ?, opened_at = ?, closed_at = ?, payment_method = ?, customer_id = ?, held = ?, total = ?, status = ?
             WHERE id = ?'
        )->execute([
            (int) $table['id'],
            (string) $table['name'],
            $opened,
            $closed,
            $payment,
            $customerId > 0 ? $customerId : null,
            $held,
            from_cents($total),
            $payment === 'credit' ? 'credit' : 'paid',
            $id,
        ]);
        $pdo->prepare('DELETE FROM sale_lines WHERE sale_id = ?')->execute([$id]);
        $lineInsert = $pdo->prepare(
            'INSERT INTO sale_lines (sale_id, item_id, item_name, unit_price, qty, line_total, added_at) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        foreach ($lines as $line) {
            $added = $lineStamp;
            $itemKey = (int) $line['item_id'];
            if ($date === $listed && !empty($kept[$itemKey])) {
                $added = array_shift($kept[$itemKey]);
            }
            $lineInsert->execute([$id, $line['item_id'], $line['item_name'], $line['unit_price'], $line['qty'], $line['line_total'], $added]);
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return ['id' => $id, 'date' => $date];
}

function delete_recorded_sale(int $id): string
{
    $pdo = db();
    $sale = sale_row_for_change($pdo, $id);
    $day = $sale['status'] === 'credit'
        ? substr((string) $sale['opened_at'], 0, 10)
        : substr((string) ($sale['closed_at'] ?: $sale['opened_at']), 0, 10);
    $pdo->beginTransaction();
    try {
        $pdo->prepare('DELETE FROM open_tables WHERE sale_id = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM sale_lines WHERE sale_id = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM sales WHERE id = ?')->execute([$id]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return $day !== '' ? $day : date('Y-m-d');
}

function list_customers(): array
{
    $rows = db()->query(
        'SELECT c.id, c.name, c.phone, c.credit_limit, c.created_at,
                (SELECT COUNT(*) FROM sales s WHERE s.customer_id = c.id AND s.status = "credit") AS open_bills,
                (SELECT COALESCE(SUM(s.total), 0) FROM sales s WHERE s.customer_id = c.id AND s.status = "credit") AS open_total,
                (SELECT COUNT(*) FROM sales s WHERE s.customer_id = c.id AND s.pay_review = "pending" AND s.status IN ("open", "credit")) AS pending_bills
         FROM customers c
         ORDER BY c.name, c.phone'
    )->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $open = to_cents((string) $row['open_total']);
        $limit = $row['credit_limit'] === null ? null : to_cents((string) $row['credit_limit']);
        $out[] = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'phone' => $row['phone'],
            'created_at' => $row['created_at'],
            'open_bills' => (int) $row['open_bills'],
            'pending_bills' => (int) ($row['pending_bills'] ?? 0),
            'open_total' => from_cents($open),
            'credit_limit' => $limit === null ? null : from_cents($limit),
            'left' => $limit === null ? null : from_cents($limit - $open),
        ];
    }
    return $out;
}

function save_credit_limit(int $id, string $limit): void
{
    $pdo = db();
    customer_row($pdo, $id);
    $limit = trim($limit);
    if ($limit === '') {
        $pdo->prepare('UPDATE customers SET credit_limit = NULL WHERE id = ?')->execute([$id]);
        return;
    }
    $cents = to_cents($limit);
    if ($cents > 9999999999) {
        throw new AppError('Credit limit is too large.');
    }
    $pdo->prepare('UPDATE customers SET credit_limit = ? WHERE id = ?')->execute([from_cents($cents), $id]);
}

function new_customer_credit_limit(): ?string
{
    $raw = settings()['default_credit_limit'] ?? null;
    if ($raw === null || $raw === '') {
        return null;
    }
    return from_cents(to_cents((string) $raw));
}

function save_customer(string $name, string $phone): int
{
    $name = trim($name);
    $phone = preg_replace('/\D+/', '', $phone) ?? '';
    if ($name === '' || strlen($name) > 80 || strlen($phone) < 5 || strlen($phone) > 15) {
        throw new AppError('Enter the customer name and phone.');
    }
    $pdo = db();
    $existing = $pdo->prepare('SELECT id FROM customers WHERE phone = ?');
    $existing->execute([$phone]);
    $row = $existing->fetch();
    if ($row) {
        $pdo->prepare('UPDATE customers SET name = ? WHERE id = ?')->execute([$name, (int) $row['id']]);
        return (int) $row['id'];
    }
    $limit = new_customer_credit_limit();
    $pdo->prepare('INSERT INTO customers (name, phone, credit_limit, created_at) VALUES (?, ?, ?, ?)')->execute([$name, $phone, $limit, date('Y-m-d H:i:s')]);
    return (int) $pdo->lastInsertId();
}

function rename_customer(int $id, string $name): void
{
    $name = trim($name);
    if ($id < 1 || $name === '' || strlen($name) > 80) {
        throw new AppError('Enter the customer name.');
    }
    $pdo = db();
    customer_row($pdo, $id);
    $pdo->prepare('UPDATE customers SET name = ? WHERE id = ?')->execute([$name, $id]);
}

function update_customer(int $id, string $name, string $phone): void
{
    $name = trim($name);
    $phone = preg_replace('/\D+/', '', $phone) ?? '';
    if ($id < 1 || $name === '' || strlen($name) > 80 || strlen($phone) < 5 || strlen($phone) > 15) {
        throw new AppError('Enter the customer name and phone.');
    }
    $pdo = db();
    customer_row($pdo, $id);
    $taken = $pdo->prepare('SELECT id FROM customers WHERE phone = ? AND id <> ?');
    $taken->execute([$phone, $id]);
    if ($taken->fetch()) {
        throw new AppError('That phone already belongs to another customer.');
    }
    $pdo->prepare('UPDATE customers SET name = ?, phone = ? WHERE id = ?')->execute([$name, $phone, $id]);
}

function credit_sales(): array
{
    $rows = db()->query(
        'SELECT s.id, s.table_name, s.total, s.opened_at, c.id AS customer_id, c.name AS customer_name, c.phone AS customer_phone,
                c.credit_limit,
                (SELECT COUNT(*) FROM sale_lines l WHERE l.sale_id = s.id) AS item_count
         FROM sales s
         JOIN customers c ON c.id = s.customer_id
         WHERE s.status = "credit"
         ORDER BY c.name, s.id'
    )->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'customer_id' => (int) $row['customer_id'],
            'table_name' => $row['table_name'],
            'opened_at' => $row['opened_at'],
            'item_count' => (int) $row['item_count'],
            'total' => from_cents(to_cents((string) $row['total'])),
            'customer_name' => $row['customer_name'],
            'customer_phone' => $row['customer_phone'],
            'credit_limit' => $row['credit_limit'] === null ? null : from_cents(to_cents((string) $row['credit_limit'])),
        ];
    }
    return $out;
}

function close_order(int $orderId, string $payment, ?array $slip = null, array $customer = []): array
{
    if ($payment !== 'cash' && $payment !== 'online' && $payment !== 'credit') {
        throw new AppError('Choose cash, bank, or credit.');
    }
    $slipPath = uploads_root() . '/slips/' . $orderId . '.jpg';
    $wroteSlip = false;
    if ($payment === 'online' && $slip !== null) {
        store_jpeg_upload($slip, $slipPath);
        $wroteSlip = true;
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $order = lock_open_order($pdo, $orderId);
        guard_bill($pdo, $order);
        $count = $pdo->prepare('SELECT COUNT(*) AS n FROM sale_lines WHERE sale_id = ?');
        $count->execute([$orderId]);
        if ((int) $count->fetch()['n'] === 0) {
            throw new AppError('Add items before taking payment, or cancel the empty table.');
        }
        if ($payment === 'credit') {
            if ($order['status'] === 'credit') {
                throw new AppError('This bill is already on credit. Add items, or take cash or bank.');
            }
            $customerId = (int) ($customer['id'] ?? 0);
            if ($customerId > 0) {
                customer_row($pdo, $customerId);
            } else {
                $customerId = save_customer((string) ($customer['name'] ?? ''), (string) ($customer['phone'] ?? ''));
            }
            $saleTotal = $pdo->prepare('SELECT total FROM sales WHERE id = ?');
            $saleTotal->execute([$orderId]);
            $nextOpen = customer_open_credit_cents($pdo, $customerId) + to_cents((string) $saleTotal->fetchColumn());
            assert_credit_limit($pdo, $customerId, $nextOpen);
            $pdo->prepare(
                'UPDATE sales SET status = "credit", payment_method = "credit", customer_id = ?, held = 1, closed_at = NULL, slip_image = "" WHERE id = ?'
            )->execute([$customerId, $orderId]);
        } else {
            $pdo->prepare(
                'UPDATE sales SET status = "paid", payment_method = ?, closed_at = ?, slip_image = ? WHERE id = ?'
            )->execute([$payment, date('Y-m-d H:i:s'), $wroteSlip ? $orderId . '.jpg' : '', $orderId]);
        }
        if ($order['table_id'] !== null) {
            $pdo->prepare('DELETE FROM open_tables WHERE table_id = ? AND sale_id = ?')->execute([
                (int) $order['table_id'],
                $orderId,
            ]);
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        if ($wroteSlip && is_file($slipPath)) {
            unlink($slipPath);
        }
        throw $error;
    }
    return order_payload($orderId);
}

function cancel_order(int $orderId): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $order = lock_open_order($pdo, $orderId);
        guard_bill($pdo, $order);
        $count = $pdo->prepare('SELECT COUNT(*) AS n FROM sale_lines WHERE sale_id = ?');
        $count->execute([$orderId]);
        if ((int) $count->fetch()['n'] > 0) {
            throw new AppError('Remove the items or take payment. A bill with items cannot be cancelled.');
        }
        if ($order['table_id'] !== null) {
            $pdo->prepare('DELETE FROM open_tables WHERE table_id = ?')->execute([(int) $order['table_id']]);
        }
        $pdo->prepare('DELETE FROM sales WHERE id = ? AND status = "open"')->execute([$orderId]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
}

function customer_credit_bills(int $customerId): array
{
    $pdo = db();
    $customer = customer_row($pdo, $customerId);
    $stmt = $pdo->prepare(
        'SELECT s.id, s.table_name, s.opened_at, s.total,
                (SELECT COUNT(*) FROM sale_lines l WHERE l.sale_id = s.id) AS item_count
         FROM sales s
         WHERE s.customer_id = ? AND s.status = "credit"
         ORDER BY s.opened_at, s.id'
    );
    $stmt->execute([$customerId]);
    $bills = [];
    $open = 0;
    foreach ($stmt as $row) {
        $cents = to_cents((string) $row['total']);
        $open += $cents;
        $bills[] = [
            'id' => (int) $row['id'],
            'table_name' => $row['table_name'],
            'opened_at' => $row['opened_at'],
            'item_count' => (int) $row['item_count'],
            'total' => from_cents($cents),
        ];
    }
    $limit = $customer['credit_limit'] === null || $customer['credit_limit'] === ''
        ? null
        : from_cents(to_cents((string) $customer['credit_limit']));
    return [
        'customer' => [
            'id' => (int) $customer['id'],
            'name' => $customer['name'],
            'phone' => $customer['phone'],
            'credit_limit' => $limit,
            'open_total' => from_cents($open),
        ],
        'bills' => $bills,
    ];
}

function settle_bills(array $saleIds, int $customerId, string $payment, ?array $slip = null): array
{
    if ($payment !== 'cash' && $payment !== 'online') {
        throw new AppError('Choose cash or bank.');
    }
    $ids = [];
    foreach ($saleIds as $saleId) {
        $id = (int) $saleId;
        if ($id < 1) {
            throw new AppError('Choose at least one bill.');
        }
        if (isset($ids[$id])) {
            throw new AppError('Each bill can be settled once.');
        }
        $ids[$id] = $id;
    }
    if ($ids === []) {
        throw new AppError('Choose at least one bill.');
    }
    if (count($ids) > 30) {
        throw new AppError('Settle up to 30 bills at once.');
    }
    ksort($ids);
    $pdo = db();
    $slipName = '';
    $slipPath = '';
    $pdo->beginTransaction();
    try {
        $locked = [];
        $lock = $pdo->prepare(
            'SELECT id, table_id, table_name, status, waiter_id, customer_id, total, held
             FROM sales WHERE id = ? FOR UPDATE'
        );
        $lineSum = $pdo->prepare('SELECT COALESCE(SUM(line_total), 0) FROM sale_lines WHERE sale_id = ?');
        $lineCount = $pdo->prepare('SELECT COUNT(*) FROM sale_lines WHERE sale_id = ?');
        foreach ($ids as $id) {
            $lock->execute([$id]);
            $sale = $lock->fetch();
            if (!$sale || !in_array($sale['status'], ['open', 'credit'], true)) {
                throw new AppError('This bill is already settled.', 409);
            }
            $lineCount->execute([$id]);
            if ((int) $lineCount->fetchColumn() < 1) {
                throw new AppError('Add items before taking payment, or cancel the empty table.');
            }
            $lineSum->execute([$id]);
            $cents = to_cents((string) $lineSum->fetchColumn());
            if ($cents !== to_cents((string) $sale['total']) || $cents < 1) {
                throw new AppError('Bill totals changed. Open the bill again.');
            }
            if ($sale['status'] === 'credit') {
                if ($customerId < 1 || (int) $sale['customer_id'] !== $customerId) {
                    throw new AppError('That bill is not on this customer\'s credit.');
                }
            } else {
                guard_bill($pdo, $sale);
            }
            $sale['cents'] = $cents;
            $locked[] = $sale;
        }
        $needsCustomer = false;
        foreach ($locked as $sale) {
            if ($sale['status'] === 'credit') {
                $needsCustomer = true;
            }
        }
        if ($needsCustomer && $customerId < 1) {
            throw new AppError('Choose the customer.');
        }
        if ($customerId > 0) {
            customer_row($pdo, $customerId);
        }
        $totalCents = 0;
        foreach ($locked as $sale) {
            $totalCents += $sale['cents'];
        }
        $now = date('Y-m-d H:i:s');
        $staff = staff_user();
        $pdo->prepare(
            'INSERT INTO settlements (reference, customer_id, total_amount, payment_method, payment_slip, settled_by, settled_at, created_at)
             VALUES (?, ?, ?, ?, "", ?, ?, ?)'
        )->execute([
            'tmp-' . bin2hex(random_bytes(4)),
            $customerId > 0 ? $customerId : null,
            from_cents($totalCents),
            $payment,
            $staff ? $staff['id'] : null,
            $now,
            $now,
        ]);
        $settlementId = (int) $pdo->lastInsertId();
        $reference = 'S' . str_pad((string) $settlementId, 5, '0', STR_PAD_LEFT);
        if ($payment === 'online' && $slip !== null) {
            $slipName = 's' . $settlementId . '.jpg';
            $slipPath = uploads_root() . '/slips/' . $slipName;
            store_jpeg_upload($slip, $slipPath);
        }
        $pdo->prepare('UPDATE settlements SET reference = ?, payment_slip = ? WHERE id = ?')
            ->execute([$reference, $slipName, $settlementId]);
        $link = $pdo->prepare('INSERT INTO settlement_bills (settlement_id, sale_id, amount_applied) VALUES (?, ?, ?)');
        $free = $pdo->prepare('DELETE FROM open_tables WHERE sale_id = ?');
        $bills = [];
        foreach ($locked as $sale) {
            $link->execute([$settlementId, (int) $sale['id'], from_cents($sale['cents'])]);
            $nextCustomer = $sale['customer_id'] !== null && $sale['customer_id'] !== '' ? (int) $sale['customer_id'] : null;
            if ($sale['status'] === 'open' && $customerId > 0) {
                $nextCustomer = $customerId;
            }
            $customerSql = $nextCustomer === null ? 'NULL' : (string) $nextCustomer;
            $markSql = 'UPDATE sales SET status = "paid", payment_method = ?, closed_at = ?, customer_id = ' . $customerSql
                . ($slipName !== '' ? ', slip_image = ?' : '')
                . ' WHERE id = ? AND status IN ("open", "credit")';
            $markParams = [$payment, $now];
            if ($slipName !== '') {
                $markParams[] = $slipName;
            }
            $markParams[] = (int) $sale['id'];
            $mark = $pdo->prepare($markSql);
            $mark->execute($markParams);
            if ($mark->rowCount() !== 1) {
                throw new AppError('This bill is already settled.', 409);
            }
            if ($sale['table_id'] !== null && $sale['status'] === 'open') {
                $free->execute([(int) $sale['id']]);
            }
            $bills[] = [
                'id' => (int) $sale['id'],
                'table_name' => $sale['table_name'],
                'total' => from_cents($sale['cents']),
            ];
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        if ($slipPath !== '' && is_file($slipPath)) {
            unlink($slipPath);
        }
        throw $error;
    }

    $lines = [];
    $openedAt = '';
    $lineStmt = db()->prepare('SELECT item_name, qty, unit_price, line_total, added_at FROM sale_lines WHERE sale_id = ? ORDER BY added_at, id');
    $openStmt = db()->prepare('SELECT opened_at FROM sales WHERE id = ?');
    foreach ($bills as $bill) {
        $openStmt->execute([$bill['id']]);
        $opened = (string) $openStmt->fetchColumn();
        if ($openedAt === '' || ($opened !== '' && $opened < $openedAt)) {
            $openedAt = $opened;
        }
        $lineStmt->execute([$bill['id']]);
        foreach ($lineStmt as $line) {
            $lines[] = [
                'item_name' => $line['item_name'],
                'qty' => (int) $line['qty'],
                'unit_price' => from_cents(to_cents((string) $line['unit_price'])),
                'line_total' => from_cents(to_cents((string) $line['line_total'])),
                'added_at' => (string) ($line['added_at'] ?? ''),
            ];
        }
    }
    $who = ['name' => '', 'phone' => ''];
    if ($customerId > 0) {
        $customer = customer_row(db(), $customerId);
        $who['name'] = $customer['name'];
        $who['phone'] = $customer['phone'];
    }
    $staff = staff_user();
    return [
        'id' => (int) $bills[0]['id'],
        'reference' => $reference,
        'table_name' => count($bills) === 1 ? $bills[0]['table_name'] : 'Bills',
        'opened_at' => $openedAt,
        'closed_at' => $now,
        'payment_method' => $payment,
        'payment_label' => payment_label($payment),
        'waiter_name' => $staff['name'] ?? '',
        'customer_name' => $who['name'],
        'customer_phone' => $who['phone'],
        'total' => from_cents($totalCents),
        'currency_symbol' => settings()['currency_symbol'],
        'cafe_name' => settings()['cafe_name'],
        'lines' => $lines,
        'bills' => $bills,
    ];
}

function customer_phone(string $phone): string
{
    $phone = preg_replace('/\D+/', '', $phone) ?? '';
    if (strlen($phone) < 5 || strlen($phone) > 15) {
        throw new AppError('Enter the phone number.');
    }
    return $phone;
}

function customer_password(string $password): string
{
    $password = (string) $password;
    if (strlen($password) < 4 || strlen($password) > 80) {
        throw new AppError('Use a password of 4 to 80 characters.');
    }
    return $password;
}

function issue_customer_token(int $customerId): string
{
    $pdo = db();
    $pdo->prepare('DELETE FROM tokens WHERE expires_at <= ?')->execute([date('Y-m-d H:i:s')]);
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 30 * 24 * 3600);
    $pdo->prepare('INSERT INTO tokens (token, expires_at, user_id, customer_id) VALUES (?, ?, NULL, ?)')->execute([
        $token,
        $expires,
        $customerId,
    ]);
    return $token;
}

function customer_user(): ?array
{
    $token = bearer_token();
    if ($token === null) {
        return null;
    }
    $stmt = db()->prepare(
        'SELECT c.id, c.name, c.phone FROM tokens t
         JOIN customers c ON c.id = t.customer_id
         WHERE t.token = ? AND t.expires_at > ? AND t.customer_id IS NOT NULL'
    );
    $stmt->execute([$token, date('Y-m-d H:i:s')]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    return [
        'id' => (int) $row['id'],
        'name' => (string) $row['name'],
        'phone' => (string) $row['phone'],
    ];
}

function require_customer(): array
{
    require_shop_floor();
    $customer = customer_user();
    if ($customer === null) {
        throw new AppError('Sign in required.', 401);
    }
    return $customer;
}

function register_customer(string $name, string $phone, string $password): array
{
    require_shop_floor();
    $name = trim($name);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Enter your name.');
    }
    $phone = customer_phone($phone);
    $password = customer_password($password);
    $pdo = db();
    $existing = $pdo->prepare('SELECT id, password_hash FROM customers WHERE phone = ?');
    $existing->execute([$phone]);
    $row = $existing->fetch();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($row) {
        if (trim((string) ($row['password_hash'] ?? '')) !== '') {
            throw new AppError('That phone already has an account. Sign in.');
        }
        $pdo->prepare('UPDATE customers SET name = ?, password_hash = ? WHERE id = ?')->execute([
            $name,
            $hash,
            (int) $row['id'],
        ]);
        $id = (int) $row['id'];
    } else {
        $limit = new_customer_credit_limit();
        $pdo->prepare('INSERT INTO customers (name, phone, password_hash, credit_limit, created_at) VALUES (?, ?, ?, ?, ?)')->execute([
            $name,
            $phone,
            $hash,
            $limit,
            date('Y-m-d H:i:s'),
        ]);
        $id = (int) $pdo->lastInsertId();
    }
    return [
        'token' => issue_customer_token($id),
        'name' => $name,
        'phone' => $phone,
    ];
}

function login_customer(string $phone, string $password): array
{
    require_shop_floor();
    $phone = customer_phone($phone);
    $password = (string) $password;
    $stmt = db()->prepare('SELECT id, name, phone, password_hash FROM customers WHERE phone = ?');
    $stmt->execute([$phone]);
    $row = $stmt->fetch();
    $hash = $row ? trim((string) ($row['password_hash'] ?? '')) : '';
    if (!$row || $hash === '' || !password_verify($password, $hash)) {
        throw new AppError('Wrong phone or password.', 401);
    }
    return [
        'token' => issue_customer_token((int) $row['id']),
        'name' => (string) $row['name'],
        'phone' => (string) $row['phone'],
    ];
}

function logout_customer(): void
{
    $token = bearer_token();
    if ($token !== null) {
        db()->prepare('DELETE FROM tokens WHERE token = ? AND customer_id IS NOT NULL')->execute([$token]);
    }
}

function customer_bill_no(int $id): string
{
    return str_pad((string) $id, 4, '0', STR_PAD_LEFT);
}

function customer_sale_lines(PDO $pdo, int $saleId): array
{
    $stmt = $pdo->prepare(
        'SELECT id, item_name, unit_price, qty, line_total, added_at FROM sale_lines WHERE sale_id = ? ORDER BY added_at, id'
    );
    $stmt->execute([$saleId]);
    $lines = [];
    foreach ($stmt->fetchAll() as $line) {
        $lines[] = [
            'id' => (int) $line['id'],
            'item_name' => (string) $line['item_name'],
            'unit_price' => from_cents(to_cents((string) $line['unit_price'])),
            'qty' => (int) $line['qty'],
            'line_total' => from_cents(to_cents((string) $line['line_total'])),
            'added_at' => (string) $line['added_at'],
        ];
    }
    return $lines;
}

function customer_sale_card(array $row, array $lines): array
{
    return [
        'id' => (int) $row['id'],
        'bill_no' => customer_bill_no((int) $row['id']),
        'table_id' => $row['table_id'] !== null ? (int) $row['table_id'] : 0,
        'table_name' => (string) $row['table_name'],
        'status' => (string) $row['status'],
        'pay_review' => (string) ($row['pay_review'] ?? ''),
        'payment_method' => (string) ($row['payment_method'] ?? ''),
        'payment_label' => payment_label($row['payment_method'] ?? ''),
        'opened_at' => (string) $row['opened_at'],
        'closed_at' => (string) ($row['closed_at'] ?? ''),
        'total' => from_cents(to_cents((string) $row['total'])),
        'lines' => $lines,
    ];
}

function customer_bills(int $customerId): array
{
    $pdo = db();
    $stmt = $pdo->prepare(
        'SELECT id, table_id, table_name, status, payment_method, pay_review, opened_at, closed_at, total
         FROM sales
         WHERE customer_id = ? AND status IN ("open", "credit", "paid")
         ORDER BY FIELD(status, "open", "credit", "paid"), id DESC
         LIMIT 60'
    );
    $stmt->execute([$customerId]);
    $out = [];
    foreach ($stmt->fetchAll() as $row) {
        $out[] = customer_sale_card($row, customer_sale_lines($pdo, (int) $row['id']));
    }
    return $out;
}

function customer_table_state(int $customerId, int $tableId): array
{
    $pdo = db();
    $table = $pdo->prepare('SELECT id, name, active FROM cafe_tables WHERE id = ?');
    $table->execute([$tableId]);
    $row = $table->fetch();
    if (!$row || (int) $row['active'] !== 1) {
        throw new AppError('That table is not available.', 404);
    }
    $state = [
        'table_id' => (int) $row['id'],
        'table_name' => (string) $row['name'],
        'blocked' => false,
        'message' => '',
        'order' => null,
    ];
    $open = $pdo->prepare('SELECT sale_id FROM open_tables WHERE table_id = ?');
    $open->execute([$tableId]);
    $link = $open->fetch();
    if (!$link) {
        return $state;
    }
    $sale = $pdo->prepare(
        'SELECT id, table_id, table_name, status, payment_method, pay_review, opened_at, closed_at, total, customer_id
         FROM sales WHERE id = ?'
    );
    $sale->execute([(int) $link['sale_id']]);
    $bill = $sale->fetch();
    if ($bill && $bill['status'] === 'open' && (int) $bill['customer_id'] === $customerId) {
        $state['order'] = customer_sale_card($bill, customer_sale_lines($pdo, (int) $bill['id']));
        return $state;
    }
    $state['blocked'] = true;
    $state['message'] = 'This table is already open with the staff.';
    return $state;
}

function customer_start_order(int $customerId, int $tableId): array
{
    $state = customer_table_state($customerId, $tableId);
    if ($state['blocked']) {
        throw new AppError($state['message'], 409);
    }
    if ($state['order'] !== null) {
        return $state;
    }
    $pdo = db();
    $other = $pdo->prepare('SELECT id FROM sales WHERE customer_id = ? AND status = "open" LIMIT 1');
    $other->execute([$customerId]);
    if ($other->fetch()) {
        throw new AppError('Pay your open order before starting another table.');
    }
    $pdo->beginTransaction();
    try {
        $table = $pdo->prepare('SELECT id, name, active FROM cafe_tables WHERE id = ? FOR UPDATE');
        $table->execute([$tableId]);
        $row = $table->fetch();
        if (!$row || (int) $row['active'] !== 1) {
            throw new AppError('That table is not available.');
        }
        $existing = $pdo->prepare('SELECT sale_id FROM open_tables WHERE table_id = ?');
        $existing->execute([$tableId]);
        if ($existing->fetch()) {
            throw new AppError('This table is already open with the staff.', 409);
        }
        $now = date('Y-m-d H:i:s');
        $pdo->prepare(
            'INSERT INTO sales (table_id, table_name, opened_at, total, status, waiter_id, customer_id) VALUES (?, ?, ?, 0.00, "open", NULL, ?)'
        )->execute([(int) $row['id'], $row['name'], $now, $customerId]);
        $saleId = (int) $pdo->lastInsertId();
        $pdo->prepare('INSERT INTO open_tables (table_id, sale_id) VALUES (?, ?)')->execute([$tableId, $saleId]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return customer_table_state($customerId, $tableId);
}

function customer_owned_open(PDO $pdo, int $customerId, int $saleId): array
{
    $stmt = $pdo->prepare(
        'SELECT id, table_id, table_name, status, payment_method, pay_review, opened_at, closed_at, total, customer_id
         FROM sales WHERE id = ? FOR UPDATE'
    );
    $stmt->execute([$saleId]);
    $sale = $stmt->fetch();
    if (!$sale || (int) $sale['customer_id'] !== $customerId || $sale['status'] !== 'open') {
        throw new AppError('That order is not open for you.', 404);
    }
    if ((string) ($sale['pay_review'] ?? '') === 'pending') {
        throw new AppError('This payment is waiting to be checked.');
    }
    return $sale;
}

function customer_add_item(int $customerId, int $saleId, int $itemId, int $qty): array
{
    if ($itemId <= 0 || $qty < 1 || $qty > 99) {
        throw new AppError('Choose an item and a quantity from 1 to 99.');
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $sale = customer_owned_open($pdo, $customerId, $saleId);
        $itemStmt = $pdo->prepare('SELECT id, name, price, active FROM items WHERE id = ?');
        $itemStmt->execute([$itemId]);
        $item = $itemStmt->fetch();
        if (!$item || (int) $item['active'] !== 1) {
            throw new AppError('That item is not available.');
        }
        $today = date('Y-m-d');
        $lineStmt = $pdo->prepare('SELECT id, qty, unit_price, added_at FROM sale_lines WHERE sale_id = ? AND item_id = ? FOR UPDATE');
        $lineStmt->execute([$saleId, $itemId]);
        $line = null;
        foreach ($lineStmt->fetchAll() as $candidate) {
            if (substr((string) $candidate['added_at'], 0, 10) === $today) {
                $line = $candidate;
                break;
            }
        }
        if ($line) {
            $next = (int) $line['qty'] + $qty;
            if ($next > 99) {
                throw new AppError('Quantity cannot exceed 99 for one item.');
            }
            $unit = to_cents((string) $line['unit_price']);
            $pdo->prepare('UPDATE sale_lines SET qty = ?, line_total = ? WHERE id = ?')->execute([
                $next,
                from_cents($unit * $next),
                (int) $line['id'],
            ]);
        } else {
            $unit = to_cents((string) $item['price']);
            $pdo->prepare(
                'INSERT INTO sale_lines (sale_id, item_id, item_name, unit_price, qty, line_total, added_at) VALUES (?, ?, ?, ?, ?, ?, ?)'
            )->execute([
                $saleId,
                $itemId,
                $item['name'],
                from_cents($unit),
                $qty,
                from_cents($unit * $qty),
                date('Y-m-d H:i:s'),
            ]);
        }
        $pdo->prepare('UPDATE sales SET service_started_at = ? WHERE id = ? AND service_started_at IS NULL')->execute([
            date('Y-m-d H:i:s'),
            $saleId,
        ]);
        recalc_order($pdo, $saleId);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    $fresh = db()->prepare(
        'SELECT id, table_id, table_name, status, payment_method, opened_at, closed_at, total, customer_id FROM sales WHERE id = ?'
    );
    $fresh->execute([$saleId]);
    return customer_sale_card($fresh->fetch(), customer_sale_lines(db(), $saleId));
}

function customer_set_qty(int $customerId, int $saleId, int $lineId, int $qty): array
{
    if ($qty < 0 || $qty > 99) {
        throw new AppError('Quantity must be from 0 to 99.');
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        customer_owned_open($pdo, $customerId, $saleId);
        $lineStmt = $pdo->prepare('SELECT id, unit_price FROM sale_lines WHERE id = ? AND sale_id = ? FOR UPDATE');
        $lineStmt->execute([$lineId, $saleId]);
        $line = $lineStmt->fetch();
        if (!$line) {
            throw new AppError('That line is no longer on the bill.', 404);
        }
        if ($qty === 0) {
            $pdo->prepare('DELETE FROM sale_lines WHERE id = ?')->execute([$lineId]);
        } else {
            $unit = to_cents((string) $line['unit_price']);
            $pdo->prepare('UPDATE sale_lines SET qty = ?, line_total = ? WHERE id = ?')->execute([
                $qty,
                from_cents($unit * $qty),
                $lineId,
            ]);
        }
        recalc_order($pdo, $saleId);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    $fresh = db()->prepare(
        'SELECT id, table_id, table_name, status, payment_method, opened_at, closed_at, total, customer_id FROM sales WHERE id = ?'
    );
    $fresh->execute([$saleId]);
    return customer_sale_card($fresh->fetch(), customer_sale_lines(db(), $saleId));
}

function customer_cancel_order(int $customerId, int $saleId): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $sale = customer_owned_open($pdo, $customerId, $saleId);
        $count = $pdo->prepare('SELECT COUNT(*) FROM sale_lines WHERE sale_id = ?');
        $count->execute([$saleId]);
        if ((int) $count->fetchColumn() > 0) {
            throw new AppError('This order has items. Pay it, or ask the staff.');
        }
        $pdo->prepare('DELETE FROM open_tables WHERE sale_id = ?')->execute([$saleId]);
        $pdo->prepare('DELETE FROM sales WHERE id = ?')->execute([$saleId]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
}

function customer_pay_bill(int $customerId, int $saleId, string $payment, ?array $slip): array
{
    if ($payment !== 'cash' && $payment !== 'online') {
        throw new AppError('Choose cash or bank.');
    }
    if ($payment === 'online' && $slip === null) {
        throw new AppError('Take a photo of the payment.');
    }
    $slipPath = uploads_root() . '/slips/' . $saleId . '.jpg';
    $wroteSlip = false;
    if ($payment === 'online' && $slip !== null) {
        store_jpeg_upload($slip, $slipPath);
        $wroteSlip = true;
    }
    $waiting = false;
    $dropSlip = false;
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare(
            'SELECT id, table_id, table_name, status, payment_method, pay_review, opened_at, closed_at, total, customer_id
             FROM sales WHERE id = ? FOR UPDATE'
        );
        $stmt->execute([$saleId]);
        $sale = $stmt->fetch();
        if (!$sale || (int) $sale['customer_id'] !== $customerId || !in_array($sale['status'], ['open', 'credit'], true)) {
            throw new AppError('That bill is not waiting for payment.', 404);
        }
        $count = $pdo->prepare('SELECT COUNT(*) FROM sale_lines WHERE sale_id = ?');
        $count->execute([$saleId]);
        if ((int) $count->fetchColumn() < 1) {
            throw new AppError('Add items before paying.');
        }
        if ($payment === 'online') {
            $pdo->prepare('UPDATE sales SET pay_review = "pending", slip_image = ? WHERE id = ?')->execute([
                $saleId . '.jpg',
                $saleId,
            ]);
            $waiting = true;
        } else {
            $dropSlip = (string) ($sale['pay_review'] ?? '') === 'pending';
            $pdo->prepare(
                'UPDATE sales SET status = "paid", payment_method = ?, closed_at = ?, slip_image = "", pay_review = "" WHERE id = ?'
            )->execute([
                $payment,
                date('Y-m-d H:i:s'),
                $saleId,
            ]);
            if ($sale['table_id'] !== null) {
                $pdo->prepare('DELETE FROM open_tables WHERE table_id = ? AND sale_id = ?')->execute([
                    (int) $sale['table_id'],
                    $saleId,
                ]);
            }
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        if ($wroteSlip && is_file($slipPath)) {
            unlink($slipPath);
        }
        throw $error;
    }
    if ($dropSlip && is_file($slipPath)) {
        unlink($slipPath);
    }
    $fresh = db()->prepare(
        'SELECT id, table_id, table_name, status, payment_method, pay_review, opened_at, closed_at, total, customer_id FROM sales WHERE id = ?'
    );
    $fresh->execute([$saleId]);
    $card = customer_sale_card($fresh->fetch(), customer_sale_lines(db(), $saleId));
    $card['waiting'] = $waiting;
    return $card;
}

function staff_can_approve(array $staff): bool
{
    $role = (string) ($staff['role'] ?? '');
    return $role === 'manager' || $role === 'superadmin';
}

function staff_create_customer(string $name, string $phone): int
{
    $name = trim($name);
    $phone = preg_replace('/\D+/', '', $phone) ?? '';
    if ($name === '' || strlen($name) > 80 || strlen($phone) < 5 || strlen($phone) > 15) {
        throw new AppError('Enter the customer name and phone.');
    }
    $existing = db()->prepare('SELECT id FROM customers WHERE phone = ?');
    $existing->execute([$phone]);
    if ($existing->fetch()) {
        throw new AppError('That phone is already a customer.');
    }
    $limit = new_customer_credit_limit();
    db()->prepare('INSERT INTO customers (name, phone, credit_limit, created_at) VALUES (?, ?, ?, ?)')->execute([$name, $phone, $limit, date('Y-m-d H:i:s')]);
    return (int) db()->lastInsertId();
}

function customer_directory(string $query): array
{
    $query = trim($query);
    $digits = preg_replace('/\D+/', '', $query) ?? '';
    $rows = [];
    foreach (list_customers() as $customer) {
        if ($query === '') {
            $rows[] = $customer;
            continue;
        }
        $hay = strtolower($customer['name'] . ' ' . $customer['phone']);
        if (str_contains($hay, strtolower($query)) || ($digits !== '' && str_contains($customer['phone'], $digits))) {
            $rows[] = $customer;
        }
    }
    return $rows;
}

function customer_bill_rows(int $customerId): array
{
    $stmt = db()->prepare(
        'SELECT s.id, s.table_name, s.status, s.payment_method, s.opened_at, s.closed_at, s.total, s.pay_review, s.slip_image,
                (SELECT COUNT(*) FROM sale_lines l WHERE l.sale_id = s.id) AS item_count
         FROM sales s
         WHERE s.customer_id = ? AND s.status IN ("open", "credit", "paid")
         ORDER BY (s.pay_review = "pending") DESC, FIELD(s.status, "credit", "open", "paid"), s.id DESC
         LIMIT 80'
    );
    $stmt->execute([$customerId]);
    $out = [];
    $pdo = db();
    foreach ($stmt->fetchAll() as $row) {
        $lines = [];
        if ((string) $row['status'] === 'credit' || (string) $row['pay_review'] === 'pending') {
            $lines = customer_sale_lines($pdo, (int) $row['id']);
        }
        $out[] = [
            'id' => (int) $row['id'],
            'bill_no' => customer_bill_no((int) $row['id']),
            'table_name' => (string) $row['table_name'],
            'status' => (string) $row['status'],
            'payment_method' => (string) ($row['payment_method'] ?? ''),
            'payment_label' => payment_label($row['payment_method'] ?? ''),
            'opened_at' => (string) $row['opened_at'],
            'closed_at' => (string) ($row['closed_at'] ?? ''),
            'total' => from_cents(to_cents((string) $row['total'])),
            'pay_review' => (string) $row['pay_review'],
            'has_slip' => trim((string) $row['slip_image']) !== '',
            'item_count' => (int) $row['item_count'],
            'lines' => $lines,
        ];
    }
    return $out;
}

function customer_staff_view(int $id): array
{
    $customer = null;
    foreach (list_customers() as $row) {
        if ((int) $row['id'] === $id) {
            $customer = $row;
            break;
        }
    }
    if ($customer === null) {
        throw new AppError('Choose a customer.', 404);
    }
    return [
        'customer' => $customer,
        'bills' => customer_bill_rows($id),
    ];
}

function pending_customer_payments(): array
{
    $rows = db()->query(
        'SELECT s.id, s.table_name, s.total, s.status, s.opened_at, s.slip_image,
                c.id AS customer_id, c.name AS customer_name, c.phone AS customer_phone
         FROM sales s
         JOIN customers c ON c.id = s.customer_id
         WHERE s.pay_review = "pending" AND s.status IN ("open", "credit")
         ORDER BY s.id'
    )->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'bill_no' => customer_bill_no((int) $row['id']),
            'table_name' => (string) $row['table_name'],
            'status' => (string) $row['status'],
            'opened_at' => (string) $row['opened_at'],
            'total' => from_cents(to_cents((string) $row['total'])),
            'has_slip' => trim((string) $row['slip_image']) !== '',
            'customer_id' => (int) $row['customer_id'],
            'customer_name' => (string) $row['customer_name'],
            'customer_phone' => (string) $row['customer_phone'],
        ];
    }
    return $out;
}

function lock_review_sale(PDO $pdo, int $saleId): array
{
    $stmt = $pdo->prepare(
        'SELECT id, table_id, status, customer_id, held, pay_review, slip_image FROM sales WHERE id = ? FOR UPDATE'
    );
    $stmt->execute([$saleId]);
    $sale = $stmt->fetch();
    if (!$sale || (string) $sale['pay_review'] !== 'pending' || !in_array($sale['status'], ['open', 'credit'], true)) {
        throw new AppError('That payment is not waiting to be checked.', 404);
    }
    if ((int) $sale['customer_id'] < 1) {
        throw new AppError('That bill has no customer.');
    }
    return $sale;
}

function approve_customer_payment(int $saleId): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $sale = lock_review_sale($pdo, $saleId);
        $held = ((int) $sale['held'] === 1 || $sale['status'] === 'credit') ? 1 : 0;
        $pdo->prepare(
            'UPDATE sales SET status = "paid", payment_method = "online", closed_at = ?, held = ?, pay_review = "" WHERE id = ?'
        )->execute([date('Y-m-d H:i:s'), $held, $saleId]);
        if ($sale['table_id'] !== null) {
            $pdo->prepare('DELETE FROM open_tables WHERE table_id = ? AND sale_id = ?')->execute([
                (int) $sale['table_id'],
                $saleId,
            ]);
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
}

function reject_customer_payment(int $saleId): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $sale = lock_review_sale($pdo, $saleId);
        $pdo->prepare('UPDATE sales SET pay_review = "", slip_image = "" WHERE id = ?')->execute([$saleId]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    $path = uploads_root() . '/slips/' . $saleId . '.jpg';
    if (is_file($path)) {
        unlink($path);
    }
    $shared = trim((string) ($sale['slip_image'] ?? ''));
    if ($shared !== '' && $shared !== $saleId . '.jpg') {
        $other = uploads_root() . '/slips/' . basename($shared);
        if (is_file($other)) {
            unlink($other);
        }
    }
}

function shop_order_host(): string
{
    $saved = trim((string) (settings()['order_host'] ?? ''));
    if ($saved !== '') {
        return $saved;
    }
    foreach ([$_SERVER['SERVER_ADDR'] ?? '', $_SERVER['HTTP_HOST'] ?? ''] as $candidate) {
        $host = preg_replace('/:\d+$/', '', trim((string) $candidate)) ?? '';
        if (is_private_ipv4($host)) {
            return $host;
        }
    }
    return '';
}

function save_order_host(string $host): void
{
    $host = trim($host);
    $host = preg_replace('#^https?://#i', '', $host) ?? '';
    $host = rtrim($host, '/');
    if ($host !== '' && !preg_match('/^[A-Za-z0-9.-]+$/', $host)) {
        throw new AppError('Enter the cafe computer address, like 192.168.1.8.');
    }
    db()->prepare('UPDATE settings SET order_host = ? WHERE id = 1')->execute([$host]);
}

function pos_web_root(): string
{
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    foreach (['/admin/', '/customer/', '/waiter/', '/api/'] as $part) {
        $at = strpos($script, $part);
        if ($at !== false) {
            return rtrim(substr($script, 0, $at), '/');
        }
    }
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
    return ($dir === '' || $dir === '/' || $dir === '.') ? '' : $dir;
}

function table_order_url(int $tableId): string
{
    $host = shop_order_host();
    if ($host === '' || $tableId < 1) {
        return '';
    }
    return 'http://' . $host . pos_web_root() . '/customer/?table=' . $tableId;
}

function credit_book_font(string $weight): string
{
    $file = dirname(__DIR__) . '/waiter/fonts/NotoSansBengali-' . $weight . '.ttf';
    if (!is_file($file) || !function_exists('imagettftext')) {
        throw new AppError('The PDF could not be made.');
    }
    return $file;
}

function credit_book_plain(string $text): string
{
    $clean = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $text);
    if (!is_string($clean)) {
        $clean = preg_replace('/[\x00-\x1F\x7F]/', ' ', $text) ?? '';
    }
    $clean = preg_replace('/\s+/u', ' ', $clean);
    return trim(is_string($clean) ? $clean : '');
}

function credit_book_customers(): array
{
    $rows = db()->query(
        'SELECT c.id, c.name, c.phone, c.credit_limit, c.created_at,
                s.id AS sale_id, s.table_name, s.opened_at, s.total, s.pay_review,
                l.id AS line_id, l.item_name, l.qty, l.line_total, l.added_at
         FROM customers c
         LEFT JOIN sales s ON s.customer_id = c.id AND s.status = "credit"
         LEFT JOIN sale_lines l ON l.sale_id = s.id
         ORDER BY c.id, s.opened_at, s.id, l.added_at, l.id'
    )->fetchAll();
    $people = [];
    foreach ($rows as $row) {
        $id = (int) $row['id'];
        if (!isset($people[$id])) {
            $limit = $row['credit_limit'] === null || $row['credit_limit'] === ''
                ? null
                : to_cents((string) $row['credit_limit']);
            $people[$id] = [
                'name' => credit_book_plain((string) $row['name']),
                'phone' => credit_book_plain((string) $row['phone']),
                'created_at' => (string) $row['created_at'],
                'limit_cents' => $limit,
                'open_cents' => 0,
                'bills' => [],
            ];
        }
        $saleId = $row['sale_id'] === null ? 0 : (int) $row['sale_id'];
        if ($saleId < 1) {
            continue;
        }
        if (!isset($people[$id]['bills'][$saleId])) {
            $cents = to_cents((string) $row['total']);
            $people[$id]['open_cents'] += $cents;
            $people[$id]['bills'][$saleId] = [
                'id' => $saleId,
                'table_name' => credit_book_plain((string) $row['table_name']),
                'opened_at' => (string) $row['opened_at'],
                'total_cents' => $cents,
                'pay_review' => (string) $row['pay_review'],
                'lines' => [],
            ];
        }
        if ($row['line_id'] !== null) {
            $people[$id]['bills'][$saleId]['lines'][] = [
                'item_name' => credit_book_plain((string) $row['item_name']),
                'qty' => (int) $row['qty'],
                'line_total' => from_cents(to_cents((string) $row['line_total'])),
                'added_at' => (string) $row['added_at'],
            ];
        }
    }
    $out = array_values($people);
    usort($out, static function (array $a, array $b): int {
        if ($a['open_cents'] !== $b['open_cents']) {
            return $b['open_cents'] <=> $a['open_cents'];
        }
        return strcasecmp($a['name'], $b['name']);
    });
    foreach ($out as &$person) {
        $person['bills'] = array_values($person['bills']);
    }
    unset($person);
    return $out;
}

function credit_book_document(): array
{
    $customers = credit_book_customers();
    $billCount = 0;
    $open = 0;
    $cards = [];
    foreach ($customers as $person) {
        $open += $person['open_cents'];
        $billCount += count($person['bills']);
        $limit = $person['limit_cents'] === null ? 'No limit' : money_label(from_cents($person['limit_cents']));
        $left = $person['limit_cents'] === null ? 'No limit' : money_label(from_cents($person['limit_cents'] - $person['open_cents']));
        $bills = [];
        foreach ($person['bills'] as $bill) {
            $where = $bill['table_name'] !== '' ? $bill['table_name'] : 'Table';
            $lines = [];
            foreach ($bill['lines'] as $line) {
                $lines[] = [
                    'label' => $line['qty'] . ' x ' . $line['item_name'],
                    'amount' => money_label($line['line_total']),
                    'when' => format_when($line['added_at']),
                ];
            }
            $bills[] = [
                'title' => 'Bill no. ' . customer_bill_no($bill['id']) . ' · ' . $where . ' · ' . format_when($bill['opened_at']),
                'amount' => money_label(from_cents($bill['total_cents'])),
                'state' => $bill['pay_review'] === 'pending' ? 'Waiting for approval' : 'Not paid',
                'lines' => $lines,
            ];
        }
        $cards[] = [
            'name' => $person['name'] !== '' ? $person['name'] : 'Customer',
            'phone' => $person['phone'],
            'meta' => 'Added ' . format_date($person['created_at']) . ' · Limit ' . $limit . ' · Left ' . $left,
            'due' => 'Bills ' . count($person['bills']) . ' · Credit total ' . money_label(from_cents($person['open_cents'])),
            'due_open' => $person['open_cents'] > 0,
            'bills' => $bills,
        ];
    }
    $cafe = trim((string) (settings()['cafe_name'] ?? ''));
    return [
        'cafe' => $cafe !== '' ? $cafe : 'Ithaa Corner',
        'when' => format_when(date('Y-m-d H:i:s')),
        'customers_label' => (string) count($cards),
        'bills_label' => (string) $billCount,
        'total_label' => money_label(from_cents($open)),
        'total_open' => $open > 0,
        'customers' => $cards,
    ];
}

final class CreditBookPdf
{
    private const W = 1191;
    private const H = 1684;
    private const LEFT = 64;
    private const RIGHT = 1127;
    private const BOTTOM = 1600;

    private string $regular;
    private string $bold;
    /** @var list<string> */
    private array $jpegs = [];
    private ?GdImage $im = null;
    private int $y = 0;
    private int $page = 0;
    private int $black = 0;
    private int $gray = 0;
    private int $red = 0;
    private int $rule = 0;

    public function __construct(string $regular, string $bold)
    {
        $this->regular = $regular;
        $this->bold = $bold;
    }

    public function render(array $book): string
    {
        $this->turn(true);
        $this->center($this->bold, 40, $book['cafe'], $this->black);
        $this->y += 56;
        $this->center($this->bold, 28, 'Credit bills', $this->black);
        $this->y += 42;
        $this->center($this->regular, 20, $book['when'], $this->gray);
        $this->y += 36;
        $this->center($this->regular, 18, 'Open credit bills and customer details. These bills are not paid.', $this->gray);
        $this->y += 34;
        $this->pair('Customers', $book['customers_label'], 22, false);
        $this->pair('Bills', $book['bills_label'], 22, false);
        $this->pair('Credit total', $book['total_label'], 22, $book['total_open']);
        $this->rule();
        if ($book['customers'] === []) {
            $this->line($this->regular, 22, 'No customers yet.', $this->gray);
        }
        foreach ($book['customers'] as $person) {
            $this->need(150);
            $this->line($this->bold, 28, $person['name'], $this->black);
            if ($person['phone'] !== '') {
                $this->line($this->regular, 20, 'Phone ' . $person['phone'], $this->gray);
            }
            $this->line($this->regular, 20, $person['meta'], $this->black);
            $this->line($this->bold, 20, $person['due'], $person['due_open'] ? $this->red : $this->black);
            if ($person['bills'] === []) {
                $this->line($this->regular, 20, 'No held bills.', $this->gray);
            }
            foreach ($person['bills'] as $bill) {
                $this->need(88);
                $this->y += 8;
                $this->pair($bill['title'], $bill['amount'], 20, false);
                $this->line($this->bold, 18, $bill['state'], $this->red);
                foreach ($bill['lines'] as $item) {
                    $this->pair($item['label'], $item['amount'], 18, false);
                    if ($item['when'] !== '') {
                        $this->line($this->regular, 16, $item['when'], $this->gray);
                    }
                }
            }
            $this->y += 8;
            $this->rule();
        }
        $this->seal();
        return $this->pack();
    }

    private function turn(bool $cover): void
    {
        if ($this->im instanceof GdImage) {
            $this->seal();
        }
        $this->page++;
        $image = imagecreatetruecolor(self::W, self::H);
        if (!$image instanceof GdImage) {
            throw new AppError('The PDF could not be made.');
        }
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white === false ? 0 : $white);
        $this->im = $image;
        $this->black = $this->color(17, 17, 17);
        $this->gray = $this->color(68, 68, 68);
        $this->red = $this->color(226, 75, 75);
        $this->rule = $this->color(210, 210, 210);
        $this->y = 72;
        if (!$cover) {
            $this->center($this->bold, 22, 'Credit bills', $this->black);
            $this->y += 40;
            $this->rule();
        }
    }

    private function seal(): void
    {
        if (!$this->im instanceof GdImage) {
            return;
        }
        $label = 'Page ' . $this->page;
        $width = $this->width($this->regular, 16, $label);
        imagettftext($this->im, 16, 0, (int) ((self::W - $width) / 2), self::H - 42, $this->gray, $this->regular, $label);
        $tmp = fopen('php://temp', 'r+b');
        if ($tmp === false || !imagejpeg($this->im, $tmp, 84)) {
            throw new AppError('The PDF could not be made.');
        }
        rewind($tmp);
        $jpeg = stream_get_contents($tmp);
        fclose($tmp);
        imagedestroy($this->im);
        $this->im = null;
        if (!is_string($jpeg) || !str_starts_with($jpeg, "\xFF\xD8")) {
            throw new AppError('The PDF could not be made.');
        }
        $this->jpegs[] = $jpeg;
    }

    private function color(int $r, int $g, int $b): int
    {
        $color = imagecolorallocate($this->im, $r, $g, $b);
        return $color === false ? 0 : $color;
    }

    private function width(string $font, int $size, string $text): int
    {
        if ($text === '') {
            return 0;
        }
        $box = imagettfbbox($size, 0, $font, $text);
        if ($box === false) {
            return $size * strlen($text);
        }
        return abs($box[2] - $box[0]);
    }

    /** @return list<string> */
    private function wrap(string $font, int $size, string $text, int $max): array
    {
        $text = trim($text);
        if ($text === '') {
            return [];
        }
        $words = preg_split('/ /', $text) ?: [];
        $lines = [];
        $current = '';
        foreach ($words as $word) {
            if ($word === '') {
                continue;
            }
            $trial = $current === '' ? $word : $current . ' ' . $word;
            if ($this->width($font, $size, $trial) <= $max) {
                $current = $trial;
                continue;
            }
            if ($current !== '') {
                $lines[] = $current;
                $current = '';
            }
            if ($this->width($font, $size, $word) <= $max) {
                $current = $word;
                continue;
            }
            $chunk = '';
            $length = mb_strlen($word);
            for ($i = 0; $i < $length; $i++) {
                $char = mb_substr($word, $i, 1);
                $next = $chunk . $char;
                if ($chunk !== '' && $this->width($font, $size, $next) > $max) {
                    $lines[] = $chunk;
                    $chunk = $char;
                } else {
                    $chunk = $next;
                }
            }
            $current = $chunk;
        }
        if ($current !== '') {
            $lines[] = $current;
        }
        return $lines;
    }

    private function need(int $height): void
    {
        if ($this->im instanceof GdImage && $this->y + $height <= self::BOTTOM) {
            return;
        }
        $this->turn(false);
    }

    private function center(string $font, int $size, string $text, int $color): void
    {
        foreach ($this->wrap($font, $size, $text, self::RIGHT - self::LEFT) as $line) {
            $this->need($size + 8);
            $x = (int) ((self::W - $this->width($font, $size, $line)) / 2);
            imagettftext($this->im, $size, 0, max(self::LEFT, $x), $this->y + $size, $color, $font, $line);
            $this->y += $size + 8;
        }
    }

    private function line(string $font, int $size, string $text, int $color): void
    {
        $lines = $this->wrap($font, $size, $text, self::RIGHT - self::LEFT);
        if ($lines === []) {
            return;
        }
        foreach ($lines as $row) {
            $this->need($size + 12);
            imagettftext($this->im, $size, 0, self::LEFT, $this->y + $size, $color, $font, $row);
            $this->y += $size + 12;
        }
    }

    private function pair(string $left, string $right, int $size, bool $alert): void
    {
        $rightWidth = $this->width($this->bold, $size, $right);
        $max = self::RIGHT - self::LEFT - $rightWidth - 28;
        $lines = $this->wrap($this->regular, $size, $left, max(120, $max));
        if ($lines === []) {
            $lines = [''];
        }
        foreach ($lines as $index => $row) {
            $this->need($size + 12);
            if ($row !== '') {
                imagettftext($this->im, $size, 0, self::LEFT, $this->y + $size, $this->black, $this->regular, $row);
            }
            if ($index === 0 && $right !== '') {
                imagettftext(
                    $this->im,
                    $size,
                    0,
                    self::RIGHT - $rightWidth,
                    $this->y + $size,
                    $alert ? $this->red : $this->black,
                    $this->bold,
                    $right
                );
            }
            $this->y += $size + 12;
        }
    }

    private function rule(): void
    {
        $this->need(22);
        $this->y += 8;
        imagesetthickness($this->im, 2);
        imageline($this->im, self::LEFT, $this->y, self::RIGHT, $this->y, $this->rule);
        imagesetthickness($this->im, 1);
        $this->y += 16;
    }

    private function pack(): string
    {
        $count = count($this->jpegs);
        if ($count < 1) {
            throw new AppError('The PDF could not be made.');
        }
        $objs = [];
        $objs[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $kids = [];
        for ($i = 0; $i < $count; $i++) {
            $kids[] = (3 + $i * 3) . ' 0 R';
        }
        $objs[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $count . ' >>';
        for ($i = 0; $i < $count; $i++) {
            $pageId = 3 + $i * 3;
            $contentId = $pageId + 1;
            $imageId = $pageId + 2;
            $objs[$pageId] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents ' . $contentId . ' 0 R /Resources << /XObject << /Im0 ' . $imageId . ' 0 R >> >> >>';
            $content = 'q 595 0 0 842 0 0 cm /Im0 Do Q';
            $objs[$contentId] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "\nendstream";
            $jpeg = $this->jpegs[$i];
            $objs[$imageId] = '<< /Type /XObject /Subtype /Image /Width ' . self::W . ' /Height ' . self::H
                . ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ' . strlen($jpeg)
                . " >>\nstream\n" . $jpeg . "\nendstream";
        }
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        $max = 2 + $count * 3;
        for ($i = 1; $i <= $max; $i++) {
            $offsets[$i] = strlen($pdf);
            $pdf .= $i . " 0 obj\n" . $objs[$i] . "\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= 'xref\n0 ' . ($max + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $max; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= 'trailer\n<< /Size ' . ($max + 1) . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";
        return $pdf;
    }
}

function credit_book_pdf_bytes(): string
{
    $pdf = new CreditBookPdf(credit_book_font('Regular'), credit_book_font('Bold'));
    return $pdf->render(credit_book_document());
}

function send_credit_book_pdf(): void
{
    $pdf = credit_book_pdf_bytes();
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="credit-bills.pdf"');
    header('Cache-Control: no-store');
    header('Content-Length: ' . strlen($pdf));
    echo $pdf;
    exit;
}

require __DIR__ . '/books.php';

config();
