<?php

require_once __DIR__ . '/../api/lib.php';

function today_bars(array $days): string
{
    $max = 1;
    foreach ($days as $day) {
        foreach (['sales', 'expenses', 'profit'] as $key) {
            $max = max($max, abs(to_cents((string) $day[$key])));
        }
    }
    $html = '<div class="bars">';
    foreach ($days as $day) {
        $sales = to_cents((string) $day['sales']);
        $expenses = to_cents((string) $day['expenses']);
        $profit = to_cents((string) $day['profit']);
        $bar = static function (int $cents, string $class) use ($max): string {
            if ($cents === 0) {
                return '<i class="' . $class . '" style="height:0"></i>';
            }
            $height = max(8, (int) round(abs($cents) / $max * 100));
            return '<i class="' . $class . '" style="height:' . $height . '%"></i>';
        };
        $html .= '<div class="bar-col" title="' . h(format_date($day['date'])) . '"><div class="bar-pair">'
            . $bar($sales, 'sale')
            . $bar($profit, $profit < 0 ? 'loss' : 'profit')
            . $bar($expenses, 'cost')
            . '</div><span>' . h(substr((string) $day['date'], 8, 2)) . '</span></div>';
    }
    return $html . '</div>';
}

function chart_axis_label(int $cents): string
{
    $units = $cents / 100;
    $abs = abs($units);
    if ($abs >= 1000000) {
        return rtrim(rtrim(number_format($units / 1000000, 1, '.', ''), '0'), '.') . 'm';
    }
    if ($abs >= 1000) {
        return rtrim(rtrim(number_format($units / 1000, 1, '.', ''), '0'), '.') . 'k';
    }
    return (string) (int) round($units);
}

function year_line_chart(int $year, array $months): string
{
    $names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $signed = static function (string $amount): int {
        $amount = str_replace([',', ' '], '', trim($amount));
        $negative = str_starts_with($amount, '-');
        if ($negative) {
            $amount = substr($amount, 1);
        }
        return ($negative ? -1 : 1) * to_cents($amount === '' ? '0' : $amount);
    };
    $sales = [];
    $expenses = [];
    $left = [];
    for ($month = 1; $month <= 12; $month++) {
        $row = $months[$month] ?? [];
        $sales[] = $signed((string) ($row['sales'] ?? '0'));
        $expenses[] = $signed((string) ($row['expenses'] ?? '0'));
        $left[] = $signed((string) ($row['profit'] ?? '0'));
    }
    $max = 0;
    $min = 0;
    foreach ([$sales, $expenses, $left] as $series) {
        foreach ($series as $cents) {
            $max = max($max, $cents);
            $min = min($min, $cents);
        }
    }
    if ($max === $min) {
        $max = $min + 10000;
    }
    $top = 18;
    $bottom = 248;
    $span = $bottom - $top;
    $yOf = static function (int $cents) use ($min, $max, $bottom, $span): float {
        $ratio = ($cents - $min) / ($max - $min);
        return round($bottom - ($ratio * $span), 1);
    };
    $xOf = static function (int $index): float {
        return round(52 + $index * (640 / 11), 1);
    };
    $path = static function (array $series) use ($xOf, $yOf): string {
        $parts = [];
        foreach ($series as $index => $cents) {
            $parts[] = ($index === 0 ? 'M' : 'L') . $xOf($index) . ' ' . $yOf($cents);
        }
        return implode(' ', $parts);
    };
    $zero = $yOf(0);
    $axis = '';
    foreach ([$max, (int) round(($max + $min) / 2), $min] as $mark) {
        $y = $yOf($mark);
        $axis .= '<text x="46" y="' . ($y + 4) . '">' . h(chart_axis_label($mark)) . '</text>';
        $axis .= '<line x1="52" y1="' . $y . '" x2="700" y2="' . $y . '" class="grid"/>';
    }
    $dots = '';
    foreach ([$sales, $expenses, $left] as $seriesIndex => $series) {
        $class = ['sale', 'cost', 'left'][$seriesIndex];
        foreach ($series as $index => $cents) {
            $dots .= '<circle class="' . $class . '" cx="' . $xOf($index) . '" cy="' . $yOf($cents) . '" r="3.2"/>';
        }
    }
    $current = $year === (int) date('Y') ? (int) date('n') : 12;
    $buttons = '';
    for ($month = 1; $month <= 12; $month++) {
        $row = $months[$month] ?? [];
        $recorded = from_cents(to_cents((string) ($row['transfer'] ?? '0')) + to_cents((string) ($row['cash'] ?? '0')));
        $buttons .= '<button type="button" class="' . ($month === $current ? 'on' : '') . '" data-month="' . h($names[$month - 1]) . '"'
            . ' data-sales="' . h(money_label($row['sales'] ?? '0')) . '"'
            . ' data-pos="' . h(money_label($row['pos'] ?? '0')) . '"'
            . ' data-recorded="' . h(money_label($recorded)) . '"'
            . ' data-expenses="' . h(money_label($row['expenses'] ?? '0')) . '"'
            . ' data-supplies="' . h(money_label($row['supplies'] ?? '0')) . '"'
            . ' data-salary="' . h(money_label($row['salary'] ?? '0')) . '"'
            . ' data-rent="' . h(money_label($row['rent'] ?? '0')) . '"'
            . ' data-utilities="' . h(money_label($row['utilities'] ?? '0')) . '"'
            . ' data-other="' . h(money_label($row['other'] ?? '0')) . '"'
            . ' data-left="' . h(money_label($row['profit'] ?? '0')) . '">'
            . '<span>' . h($names[$month - 1]) . '</span></button>';
    }
    return '<div class="year-chart" id="year-chart">'
        . '<div class="chart-tip"><strong data-tip="month"></strong>'
        . '<p><i class="sale"></i> Sales <b data-tip="sales"></b></p>'
        . '<p class="sub">Paid bills <b data-tip="pos"></b> · Recorded <b data-tip="recorded"></b></p>'
        . '<p><i class="cost"></i> Expenses <b data-tip="expenses"></b></p>'
        . '<p class="sub">Supplies <b data-tip="supplies"></b> · Salaries <b data-tip="salary"></b> · Rent <b data-tip="rent"></b> · Utilities <b data-tip="utilities"></b> · Other <b data-tip="other"></b></p>'
        . '<p><i class="left"></i> Left <b data-tip="left"></b></p></div>'
        . '<div class="chart-stage"><svg viewBox="0 0 720 270" role="img" aria-label="Sales, expenses, and what is left each month">'
        . $axis
        . '<line class="zero" x1="52" y1="' . $zero . '" x2="700" y2="' . $zero . '"/>'
        . '<path class="sale" d="' . h($path($sales)) . '"/>'
        . '<path class="cost" d="' . h($path($expenses)) . '"/>'
        . '<path class="left" d="' . h($path($left)) . '"/>'
        . $dots
        . '</svg><div class="chart-cols">' . $buttons . '</div></div></div>';
}

function require_local_page(): void
{
    if (is_local_request()) {
        return;
    }
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><title>Owner only</title>';
    echo '<body style="font-family: Georgia, serif; background:#e7eef6; color:#0d1b2e; padding:2rem;">';
    echo '<p>Open this POS on the cafe Wi-Fi or over Tailscale.</p>';
    echo '</body></html>';
    exit;
}

function is_manager_session(): bool
{
    return (string) ($_SESSION['role'] ?? '') === 'manager';
}

function is_superadmin_session(): bool
{
    return (string) ($_SESSION['role'] ?? '') === 'superadmin';
}

function can_manage(): bool
{
    if (is_manager_session()) {
        return false;
    }
    $role = (string) ($_SESSION['role'] ?? '');
    return $role === 'superadmin' || !empty($_SESSION['owner_ok']);
}

function supply_category_ids(): array
{
    $ids = [];
    foreach (book_categories() as $id => $name) {
        if (book_category_bucket((string) $name) === 'supplies') {
            $ids[] = (string) $id;
        }
    }
    return $ids;
}

function manager_may_post(): bool
{
    $page = basename($_SERVER['SCRIPT_NAME'] ?? '');
    if ($page === 'account.php') {
        return true;
    }
    if ($page !== 'expenses.php') {
        return false;
    }
    if ((string) ($_POST['action'] ?? '') !== '') {
        return false;
    }
    if ((int) ($_POST['id'] ?? 0) > 0) {
        return false;
    }
    return in_array((string) ($_POST['category_id'] ?? ''), supply_category_ids(), true);
}

function page_area(string $page): string
{
    $map = [
        'index.php' => 'admin.today',
        'sales.php' => 'admin.sales',
        'customers.php' => 'admin.customers',
        'items.php' => 'admin.menu',
        'categories.php' => 'admin.menu',
        'tables.php' => 'admin.floor',
        'books.php' => 'admin.books',
        'salaries.php' => 'admin.books',
        'expenses.php' => 'admin.expenses',
        'supply.php' => 'admin.expenses',
        'reports.php' => 'admin.reports',
        'hr.php' => 'admin.hours',
        'settings.php' => 'admin.settings',
        'users.php' => 'admin.users',
        'account.php' => '',
        'logout.php' => '',
    ];
    return $map[$page] ?? 'admin.today';
}

function require_owner_page(): void
{
    require_local_page();
    start_app_session();
    $role = (string) ($_SESSION['role'] ?? '');
    if (!role_opens_admin($role) && empty($_SESSION['owner_ok'])) {
        header('Location: ../login.php');
        exit;
    }
    $area = page_area(basename($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($area !== '' && !session_can($area)) {
        http_response_code(403);
        layout_header('Not open');
        echo '<p class="error">Your role cannot open that page.</p>';
        layout_footer();
        exit;
    }
}

function csrf_token(): string
{
    start_app_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function csrf_check(): void
{
    $sent = (string) ($_POST['csrf'] ?? '');
    $token = csrf_token();
    if ($sent === '' || !hash_equals($token, $sent)) {
        throw new AppError('The form expired. Reload the page and try again.');
    }
}

function flash_set(string $message): void
{
    start_app_session();
    $_SESSION['flash'] = $message;
}

function flash_take(): ?string
{
    start_app_session();
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $message = (string) $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $message;
}

function period_input(int $year, int $month): string
{
    return sprintf('%04d-%02d', $year, $month);
}

function read_period(): array
{
    $todayYear = (int) date('Y');
    $todayMonth = (int) date('n');
    $raw = trim((string) ($_REQUEST['period'] ?? ''));
    if (preg_match('/^(\d{4})-(\d{2})$/', $raw, $matches)) {
        $year = (int) $matches[1];
        $month = (int) $matches[2];
        if ($year >= 2000 && $year <= 2100 && $month >= 1 && $month <= 12) {
            return [$year, $month];
        }
    }
    $year = (int) ($_REQUEST['year'] ?? 0);
    $month = (int) ($_REQUEST['month'] ?? 0);
    if ($year >= 2000 && $year <= 2100 && $month >= 1 && $month <= 12) {
        return [$year, $month];
    }
    if ($year >= 2000 && $year <= 2100) {
        return [$year, $year === $todayYear ? $todayMonth : 1];
    }
    return [$todayYear, $todayMonth];
}

function month_order(int $year): array
{
    $order = range(1, 12);
    if ($year !== (int) date('Y')) {
        return $order;
    }
    $running = (int) date('n');
    $order = array_values(array_filter($order, static function (int $month) use ($running): bool {
        return $month !== $running;
    }));
    array_unshift($order, $running);
    return $order;
}

function month_bounds(int $year, int $month): array
{
    $start = sprintf('%04d-%02d-01', $year, $month);
    $end = (new DateTime($start))->modify('last day of this month')->format('Y-m-d');
    return [$start, $end];
}

function owner_cafe_name(): string
{
    try {
        $name = trim((string) (settings()['cafe_name'] ?? ''));
        return $name !== '' ? $name : 'Ithaa Corner';
    } catch (Throwable $error) {
        return 'Ithaa Corner';
    }
}

function owner_nav(): array
{
    $nav = [
        'Register' => [
            'index.php' => 'Dashboard',
            'sales.php' => 'Sales',
            'customers.php' => 'Customers',
        ],
        'Menu' => [
            'items.php' => 'Items',
            'categories.php' => 'Categories',
        ],
        'Floor' => [
            'tables.php' => 'Tables',
        ],
        'Cafe' => [
            'users.php' => 'Users',
            'account.php' => 'Account',
            'hr.php' => 'Hours',
            'settings.php' => 'Settings',
        ],
        'Books' => [
            'books.php' => 'Books',
            'salaries.php' => 'Salaries',
            'expenses.php' => 'Expenses',
            'supply.php' => 'Supplies',
            'reports.php' => 'Reports',
        ],
    ];
    $open = [];
    foreach ($nav as $group => $links) {
        $kept = [];
        foreach ($links as $href => $label) {
            $area = page_area($href);
            if ($area === '' || session_can($area)) {
                $kept[$href] = $label;
            }
        }
        if ($kept !== []) {
            $open[$group] = $kept;
        }
    }
    return $open;
}

function layout_header(string $title, bool $showNav = true, string $bodyClass = ''): void
{
    global $ownerNavOpen;
    $ownerNavOpen = $showNav;
    $flash = flash_take();
    $page = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $cafe = owner_cafe_name();
    $section = '';
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
    echo '<meta name="theme-color" content="#e6e6e6">';
    echo '<meta name="mobile-web-app-capable" content="yes">';
    echo '<meta name="apple-mobile-web-app-capable" content="yes">';
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';
    echo '<meta name="apple-mobile-web-app-title" content="Ithaa">';
    echo '<title>' . h($title . ' · ' . $cafe) . '</title>';
    echo '<link rel="manifest" href="../manifest.webmanifest">';
    echo '<link rel="icon" href="../icon-192.png">';
    echo '<link rel="apple-touch-icon" href="../apple-touch-icon.png">';
    echo '<link rel="stylesheet" href="style.css?v=31">';
    echo '<link rel="stylesheet" href="mobile.css?v=7" media="(max-width: 860px)">';
    echo '<script src="../pwa.js" defer></script>';
    echo '</head>';
    if (!$showNav) {
        echo '<body class="login ' . h($bodyClass) . '"><main>';
        if ($flash !== null) {
            echo '<p class="flash">' . h($flash) . '</p>';
        }
        return;
    }
    echo '<body class="' . h($bodyClass) . '">';
    echo '<input class="nav-check" id="nav-open" type="checkbox">';
    echo '<div class="shell"><label class="nav-shade" for="nav-open" aria-hidden="true"></label><aside class="side">';
    $roleName = role_label((string) ($_SESSION['role'] ?? ''));
    echo '<div class="brand"><a href="index.php">' . h($cafe) . '</a><span>' . h($roleName !== '' ? $roleName : 'Admin') . '</span>';
    if (!empty($_SESSION['user_name'])) {
        echo '<span class="who">' . h((string) $_SESSION['user_name']) . '</span>';
    }
    echo '</div><nav>';
    $nav = owner_nav();
    $here = '';
    foreach ($nav as $group => $links) {
        if (isset($links[$page])) {
            $here = $group;
        }
    }
    $section = $here;
    foreach ($nav as $group => $links) {
        echo '<section class="' . ($group === $here ? 'here' : 'quiet') . '"><h2>' . h($group) . '</h2>';
        foreach ($links as $href => $label) {
            echo '<a' . ($page === $href ? ' class="current"' : '') . ' href="' . h($href) . '">' . h($label) . '</a>';
        }
        echo '</section>';
    }
    echo '</nav><button type="button" class="logout" id="pwa-install" hidden>Install app</button>';
    echo '<a class="logout" href="logout.php">Log out</a></aside>';
    echo '<div class="workspace"><div class="bar">';
    echo '<label class="menu-btn" for="nav-open">Menu</label>';
    if ($section !== '') {
        echo '<p class="kicker">' . h($section) . '</p>';
    }
    echo '<p class="today">' . h(format_date(date('Y-m-d'))) . '</p>';
    echo '</div><main>';
    if ($flash !== null) {
        echo '<p class="flash">' . h($flash) . '</p>';
    }
}

function layout_footer(): void
{
    global $ownerNavOpen;
    echo '</main>';
    if (!empty($ownerNavOpen)) {
        echo '</div></div>';
    }
    admin_script();
    echo '</body></html>';
}

function table_short(string $name, string $areaName = ''): string
{
    return table_seat_label($name, $areaName);
}

function floor_groups(array $tables, bool $onlyBusy = false): array
{
    $groups = [];
    foreach ($tables as $table) {
        $busy = !empty($table['order_id']);
        if ($onlyBusy && !$busy) {
            continue;
        }
        $key = !empty($table['area_id']) ? 'a' . (int) $table['area_id'] : 'other';
        if (!isset($groups[$key])) {
            $groups[$key] = [
                'name' => !empty($table['area_name']) ? (string) $table['area_name'] : 'Other',
                'sort' => !empty($table['area_id']) ? (int) ($table['area_sort'] ?? 0) : 100000,
                'tables' => [],
            ];
        }
        $groups[$key]['tables'][] = $table;
    }
    $list = array_values($groups);
    usort($list, static function (array $a, array $b): int {
        if ($a['sort'] !== $b['sort']) {
            return $a['sort'] <=> $b['sort'];
        }
        return strcasecmp($a['name'], $b['name']);
    });
    return $list;
}

function page_head(string $title, string $lead = '', string $extra = ''): void
{
    echo '<header class="pagehead"><div><h1>' . h($title) . '</h1>';
    if ($lead !== '') {
        echo '<p class="lead">' . $lead . '</p>';
    }
    echo '</div>' . $extra . '</header>';
}

function render_floor(array $tables, bool $onlyBusy = false): void
{
    $groups = floor_groups($tables, $onlyBusy);
    $any = false;
    echo '<div class="areas">';
    foreach ($groups as $group) {
        $list = $group['tables'];
        if ($list === []) {
            continue;
        }
        $any = true;
        $open = 0;
        foreach ($list as $table) {
            if (!empty($table['order_id'])) {
                $open++;
            }
        }
        echo '<section class="area"><div class="area-head"><h2>' . h($group['name']) . '</h2><span class="area-count">' . count($list) . '</span></div>';
        if ($open > 0) {
            echo '<p class="area-note">' . $open . ' open</p>';
        }
        echo '<div class="floor-grid">';
        foreach ($list as $table) {
            $busy = !empty($table['order_id']);
            $hidden = array_key_exists('active', $table) && empty($table['active']);
            echo '<article class="seat' . ($busy ? ' open' : '') . ($hidden ? ' hidden-seat' : '') . '">';
            echo '<strong>' . h(table_short((string) $table['name'], (string) ($table['area_name'] ?? ''))) . '</strong>';
            echo '<span>' . h((string) $table['name']) . '</span>';
            if ($busy) {
                $meta = [];
                if (isset($table['total'])) {
                    $meta[] = money_label((string) $table['total']);
                }
                if (!empty($table['item_count'])) {
                    $meta[] = (int) $table['item_count'] . ' items';
                }
                if (!empty($table['waiter_name'])) {
                    $meta[] = (string) $table['waiter_name'];
                }
                echo '<em>' . h($meta === [] ? 'Open' : implode(' · ', $meta)) . '</em>';
            } else {
                echo '<em>' . ($hidden ? 'Hidden' : 'Free') . '</em>';
            }
            echo '</article>';
        }
        echo '</div></section>';
    }
    echo '</div>';
    if (!$any) {
        echo '<p class="empty">' . ($onlyBusy ? 'No open tables right now.' : 'No tables yet.') . '</p>';
    }
}

function admin_script(): void
{
    echo <<<'HTML'
<script>
(function () {
  function applyFilters() {
    document.querySelectorAll('[data-list]').forEach(function (list) {
      var find = document.querySelector('[data-find="#' + list.id + '"]');
      var query = find ? find.value.trim().toLowerCase() : '';
      var pay = list.getAttribute('data-pay') || 'all';
      var cat = list.getAttribute('data-cat') || '';
      var state = list.getAttribute('data-state') || '';
      var shown = 0;
      list.querySelectorAll('[data-text]').forEach(function (row) {
        var text = (row.getAttribute('data-text') || '').toLowerCase();
        var method = row.getAttribute('data-pay') || '';
        var kind = row.getAttribute('data-kind') || '';
        var rowCat = row.getAttribute('data-cat') || '';
        var rowState = row.getAttribute('data-state') || '';
        var payMiss = false;
        if (pay === 'credit') {
          payMiss = method !== 'credit' && kind !== 'held';
        } else if (pay !== 'all') {
          payMiss = method !== pay;
        }
        var hide = (query && text.indexOf(query) === -1) || payMiss || (cat && rowCat !== cat) || (state && rowState !== state);
        row.hidden = hide;
        if (!hide) shown += 1;
      });
      list.querySelectorAll('[data-group]').forEach(function (group) {
        group.hidden = group.querySelectorAll('[data-text]:not([hidden])').length === 0;
      });
      var none = document.querySelector('[data-none="#' + list.id + '"]');
      if (none) none.hidden = shown !== 0;
    });
  }
  document.querySelectorAll('[data-find]').forEach(function (input) {
    input.addEventListener('input', applyFilters);
  });
  document.querySelectorAll('[data-pay-set]').forEach(function (button) {
    button.addEventListener('click', function () {
      var list = document.querySelector(button.getAttribute('data-pay-set'));
      if (!list) return;
      list.setAttribute('data-pay', button.getAttribute('data-value') || 'all');
      var group = button.parentElement;
      if (group) {
        group.querySelectorAll('button').forEach(function (other) {
          other.classList.toggle('current', other === button);
        });
      }
      applyFilters();
    });
  });
  document.querySelectorAll('[data-cat-set]').forEach(function (select) {
    select.addEventListener('change', function () {
      var list = document.querySelector(select.getAttribute('data-cat-set'));
      if (!list) return;
      list.setAttribute('data-cat', select.value);
      applyFilters();
    });
  });
  document.querySelectorAll('[data-cat-btn], [data-state-set]').forEach(function (button) {
    button.addEventListener('click', function () {
      var list = document.querySelector(button.getAttribute('data-cat-btn') || button.getAttribute('data-state-set'));
      if (!list) return;
      if (button.hasAttribute('data-cat-btn')) {
        list.setAttribute('data-cat', button.getAttribute('data-value') || '');
      } else {
        list.setAttribute('data-state', button.getAttribute('data-value') || '');
      }
      var group = button.parentElement;
      if (group) {
        group.querySelectorAll('button').forEach(function (other) {
          other.classList.toggle('current', other === button);
        });
      }
      applyFilters();
    });
  });
  applyFilters();
})();
</script>
HTML;
}

function redirect_to(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function bill_number(int $id): string
{
    return str_pad((string) $id, 4, '0', STR_PAD_LEFT);
}

function bill_pay_word(string $method): string
{
    if ($method === 'online') {
        return 'BANK';
    }
    if ($method === 'credit' || $method === 'card') {
        return 'CREDIT/HOLD';
    }
    return 'CASH';
}

function bill_day_label(string $value): string
{
    $label = format_date(substr($value, 0, 10));
    return $label !== '' ? $label : $value;
}

function sale_bill_html(array $sale, string $cafe): string
{
    $id = (int) ($sale['id'] ?? 0);
    $serial = bill_number($id);
    $reference = trim((string) ($sale['settlement_reference'] ?? ''));
    $lines = $sale['lines'] ?? [];
    $groups = [];
    $pieces = 0;
    foreach ($lines as $line) {
        $pieces += (int) $line['qty'];
        $day = substr((string) ($line['added_at'] ?? ''), 0, 10);
        if ($day === '') {
            $day = substr((string) ($sale['opened_at'] ?? ''), 0, 10);
        }
        $groups[$day][] = $line;
    }
    $rows = '';
    foreach ($groups as $day => $group) {
        if ($day !== '') {
            $rows .= '<tr class="day"><td colspan="4">Added ' . h(bill_day_label($day)) . '</td></tr>';
        }
        foreach ($group as $line) {
            $rows .= '<tr><td>' . h((string) $line['item_name']) . '</td><td class="qty">' . (int) $line['qty']
                . '</td><td class="rate">' . h(money_label($line['unit_price'])) . '</td><td class="amt">'
                . h(money_label($line['line_total'])) . '</td></tr>';
        }
    }
    $paid = trim((string) ($sale['closed_at'] ?? ''));
    $meta = [
        ['Table', (string) ($sale['table_name'] ?? '')],
        ['Opened by', (string) ($sale['waiter_name'] ?? '')],
        ['Opened', format_when((string) ($sale['opened_at'] ?? ''))],
        ['Paid', $paid !== '' ? format_when($paid) : ''],
        ['Payment', bill_pay_word((string) ($sale['payment_method'] ?? 'cash'))],
        ['Items', (string) $pieces],
    ];
    if (trim((string) ($sale['customer_name'] ?? '')) !== '') {
        $meta[] = ['Customer', (string) $sale['customer_name']];
    }
    $metaHtml = '';
    foreach ($meta as $pair) {
        if ($pair[1] === '') {
            continue;
        }
        $metaHtml .= '<div><span>' . h($pair[0]) . '</span><strong>' . h($pair[1]) . '</strong></div>';
    }
    $sub = $reference !== '' ? '<p class="bill-sub">Order ' . h($serial) . '</p>' : '';
    $number = $reference !== '' ? $reference : $serial;
    return '<article class="paper">'
        . '<p class="bill-kicker">Bill</p>'
        . '<h2>' . h($cafe) . '</h2>'
        . '<p class="bill-no">Bill no. ' . h($number) . '</p>'
        . $sub
        . '<div class="bill-meta">' . $metaHtml . '</div>'
        . '<table><thead><tr><th>Item</th><th>Qty</th><th>Rate</th><th>Amount</th></tr></thead><tbody>'
        . $rows
        . '</tbody></table>'
        . '<p class="bill-total"><span>Total</span><strong>' . h(money_label($sale['total'] ?? '0')) . '</strong></p>'
        . '<p class="bill-pay">' . h(bill_pay_word((string) ($sale['payment_method'] ?? 'cash'))) . ' ' . h(money_label($sale['total'] ?? '0')) . '</p>'
        . '<p class="bill-thanks">Thank you</p>'
        . (bank_image_file() !== '' ? '<img class="pay-qr" alt="" src="media.php?bank=1">' : '')
        . '</article>';
}

function sale_slip(array $sale): string
{
    if (($sale['payment_method'] ?? '') !== 'online') {
        return '';
    }
    if (empty($sale['has_slip'])) {
        return '<p class="off">No payment slip</p>';
    }
    $url = 'media.php?slip=' . (int) $sale['id'];
    return '<a class="slip" href="' . h($url) . '" target="_blank" rel="noopener"><img src="' . h($url) . '" alt="Payment slip"></a>';
}
