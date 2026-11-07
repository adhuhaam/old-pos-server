<?php

function book_months(): array
{
    return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
}

function book_categories(): array
{
    $rows = db()->query('SELECT id, name FROM book_categories WHERE active = 1 ORDER BY sort_order, name')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[(string) $row['id']] = $row['name'];
    }
    return $out;
}

function book_category_bucket(string $name): string
{
    $key = strtolower(trim($name));
    if ($key === 'supplies' || $key === 'supply') {
        return 'supplies';
    }
    if ($key === 'rent') {
        return 'rent';
    }
    if ($key === 'salary' || $key === 'salaries' || str_starts_with($key, 'salar')) {
        return 'salary';
    }
    if ($key === 'utilities' || $key === 'utility' || $key === 'utility bills') {
        return 'utilities';
    }
    return 'other';
}

function month_has_started(int $year, int $month): bool
{
    return sprintf('%04d-%02d-01', $year, $month) <= date('Y-m-d');
}

function book_amount(string $raw): string
{
    $cents = to_cents(trim($raw));
    if ($cents < 0 || $cents > 99999999) {
        throw new AppError('Amount must be from 0.00 to 999999.99.');
    }
    return from_cents($cents);
}

function ensure_books(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_partners (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(80) NOT NULL,
            share_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
            sort_order INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_days (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            sale_date DATE NOT NULL,
            transfer DECIMAL(10,2) NOT NULL DEFAULT 0,
            cash DECIMAL(10,2) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY book_days_date (sale_date)
        ) ENGINE=InnoDB'
    );
    if (!column_exists($pdo, 'book_days', 'origin')) {
        $pdo->exec("ALTER TABLE book_days ADD COLUMN origin VARCHAR(20) NOT NULL DEFAULT ''");
    }
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_people (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(80) NOT NULL,
            designation VARCHAR(80) NOT NULL DEFAULT "",
            PRIMARY KEY (id)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_salaries (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            person_id INT UNSIGNED NOT NULL,
            year_num SMALLINT NOT NULL,
            month_num TINYINT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY book_salary_month (person_id, year_num, month_num)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_expenses (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            spent_on DATE NOT NULL,
            category VARCHAR(20) NOT NULL,
            kind VARCHAR(40) NOT NULL DEFAULT "",
            amount DECIMAL(10,2) NOT NULL,
            note VARCHAR(160) NOT NULL DEFAULT "",
            PRIMARY KEY (id),
            KEY book_expenses_date (spent_on)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_recurring (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            category_id INT UNSIGNED NOT NULL,
            kind VARCHAR(40) NOT NULL DEFAULT "",
            amount DECIMAL(10,2) NOT NULL,
            note VARCHAR(160) NOT NULL DEFAULT "",
            PRIMARY KEY (id),
            KEY book_recurring_category (category_id)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_recurring_months (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            recurring_id INT UNSIGNED NOT NULL,
            year_num SMALLINT NOT NULL,
            month_num TINYINT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY book_recurring_month (recurring_id, year_num, month_num)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS book_categories (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(80) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            active TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (id),
            UNIQUE KEY book_categories_name (name)
        ) ENGINE=InnoDB'
    );
    if (column_exists($pdo, 'book_expenses', 'category')) {
        $pdo->exec('ALTER TABLE book_expenses MODIFY category VARCHAR(80) NOT NULL');
    }
    if (!column_exists($pdo, 'book_expenses', 'category_id')) {
        $pdo->exec('ALTER TABLE book_expenses ADD COLUMN category_id INT UNSIGNED NULL');
    }
    if (!column_exists($pdo, 'book_expenses', 'proof')) {
        $pdo->exec("ALTER TABLE book_expenses ADD COLUMN proof VARCHAR(80) NOT NULL DEFAULT ''");
    }
    $names = ['Supply' => 'supplies', 'Rent' => 'rent', 'Utility bills' => 'utilities', 'Other' => 'other'];
    $sort = 1;
    $insertCategory = $pdo->prepare('INSERT INTO book_categories (name, sort_order, active) VALUES (?, ?, 1)');
    $findCategory = $pdo->prepare('SELECT id FROM book_categories WHERE name = ?');
    foreach ($names as $name => $old) {
        $findCategory->execute([$name]);
        if (!$findCategory->fetch()) {
            $insertCategory->execute([$name, $sort]);
        }
        $sort++;
    }
    $link = $pdo->prepare(
        'UPDATE book_expenses e
         JOIN book_categories c ON c.name = ?
         SET e.category_id = c.id
         WHERE e.category_id IS NULL AND e.category IN (?, ?)'
    );
    $link->execute(['Supply', 'supplies', 'Supply']);
    $link->execute(['Rent', 'rent', 'Rent']);
    $link->execute(['Utility bills', 'utilities', 'Utility bills']);
    $link->execute(['Other', 'other', 'Other']);

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS supply_items (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(80) NOT NULL,
            unit VARCHAR(20) NOT NULL DEFAULT "",
            active TINYINT(1) NOT NULL DEFAULT 1,
            sort_order INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS supply_weeks (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            week_start DATE NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT "requested",
            requested_by INT UNSIGNED NULL,
            quoted_by INT UNSIGNED NULL,
            accepted_by INT UNSIGNED NULL,
            expense_id INT UNSIGNED NULL,
            requested_at DATETIME NULL,
            quoted_at DATETIME NULL,
            accepted_at DATETIME NULL,
            PRIMARY KEY (id),
            UNIQUE KEY supply_weeks_start (week_start)
        ) ENGINE=InnoDB'
    );
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS supply_lines (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            week_id INT UNSIGNED NOT NULL,
            item_id INT UNSIGNED NOT NULL,
            requested_qty DECIMAL(10,2) NOT NULL DEFAULT 0,
            quote_qty DECIMAL(10,2) NULL,
            quote_price DECIMAL(10,2) NULL,
            PRIMARY KEY (id),
            UNIQUE KEY supply_lines_item (week_id, item_id)
        ) ENGINE=InnoDB'
    );

    $count = (int) $pdo->query('SELECT COUNT(*) FROM book_partners')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $pdo->beginTransaction();
    try {
        $partner = $pdo->prepare('INSERT INTO book_partners (name, share_percent, sort_order) VALUES (?, ?, ?)');
        $shares = [
            ['Adhuham', '40.00', 1],
            ['Nooradhen', '10.00', 2],
            ['Zainab', '10.00', 3],
            ['Naura', '10.00', 4],
            ['Mifzaal', '10.00', 5],
            ['Mamnoon', '10.00', 6],
            ['Maain', '10.00', 7],
        ];
        foreach ($shares as $row) {
            $partner->execute($row);
        }

        $day = $pdo->prepare('INSERT INTO book_days (sale_date, transfer, cash) VALUES (?, ?, ?)');
        foreach (book_sheet_days() as $row) {
            $day->execute($row);
        }

        $person = $pdo->prepare('INSERT INTO book_people (name, designation) VALUES (?, ?)');
        $person->execute(['Suman', 'Waiter']);
        $suman = (int) $pdo->lastInsertId();
        $person->execute(['Waiter 2', 'Waiter']);
        $person->execute(['Cook', 'Cook']);
        $person->execute(['Manager', 'Manager']);
        $salary = $pdo->prepare('INSERT INTO book_salaries (person_id, year_num, month_num, amount) VALUES (?, ?, ?, ?)');
        $salary->execute([$suman, 2026, 8, '6000.00']);
        $salary->execute([$suman, 2026, 9, '8000.00']);

        $expense = $pdo->prepare('INSERT INTO book_expenses (spent_on, category, kind, amount, note) VALUES (?, ?, ?, ?, ?)');
        $expense->execute(['2026-08-01', 'rent', '', '3500.00', 'From the sales sheet']);
        foreach (
            [
                ['2026-03-01', '42.97'],
                ['2026-04-01', '41.69'],
                ['2026-05-01', '42.97'],
                ['2026-06-01', '42.97'],
                ['2026-07-01', '42.97'],
                ['2026-08-01', '2254.84'],
                ['2026-09-01', '1046.33'],
            ] as $bill
        ) {
            $expense->execute([$bill[0], 'utilities', 'Electricity', $bill[1], 'From the sales sheet']);
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
}

function book_sheet_days(): array
{
    return [
        ['2026-07-26', '34.00', '0.00'],
        ['2026-07-27', '194.00', '0.00'],
        ['2026-07-28', '377.00', '0.00'],
        ['2026-07-29', '659.00', '0.00'],
        ['2026-07-30', '605.00', '0.00'],
        ['2026-07-31', '696.00', '0.00'],
        ['2026-08-01', '840.00', '0.00'],
        ['2026-08-02', '678.00', '0.00'],
        ['2026-08-03', '832.00', '0.00'],
        ['2026-08-04', '786.00', '0.00'],
        ['2026-08-05', '968.00', '0.00'],
        ['2026-08-06', '674.00', '0.00'],
        ['2026-08-07', '547.00', '0.00'],
        ['2026-08-08', '780.00', '0.00'],
        ['2026-08-09', '780.00', '0.00'],
        ['2026-08-10', '736.00', '0.00'],
        ['2026-08-11', '625.00', '0.00'],
        ['2026-08-12', '998.00', '0.00'],
        ['2026-08-13', '895.00', '0.00'],
        ['2026-08-14', '647.00', '0.00'],
        ['2026-08-15', '946.00', '0.00'],
        ['2026-08-16', '1204.00', '0.00'],
        ['2026-08-17', '1166.00', '0.00'],
        ['2026-08-18', '1136.00', '0.00'],
        ['2026-08-19', '1266.00', '0.00'],
        ['2026-08-20', '1219.00', '0.00'],
        ['2026-08-21', '851.00', '0.00'],
        ['2026-08-22', '1234.00', '0.00'],
        ['2026-08-23', '1285.00', '0.00'],
        ['2026-08-24', '847.00', '0.00'],
        ['2026-08-25', '2163.00', '0.00'],
        ['2026-08-26', '2763.00', '0.00'],
        ['2026-08-27', '1959.00', '0.00'],
        ['2026-08-28', '1134.00', '0.00'],
        ['2026-08-29', '1643.00', '0.00'],
        ['2026-08-30', '1522.00', '0.00'],
        ['2026-08-31', '2003.00', '0.00'],
        ['2026-09-01', '1926.00', '0.00'],
        ['2026-09-02', '2029.00', '0.00'],
        ['2026-09-03', '1904.00', '0.00'],
        ['2026-09-04', '1525.00', '0.00'],
        ['2026-09-05', '2939.00', '0.00'],
        ['2026-09-06', '1438.00', '0.00'],
        ['2026-09-07', '1801.00', '0.00'],
        ['2026-09-08', '1991.00', '0.00'],
        ['2026-09-09', '1563.00', '0.00'],
        ['2026-09-10', '1812.00', '0.00'],
        ['2026-09-11', '855.00', '0.00'],
        ['2026-09-12', '1618.00', '0.00'],
        ['2026-09-13', '1736.00', '0.00'],
        ['2026-09-14', '1515.00', '0.00'],
        ['2026-09-15', '1632.00', '0.00'],
        ['2026-09-16', '1486.00', '0.00'],
        ['2026-09-17', '1208.00', '0.00'],
        ['2026-09-18', '589.00', '0.00'],
        ['2026-09-19', '1914.00', '0.00'],
        ['2026-09-20', '1346.00', '0.00'],
        ['2026-09-21', '1748.00', '0.00'],
        ['2026-09-22', '1057.00', '0.00'],
        ['2026-09-23', '1361.00', '0.00'],
        ['2026-09-24', '1335.00', '0.00'],
        ['2026-09-25', '603.00', '0.00'],
        ['2026-09-26', '1284.00', '0.00'],
        ['2026-09-27', '1448.00', '0.00'],
        ['2026-09-28', '1731.00', '0.00'],
        ['2026-09-29', '1729.00', '0.00'],
        ['2026-09-30', '2207.00', '0.00'],
        ['2026-10-01', '3216.00', '0.00'],
        ['2026-10-02', '940.00', '0.00'],
        ['2026-10-03', '1307.00', '0.00'],
        ['2026-10-04', '1691.00', '0.00'],
        ['2026-10-05', '1677.00', '0.00'],
        ['2026-10-06', '2162.00', '0.00'],
        ['2026-10-07', '1448.00', '714.00'],
        ['2026-10-08', '968.00', '467.00'],
        ['2026-10-09', '210.00', '365.00'],
        ['2026-10-10', '722.00', '624.00'],
        ['2026-10-11', '733.00', '500.00'],
    ];
}

function book_year_report(int $year): array
{
    if ($year < 2000 || $year > 2100) {
        throw new AppError('Choose a year.');
    }
    $blank = static function (): array {
        return [
            'pos' => 0,
            'transfer' => 0,
            'cash' => 0,
            'supplies' => 0,
            'salary' => 0,
            'rent' => 0,
            'utilities' => 0,
            'other' => 0,
        ];
    };
    $months = [];
    for ($month = 1; $month <= 12; $month++) {
        $months[$month] = $blank();
    }

    $pdo = db();
    $pos = $pdo->prepare(
        'SELECT MONTH(closed_at) AS month_num, SUM(total) AS total
         FROM sales
         WHERE status = "paid" AND payment_method IN ("cash", "online") AND YEAR(closed_at) = ?
         GROUP BY MONTH(closed_at)'
    );
    $pos->execute([$year]);
    foreach ($pos as $row) {
        $months[(int) $row['month_num']]['pos'] = to_cents((string) $row['total']);
    }

    $days = $pdo->prepare(
        'SELECT MONTH(sale_date) AS month_num, SUM(transfer) AS transfer, SUM(cash) AS cash
         FROM book_days
         WHERE YEAR(sale_date) = ?
         GROUP BY MONTH(sale_date)'
    );
    $days->execute([$year]);
    foreach ($days as $row) {
        $months[(int) $row['month_num']]['transfer'] = to_cents((string) $row['transfer']);
        $months[(int) $row['month_num']]['cash'] = to_cents((string) $row['cash']);
    }

    $pay = $pdo->prepare(
        'SELECT month_num, SUM(amount) AS total
         FROM book_salaries
         WHERE year_num = ?
         GROUP BY month_num'
    );
    $pay->execute([$year]);
    foreach ($pay as $row) {
        $months[(int) $row['month_num']]['salary'] = to_cents((string) $row['total']);
    }

    $bills = $pdo->prepare(
        'SELECT MONTH(e.spent_on) AS month_num, COALESCE(c.name, e.category) AS category_name, SUM(e.amount) AS total
         FROM book_expenses e
         LEFT JOIN book_categories c ON c.id = e.category_id
         WHERE YEAR(e.spent_on) = ?
         GROUP BY MONTH(e.spent_on), category_name'
    );
    $bills->execute([$year]);
    foreach ($bills as $row) {
        $category = book_category_bucket((string) $row['category_name']);
        $months[(int) $row['month_num']][$category] += to_cents((string) $row['total']);
    }

    $totals = $blank();
    $outMonths = [];
    foreach ($months as $month => $row) {
        foreach (book_recurring_for_month($year, $month) as $line) {
            $row[$line['bucket']] += $line['cents'];
        }
        $sales = $row['pos'] + $row['transfer'] + $row['cash'];
        $expenses = $row['supplies'] + $row['salary'] + $row['rent'] + $row['utilities'] + $row['other'];
        $outMonths[$month] = [
            'pos' => from_cents($row['pos']),
            'transfer' => from_cents($row['transfer']),
            'cash' => from_cents($row['cash']),
            'sales' => from_cents($sales),
            'supplies' => from_cents($row['supplies']),
            'salary' => from_cents($row['salary']),
            'rent' => from_cents($row['rent']),
            'utilities' => from_cents($row['utilities']),
            'other' => from_cents($row['other']),
            'expenses' => from_cents($expenses),
            'profit' => from_cents($sales - $expenses),
        ];
        foreach ($row as $key => $cents) {
            $totals[$key] += $cents;
        }
    }
    $sales = $totals['pos'] + $totals['transfer'] + $totals['cash'];
    $expenses = $totals['supplies'] + $totals['salary'] + $totals['rent'] + $totals['utilities'] + $totals['other'];
    $profit = $sales - $expenses;
    $partners = [];
    $shareTotal = 0;
    $assigned = 0;
    $shares = [];
    foreach ($pdo->query('SELECT id, name, share_percent FROM book_partners ORDER BY sort_order, name') as $partner) {
        $percent = (float) $partner['share_percent'];
        $shareTotal += $percent;
        $share = intdiv($profit * (int) round($percent * 100), 10000);
        $assigned += $share;
        $shares[] = $share;
        $partners[] = [
            'id' => (int) $partner['id'],
            'name' => $partner['name'],
            'share_percent' => number_format($percent, 2, '.', ''),
            'share' => '0.00',
        ];
    }
    if ($partners && abs($shareTotal - 100) < 0.001) {
        $shares[0] += $profit - $assigned;
    }
    foreach ($partners as $index => $partner) {
        $partners[$index]['share'] = from_cents($shares[$index]);
    }

    return [
        'year' => $year,
        'months' => $outMonths,
        'partners' => $partners,
        'share_total' => number_format($shareTotal, 2, '.', ''),
        'totals' => [
            'pos' => from_cents($totals['pos']),
            'transfer' => from_cents($totals['transfer']),
            'cash' => from_cents($totals['cash']),
            'sales' => from_cents($sales),
            'expenses' => from_cents($expenses),
            'salary' => from_cents($totals['salary']),
            'profit' => from_cents($profit),
        ],
    ];
}

function book_days_for_month(int $year, int $month): array
{
    $stmt = db()->prepare(
        'SELECT id, sale_date, transfer, cash, origin
         FROM book_days
         WHERE YEAR(sale_date) = ? AND MONTH(sale_date) = ?
         ORDER BY sale_date'
    );
    $stmt->execute([$year, $month]);
    return $stmt->fetchAll();
}

function book_day_row(string $date): ?array
{
    $date = parse_date($date);
    $stmt = db()->prepare('SELECT sale_date, transfer, cash FROM book_days WHERE sale_date = ?');
    $stmt->execute([$date]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function book_save_day(string $date, string $transfer, string $cash): void
{
    $date = parse_date($date);
    $transferAmount = book_amount($transfer === '' ? '0' : $transfer);
    $cashAmount = book_amount($cash === '' ? '0' : $cash);
    $pdo = db();
    if (to_cents($transferAmount) === 0 && to_cents($cashAmount) === 0) {
        $pdo->prepare('DELETE FROM book_days WHERE sale_date = ?')->execute([$date]);
        return;
    }
    $pdo->prepare(
        'INSERT INTO book_days (sale_date, transfer, cash) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE transfer = VALUES(transfer), cash = VALUES(cash)'
    )->execute([$date, $transferAmount, $cashAmount]);
}

function book_save_partner(?int $id, string $name, string $percent): void
{
    $name = trim($name);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Partner name must be 1 to 80 characters.');
    }
    if (!preg_match('/^\d{1,3}(?:\.\d{1,2})?$/', trim($percent))) {
        throw new AppError('Share must be a percent like 10 or 40.');
    }
    $share = (float) $percent;
    if ($share < 0 || $share > 100) {
        throw new AppError('Share must be from 0 to 100.');
    }
    $shareText = number_format($share, 2, '.', '');
    $pdo = db();
    if ($id === null) {
        $sort = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM book_partners')->fetchColumn();
        $pdo->prepare('INSERT INTO book_partners (name, share_percent, sort_order) VALUES (?, ?, ?)')->execute([$name, $shareText, $sort]);
        return;
    }
    $pdo->prepare('UPDATE book_partners SET name = ?, share_percent = ? WHERE id = ?')->execute([$name, $shareText, $id]);
}

function book_delete_partner(int $id): void
{
    db()->prepare('DELETE FROM book_partners WHERE id = ?')->execute([$id]);
}

function book_people(): array
{
    return db()->query('SELECT id, name, designation FROM book_people ORDER BY name')->fetchAll();
}

function book_salary_grid(int $year): array
{
    $people = book_people();
    $amounts = [];
    $stmt = db()->prepare('SELECT person_id, month_num, amount FROM book_salaries WHERE year_num = ?');
    $stmt->execute([$year]);
    foreach ($stmt as $row) {
        $amounts[(int) $row['person_id']][(int) $row['month_num']] = $row['amount'];
    }
    foreach ($people as &$person) {
        $person['months'] = [];
        for ($month = 1; $month <= 12; $month++) {
            $person['months'][$month] = $amounts[(int) $person['id']][$month] ?? '';
        }
    }
    unset($person);
    return $people;
}

function book_save_person(string $name, string $designation, ?int $id = null): void
{
    $name = trim($name);
    $designation = trim($designation);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Name must be 1 to 80 characters.');
    }
    if (strlen($designation) > 80) {
        throw new AppError('Designation must be 80 characters or less.');
    }
    $pdo = db();
    if ($id !== null && $id > 0) {
        $check = $pdo->prepare('SELECT id FROM book_people WHERE id = ?');
        $check->execute([$id]);
        if (!$check->fetch()) {
            throw new AppError('That person was not found.', 404);
        }
        $pdo->prepare('UPDATE book_people SET name = ?, designation = ? WHERE id = ?')->execute([$name, $designation, $id]);
        return;
    }
    $pdo->prepare('INSERT INTO book_people (name, designation) VALUES (?, ?)')->execute([$name, $designation]);
}

function book_delete_person(int $id): void
{
    $pdo = db();
    $pdo->prepare('DELETE FROM book_salaries WHERE person_id = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM book_people WHERE id = ?')->execute([$id]);
}

function book_save_salary(int $personId, int $year, array $amounts): void
{
    $pdo = db();
    $check = $pdo->prepare('SELECT id FROM book_people WHERE id = ?');
    $check->execute([$personId]);
    if (!$check->fetch()) {
        throw new AppError('That person was not found.', 404);
    }
    $delete = $pdo->prepare('DELETE FROM book_salaries WHERE person_id = ? AND year_num = ? AND month_num = ?');
    $insert = $pdo->prepare('INSERT INTO book_salaries (person_id, year_num, month_num, amount) VALUES (?, ?, ?, ?)');
    for ($month = 1; $month <= 12; $month++) {
        $raw = trim((string) ($amounts[$month] ?? ''));
        $delete->execute([$personId, $year, $month]);
        if ($raw === '') {
            continue;
        }
        $amount = book_amount($raw);
        if (to_cents($amount) === 0) {
            continue;
        }
        $insert->execute([$personId, $year, $month, $amount]);
    }
}

function book_put_month_salary(int $personId, int $year, int $month, string $raw): void
{
    if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
        throw new AppError('Choose a real month.');
    }
    $pdo = db();
    $check = $pdo->prepare('SELECT id FROM book_people WHERE id = ?');
    $check->execute([$personId]);
    if (!$check->fetch()) {
        throw new AppError('That person was not found.', 404);
    }
    $raw = trim($raw);
    $amount = $raw === '' ? '0.00' : book_amount($raw);
    if (to_cents($amount) === 0) {
        $pdo->prepare('DELETE FROM book_salaries WHERE person_id = ? AND year_num = ? AND month_num = ?')->execute([$personId, $year, $month]);
        return;
    }
    $pdo->prepare(
        'INSERT INTO book_salaries (person_id, year_num, month_num, amount) VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE amount = VALUES(amount)'
    )->execute([$personId, $year, $month, $amount]);
}

function book_expenses(int $year, string $categoryId, int $month = 0): array
{
    $sql = 'SELECT e.id, e.spent_on, e.category, e.kind, e.amount, e.note, e.category_id, e.proof,
                   COALESCE(c.name, e.category) AS category_name
            FROM book_expenses e
            LEFT JOIN book_categories c ON c.id = e.category_id
            WHERE YEAR(e.spent_on) = ?';
    $params = [$year];
    if ($month >= 1 && $month <= 12) {
        $sql .= ' AND MONTH(e.spent_on) = ?';
        $params[] = $month;
    }
    if ($categoryId !== '' && ctype_digit($categoryId)) {
        $sql .= ' AND e.category_id = ?';
        $params[] = (int) $categoryId;
    }
    $sql .= ' ORDER BY e.spent_on DESC, e.id DESC';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function book_save_category(string $name): void
{
    $name = trim($name);
    if ($name === '' || strlen($name) > 80) {
        throw new AppError('Category name must be 1 to 80 characters.');
    }
    $pdo = db();
    $existing = $pdo->prepare('SELECT id FROM book_categories WHERE name = ?');
    $existing->execute([$name]);
    if ($existing->fetch()) {
        throw new AppError('That category already exists.');
    }
    $sort = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM book_categories')->fetchColumn();
    $pdo->prepare('INSERT INTO book_categories (name, sort_order, active) VALUES (?, ?, 1)')->execute([$name, $sort]);
}

function book_expense(int $id): ?array
{
    $stmt = db()->prepare(
        'SELECT e.id, e.spent_on, e.category_id, e.kind, e.amount, e.note, e.proof,
                COALESCE(c.name, e.category) AS category_name
         FROM book_expenses e
         LEFT JOIN book_categories c ON c.id = e.category_id
         WHERE e.id = ?'
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function proof_file(int $id): string
{
    if ($id < 1) {
        return '';
    }
    $stmt = db()->prepare('SELECT proof FROM book_expenses WHERE id = ?');
    $stmt->execute([$id]);
    $name = basename((string) $stmt->fetchColumn());
    if (!preg_match('/^\d+\.(jpg|png|webp|gif|pdf)$/', $name)) {
        return '';
    }
    $path = uploads_root() . '/proofs/' . $name;
    return is_file($path) ? $path : '';
}

function delete_proof_files(int $id): void
{
    if ($id < 1) {
        return;
    }
    $dir = uploads_root() . '/proofs';
    foreach (glob($dir . '/' . $id . '.*') ?: [] as $old) {
        if (is_file($old)) {
            unlink($old);
        }
    }
}

function store_proof_upload(array $file, int $expenseId): string
{
    $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error === UPLOAD_ERR_NO_FILE) {
        return '';
    }
    if ($error !== UPLOAD_ERR_OK) {
        throw new AppError('The proof did not upload. Try again.');
    }
    $size = (int) ($file['size'] ?? 0);
    if ($size < 1 || $size > 8 * 1024 * 1024) {
        throw new AppError('Use a proof under 8 MB.');
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        throw new AppError('The proof did not upload. Try again.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
    $types = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
        'application/pdf' => 'pdf',
    ];
    if (!is_string($mime) || !isset($types[$mime])) {
        throw new AppError('Use a JPG, PNG, WebP, GIF, or PDF.');
    }
    $dir = uploads_root() . '/proofs';
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new AppError('Could not store the proof.', 500);
    }
    $name = $expenseId . '.' . $types[$mime];
    $dest = $dir . '/' . $name;
    $part = $dir . '/incoming-' . $expenseId;
    if (!move_uploaded_file($tmp, $part)) {
        throw new AppError('Could not store the proof.', 500);
    }
    delete_proof_files($expenseId);
    if (!rename($part, $dest)) {
        if (is_file($part)) {
            unlink($part);
        }
        throw new AppError('Could not store the proof.', 500);
    }
    return $name;
}

function book_save_expense(string $date, string $categoryId, string $kind, string $amount, string $note, ?int $id = null, ?array $file = null, bool $removeProof = false): void
{
    $date = parse_date($date);
    if (!ctype_digit($categoryId)) {
        throw new AppError('Choose a category.');
    }
    $pdo = db();
    $category = $pdo->prepare('SELECT id, name FROM book_categories WHERE id = ? AND active = 1');
    $category->execute([(int) $categoryId]);
    $row = $category->fetch();
    if (!$row) {
        throw new AppError('Choose a category.');
    }
    $kind = trim($kind);
    $note = trim($note);
    if (strlen($kind) > 40 || strlen($note) > 160) {
        throw new AppError('Keep the note short.');
    }
    $value = book_amount($amount);
    if (to_cents($value) === 0) {
        throw new AppError('Enter an amount.');
    }
    if ($id !== null && $id > 0) {
        $existing = book_expense($id);
        if ($existing === null) {
            throw new AppError('That expense was not found.', 404);
        }
        $pdo->prepare('UPDATE book_expenses SET spent_on = ?, category = ?, category_id = ?, kind = ?, amount = ?, note = ? WHERE id = ?')
            ->execute([$date, $row['name'], (int) $row['id'], $kind, $value, $note, $id]);
        $expenseId = $id;
    } else {
        $pdo->prepare('INSERT INTO book_expenses (spent_on, category, category_id, kind, amount, note) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$date, $row['name'], (int) $row['id'], $kind, $value, $note]);
        $expenseId = (int) $pdo->lastInsertId();
    }
    if ($removeProof) {
        delete_proof_files($expenseId);
        $pdo->prepare('UPDATE book_expenses SET proof = "" WHERE id = ?')->execute([$expenseId]);
    }
    if ($file !== null) {
        $stored = store_proof_upload($file, $expenseId);
        if ($stored !== '') {
            $pdo->prepare('UPDATE book_expenses SET proof = ? WHERE id = ?')->execute([$stored, $expenseId]);
        }
    }
}

function staff_month_expenses(string $month): array
{
    if (!preg_match('/^(\d{4})-(\d{2})$/', $month, $matches)) {
        throw new AppError('Choose a month.');
    }
    $year = (int) $matches[1];
    $monthNum = (int) $matches[2];
    $maxYear = (int) date('Y');
    if ($year < 2020 || $year > $maxYear || $monthNum < 1 || $monthNum > 12) {
        throw new AppError('Choose a month.');
    }
    if ($year === $maxYear && $monthNum > (int) date('n')) {
        throw new AppError('Choose a month that has started.');
    }
    $out = [];
    foreach (book_expenses($year, '', $monthNum) as $row) {
        $proof = trim((string) ($row['proof'] ?? ''));
        $ext = strtolower(pathinfo($proof, PATHINFO_EXTENSION));
        $out[] = [
            'id' => (int) $row['id'],
            'spent_on' => (string) $row['spent_on'],
            'category' => (string) $row['category_name'],
            'kind' => (string) $row['kind'],
            'amount' => from_cents(to_cents((string) $row['amount'])),
            'note' => (string) $row['note'],
            'has_proof' => $proof !== '',
            'proof_kind' => $ext === 'pdf' ? 'pdf' : ($proof !== '' ? 'image' : ''),
        ];
    }
    return [
        'month' => sprintf('%04d-%02d', $year, $monthNum),
        'expenses' => $out,
        'currency_symbol' => settings()['currency_symbol'],
    ];
}

function book_month_report(int $year, int $month): array
{
    if ($month < 1 || $month > 12) {
        throw new AppError('Choose a month.');
    }
    $yearReport = book_year_report($year);
    $monthRow = $yearReport['months'][$month];
    $pdo = db();
    $start = sprintf('%04d-%02d-01', $year, $month);
    $end = (new DateTime($start))->modify('+1 month')->format('Y-m-d');
    $expenseRows = $pdo->prepare(
        'SELECT e.id, e.spent_on, e.proof, COALESCE(c.name, e.category) AS category_name, e.kind, e.amount, e.note
         FROM book_expenses e
         LEFT JOIN book_categories c ON c.id = e.category_id
         WHERE e.spent_on >= ? AND e.spent_on < ?
         ORDER BY e.spent_on, e.id'
    );
    $expenseRows->execute([$start, $end]);
    $listed = $expenseRows->fetchAll();
    foreach (book_recurring_for_month($year, $month) as $line) {
        $listed[] = [
            'id' => 0,
            'spent_on' => $start,
            'proof' => '',
            'category_name' => $line['category'],
            'kind' => $line['kind'],
            'amount' => $line['amount'],
            'note' => ($line['note'] !== '' ? $line['note'] . ' · ' : '') . ($line['month_set'] ? 'This month' : 'Every month'),
        ];
    }
    $byCategory = [];
    foreach ($listed as $row) {
        $name = (string) $row['category_name'];
        if (book_category_bucket($name) === 'salary') {
            continue;
        }
        $byCategory[$name] = ($byCategory[$name] ?? 0) + to_cents((string) $row['amount']);
    }
    $salary = to_cents($monthRow['salary']);
    $categories = [];
    foreach (book_categories() as $name) {
        $categories[] = [
            'name' => $name,
            'total' => from_cents($byCategory[$name] ?? 0),
        ];
        unset($byCategory[$name]);
    }
    foreach ($byCategory as $name => $cents) {
        $categories[] = ['name' => $name, 'total' => from_cents($cents)];
    }
    $sales = $pdo->prepare(
        'SELECT s.id, s.closed_at AS when_at, s.table_name, s.payment_method, s.total, s.held, c.name AS customer_name
         FROM sales s
         LEFT JOIN customers c ON c.id = s.customer_id
         WHERE s.status = "paid" AND s.closed_at >= ? AND s.closed_at < ?
         ORDER BY s.closed_at'
    );
    $sales->execute([$start . ' 00:00:00', $end . ' 00:00:00']);
    $saleRows = [];
    $dayCents = [];
    $addDay = static function (string $date) use (&$dayCents): void {
        if (!isset($dayCents[$date])) {
            $dayCents[$date] = ['cash' => 0, 'bank' => 0, 'credit' => 0];
        }
    };
    foreach ($sales as $row) {
        $cents = to_cents((string) $row['total']);
        $method = (string) $row['payment_method'];
        $day = substr((string) $row['when_at'], 0, 10);
        $addDay($day);
        if ($method === 'online') {
            $dayCents[$day]['bank'] += $cents;
        } elseif ($method === 'credit' || $method === 'card') {
            $dayCents[$day]['credit'] += $cents;
        } else {
            $dayCents[$day]['cash'] += $cents;
        }
        $saleRows[] = [
            'id' => (int) $row['id'],
            'when_at' => $row['when_at'],
            'table_name' => $row['table_name'],
            'customer_name' => (string) ($row['customer_name'] ?? ''),
            'payment_method' => $method,
            'payment_label' => payment_label($method),
            'held' => (int) ($row['held'] ?? 0) === 1,
            'open_credit' => false,
            'total' => from_cents($cents),
        ];
    }
    $openCredit = $pdo->prepare(
        'SELECT s.id, s.opened_at AS when_at, s.table_name, s.total, c.name AS customer_name
         FROM sales s
         LEFT JOIN customers c ON c.id = s.customer_id
         WHERE s.status = "credit" AND s.opened_at >= ? AND s.opened_at < ?
         ORDER BY s.opened_at'
    );
    $openCredit->execute([$start . ' 00:00:00', $end . ' 00:00:00']);
    $creditMonth = 0;
    foreach ($openCredit as $row) {
        $cents = to_cents((string) $row['total']);
        $creditMonth += $cents;
        $day = substr((string) $row['when_at'], 0, 10);
        $addDay($day);
        $dayCents[$day]['credit'] += $cents;
        $saleRows[] = [
            'id' => (int) $row['id'],
            'when_at' => $row['when_at'],
            'table_name' => $row['table_name'],
            'customer_name' => (string) ($row['customer_name'] ?? ''),
            'payment_method' => 'credit',
            'payment_label' => 'Credit/Hold',
            'held' => true,
            'open_credit' => true,
            'total' => from_cents($cents),
        ];
    }
    usort($saleRows, static function (array $a, array $b): int {
        return strcmp((string) $a['when_at'], (string) $b['when_at']);
    });
    ksort($dayCents);
    $dayRows = [];
    foreach ($dayCents as $date => $amounts) {
        $dayRows[] = [
            'date' => $date,
            'cash' => from_cents($amounts['cash']),
            'bank' => from_cents($amounts['bank']),
            'credit' => from_cents($amounts['credit']),
        ];
    }
    $creditRaw = $pdo->query('SELECT COALESCE(SUM(total), 0) FROM sales WHERE status = "credit"')->fetchColumn();
    $credit = to_cents((string) $creditRaw);
    $income = to_cents($monthRow['sales']);
    $expenseTotal = to_cents($monthRow['expenses']);
    return [
        'year' => $year,
        'month' => $month,
        'income' => from_cents($income),
        'pos' => $monthRow['pos'],
        'recorded' => from_cents(to_cents($monthRow['transfer']) + to_cents($monthRow['cash'])),
        'salaries' => from_cents($salary),
        'categories' => $categories,
        'expenses' => from_cents($expenseTotal),
        'profit' => from_cents($income - $expenseTotal),
        'credit_open' => from_cents($credit),
        'credit_month' => from_cents($creditMonth),
        'days' => $dayRows,
        'sales' => $saleRows,
        'expense_rows' => $listed,
    ];
}

function book_delete_expense(int $id): void
{
    delete_proof_files($id);
    db()->prepare('DELETE FROM book_expenses WHERE id = ?')->execute([$id]);
}

function book_recurring_list(): array
{
    $rows = db()->query(
        'SELECT r.id, r.category_id, r.kind, r.amount, r.note, c.name AS category_name
         FROM book_recurring r
         JOIN book_categories c ON c.id = r.category_id
         ORDER BY c.sort_order, c.name, r.id'
    )->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'category_id' => (int) $row['category_id'],
            'category_name' => (string) $row['category_name'],
            'kind' => (string) $row['kind'],
            'amount' => from_cents(to_cents((string) $row['amount'])),
            'note' => (string) $row['note'],
        ];
    }
    return $out;
}

function book_recurring_month_amounts(int $year, int $month): array
{
    $stmt = db()->prepare('SELECT recurring_id, amount FROM book_recurring_months WHERE year_num = ? AND month_num = ?');
    $stmt->execute([$year, $month]);
    $out = [];
    foreach ($stmt->fetchAll() as $row) {
        $out[(int) $row['recurring_id']] = from_cents(to_cents((string) $row['amount']));
    }
    return $out;
}

function book_recurring_for_month(int $year, int $month): array
{
    if ($month < 1 || $month > 12) {
        return [];
    }
    $started = month_has_started($year, $month);
    $overrides = book_recurring_month_amounts($year, $month);
    $out = [];
    foreach (book_recurring_list() as $row) {
        $id = $row['id'];
        if (array_key_exists($id, $overrides)) {
            $amount = $overrides[$id];
            $monthSet = true;
        } elseif ($started) {
            $amount = $row['amount'];
            $monthSet = false;
        } else {
            continue;
        }
        $cents = to_cents($amount);
        if ($cents < 1) {
            continue;
        }
        $out[] = [
            'id' => $id,
            'category' => $row['category_name'],
            'kind' => $row['kind'],
            'note' => $row['note'],
            'amount' => from_cents($cents),
            'cents' => $cents,
            'bucket' => book_category_bucket($row['category_name']),
            'month_set' => $monthSet,
        ];
    }
    return $out;
}

function book_save_recurring(?int $id, string $categoryId, string $kind, string $amount, string $note): int
{
    if (!ctype_digit($categoryId)) {
        throw new AppError('Choose a category.');
    }
    $pdo = db();
    $category = $pdo->prepare('SELECT id FROM book_categories WHERE id = ? AND active = 1');
    $category->execute([(int) $categoryId]);
    if (!$category->fetch()) {
        throw new AppError('Choose a category.');
    }
    $kind = trim($kind);
    $note = trim($note);
    if (strlen($kind) > 40 || strlen($note) > 160) {
        throw new AppError('Keep the note short.');
    }
    $value = book_amount($amount);
    if (to_cents($value) < 1) {
        throw new AppError('Enter an amount.');
    }
    if ($id !== null && $id > 0) {
        $existing = $pdo->prepare('SELECT id FROM book_recurring WHERE id = ?');
        $existing->execute([$id]);
        if (!$existing->fetch()) {
            throw new AppError('That recurring expense was not found.', 404);
        }
        $pdo->prepare('UPDATE book_recurring SET category_id = ?, kind = ?, amount = ?, note = ? WHERE id = ?')
            ->execute([(int) $categoryId, $kind, $value, $note, $id]);
        return $id;
    }
    $pdo->prepare('INSERT INTO book_recurring (category_id, kind, amount, note) VALUES (?, ?, ?, ?)')
        ->execute([(int) $categoryId, $kind, $value, $note]);
    return (int) $pdo->lastInsertId();
}

function book_delete_recurring(int $id): void
{
    $pdo = db();
    $pdo->prepare('DELETE FROM book_recurring_months WHERE recurring_id = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM book_recurring WHERE id = ?')->execute([$id]);
}

function book_save_recurring_month(int $id, int $year, int $month, string $amount): void
{
    if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
        throw new AppError('Choose a month.');
    }
    $pdo = db();
    $existing = $pdo->prepare('SELECT id FROM book_recurring WHERE id = ?');
    $existing->execute([$id]);
    if (!$existing->fetch()) {
        throw new AppError('That recurring expense was not found.', 404);
    }
    $raw = trim($amount);
    if ($raw === '') {
        $pdo->prepare('DELETE FROM book_recurring_months WHERE recurring_id = ? AND year_num = ? AND month_num = ?')
            ->execute([$id, $year, $month]);
        return;
    }
    $value = book_amount($raw);
    $pdo->prepare(
        'INSERT INTO book_recurring_months (recurring_id, year_num, month_num, amount) VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE amount = VALUES(amount)'
    )->execute([$id, $year, $month, $value]);
}

function book_item_sales(int $itemId, string $from, string $to): array
{
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
        throw new AppError('Use a date like 21 Oct 2026.');
    }
    if ($to < $from) {
        throw new AppError('The end date is before the start date.');
    }
    $pdo = db();
    $item = $pdo->prepare('SELECT id, name FROM items WHERE id = ?');
    $item->execute([$itemId]);
    $found = $item->fetch();
    if (!$found) {
        throw new AppError('Choose an item.');
    }
    $start = $from . ' 00:00:00';
    $endDay = DateTime::createFromFormat('!Y-m-d', $to);
    if (!$endDay) {
        throw new AppError('Use a date like 21 Oct 2026.');
    }
    $end = $endDay->modify('+1 day')->format('Y-m-d') . ' 00:00:00';
    $where = 'l.item_id = ? AND (
        (s.status = "paid" AND s.closed_at >= ? AND s.closed_at < ?)
        OR (s.status = "credit" AND s.opened_at >= ? AND s.opened_at < ?)
    )';
    $params = [$itemId, $start, $end, $start, $end];
    $daysStmt = $pdo->prepare(
        'SELECT DATE(CASE WHEN s.status = "credit" THEN s.opened_at ELSE s.closed_at END) AS sale_day,
                SUM(l.qty) AS qty, SUM(l.line_total) AS amount, COUNT(DISTINCT s.id) AS bills
         FROM sale_lines l
         JOIN sales s ON s.id = l.sale_id
         WHERE ' . $where . '
         GROUP BY sale_day
         ORDER BY sale_day'
    );
    $daysStmt->execute($params);
    $qty = 0;
    $amount = 0;
    $bills = 0;
    $days = [];
    foreach ($daysStmt as $row) {
        $dayQty = (int) $row['qty'];
        $dayAmount = to_cents((string) $row['amount']);
        $dayBills = (int) $row['bills'];
        $qty += $dayQty;
        $amount += $dayAmount;
        $bills += $dayBills;
        $days[] = [
            'date' => (string) $row['sale_day'],
            'qty' => $dayQty,
            'amount' => from_cents($dayAmount),
            'bills' => $dayBills,
        ];
    }
    $salesStmt = $pdo->prepare(
        'SELECT s.id,
                CASE WHEN s.status = "credit" THEN s.opened_at ELSE s.closed_at END AS when_at,
                s.table_name, s.payment_method, s.status, s.held, c.name AS customer_name,
                SUM(l.qty) AS qty, SUM(l.line_total) AS amount
         FROM sale_lines l
         JOIN sales s ON s.id = l.sale_id
         LEFT JOIN customers c ON c.id = s.customer_id
         WHERE ' . $where . '
         GROUP BY s.id, when_at, s.table_name, s.payment_method, s.status, s.held, c.name
         ORDER BY when_at, s.id'
    );
    $salesStmt->execute($params);
    $sales = [];
    foreach ($salesStmt as $row) {
        $method = (string) $row['payment_method'];
        $sales[] = [
            'id' => (int) $row['id'],
            'when_at' => $row['when_at'],
            'table_name' => $row['table_name'],
            'customer_name' => (string) ($row['customer_name'] ?? ''),
            'payment_label' => payment_label($method),
            'open_credit' => $row['status'] === 'credit',
            'held' => (int) ($row['held'] ?? 0) === 1 && $row['status'] !== 'credit',
            'qty' => (int) $row['qty'],
            'amount' => from_cents(to_cents((string) $row['amount'])),
        ];
    }
    return [
        'item_id' => (int) $found['id'],
        'item_name' => $found['name'],
        'from' => $from,
        'to' => $to,
        'qty' => $qty,
        'amount' => from_cents($amount),
        'bills' => $bills,
        'days' => $days,
        'sales' => $sales,
    ];
}

function trading_overview(string $from, string $to, bool $includeSalary): array
{
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to) || $from > $to) {
        throw new AppError('Choose a valid date range.');
    }
    $start = new DateTime($from);
    $end = new DateTime($to);
    if ((int) $start->diff($end)->days > 40) {
        throw new AppError('Choose a shorter range.');
    }
    $endExclusive = (clone $end)->modify('+1 day')->format('Y-m-d');
    $pdo = db();
    $days = [];
    for ($cursor = clone $start; $cursor <= $end; $cursor->modify('+1 day')) {
        $key = $cursor->format('Y-m-d');
        $days[$key] = ['sales' => 0, 'expenses' => 0, 'recorded' => 0, 'credits' => 0];
    }
    $cash = 0;
    $bank = 0;
    $pos = $pdo->prepare(
        'SELECT DATE(closed_at) AS day_key, payment_method, SUM(total) AS total
         FROM sales
         WHERE status = "paid" AND payment_method IN ("cash", "online") AND closed_at >= ? AND closed_at < ?
         GROUP BY day_key, payment_method'
    );
    $pos->execute([$from . ' 00:00:00', $endExclusive . ' 00:00:00']);
    foreach ($pos as $row) {
        $key = (string) $row['day_key'];
        if (!isset($days[$key])) {
            continue;
        }
        $cents = to_cents((string) $row['total']);
        $days[$key]['sales'] += $cents;
        if ($row['payment_method'] === 'online') {
            $bank += $cents;
        } else {
            $cash += $cents;
        }
    }
    $recorded = 0;
    $book = $pdo->prepare('SELECT sale_date, transfer, cash FROM book_days WHERE sale_date >= ? AND sale_date <= ?');
    $book->execute([$from, $to]);
    foreach ($book as $row) {
        $key = (string) $row['sale_date'];
        $cents = to_cents((string) $row['transfer']) + to_cents((string) $row['cash']);
        if (isset($days[$key])) {
            $days[$key]['recorded'] += $cents;
        }
        $recorded += $cents;
    }
    $credits = 0;
    $credit = $pdo->prepare(
        'SELECT DATE(opened_at) AS day_key, SUM(total) AS total
         FROM sales
         WHERE status = "credit" AND opened_at >= ? AND opened_at < ?
         GROUP BY day_key'
    );
    $credit->execute([$from . ' 00:00:00', $endExclusive . ' 00:00:00']);
    foreach ($credit as $row) {
        $key = (string) $row['day_key'];
        $cents = to_cents((string) $row['total']);
        if (isset($days[$key])) {
            $days[$key]['credits'] += $cents;
        }
        $credits += $cents;
    }
    $expenseRows = [];
    $expenseTotal = 0;
    $buckets = ['supplies' => 0, 'salary' => 0, 'rent' => 0, 'utilities' => 0, 'other' => 0];
    $expenses = $pdo->prepare(
        'SELECT e.spent_on, COALESCE(c.name, e.category) AS category_name, e.kind, e.amount, e.note
         FROM book_expenses e
         LEFT JOIN book_categories c ON c.id = e.category_id
         WHERE e.spent_on >= ? AND e.spent_on <= ?
         ORDER BY e.spent_on, e.id'
    );
    $expenses->execute([$from, $to]);
    foreach ($expenses as $row) {
        $cents = to_cents((string) $row['amount']);
        $key = (string) $row['spent_on'];
        if (isset($days[$key])) {
            $days[$key]['expenses'] += $cents;
        }
        $expenseTotal += $cents;
        $bucket = book_category_bucket((string) $row['category_name']);
        $buckets[$bucket] += $cents;
        $expenseRows[] = [
            'spent_on' => $key,
            'category' => (string) $row['category_name'],
            'kind' => (string) $row['kind'],
            'amount' => from_cents($cents),
            'note' => (string) $row['note'],
            'bucket' => $bucket,
        ];
    }
    if ($includeSalary) {
        $month = (int) $start->format('n');
        $year = (int) $start->format('Y');
        $salaryStmt = $pdo->prepare('SELECT COALESCE(SUM(amount), 0) FROM book_salaries WHERE year_num = ? AND month_num = ?');
        $salaryStmt->execute([$year, $month]);
        $salary = to_cents((string) $salaryStmt->fetchColumn());
        if ($salary > 0) {
            $expenseTotal += $salary;
            $buckets['salary'] += $salary;
            $expenseRows[] = [
                'spent_on' => $from,
                'category' => 'Salaries',
                'kind' => '',
                'amount' => from_cents($salary),
                'note' => 'This month',
                'bucket' => 'salary',
            ];
        }
        $recurringTotal = 0;
        foreach (book_recurring_for_month($year, $month) as $line) {
            $buckets[$line['bucket']] += $line['cents'];
            $recurringTotal += $line['cents'];
            $expenseRows[] = [
                'spent_on' => $from,
                'category' => $line['category'],
                'kind' => $line['kind'],
                'amount' => $line['amount'],
                'note' => $line['month_set'] ? 'This month' : 'Every month',
                'bucket' => $line['bucket'],
            ];
        }
        if ($recurringTotal > 0) {
            $expenseTotal += $recurringTotal;
            $keys = array_keys($days);
            $count = count($keys);
            $each = intdiv($recurringTotal, $count);
            $left = $recurringTotal - $each * $count;
            foreach ($keys as $index => $key) {
                $days[$key]['expenses'] += $each + ($index === $count - 1 ? $left : 0);
            }
        }
    }
    $salesTotal = $cash + $bank;
    $profit = $salesTotal + $recorded - $expenseTotal;
    $dayRows = [];
    foreach ($days as $key => $row) {
        $dayRows[] = [
            'date' => $key,
            'sales' => from_cents($row['sales']),
            'recorded' => from_cents($row['recorded']),
            'expenses' => from_cents($row['expenses']),
            'credits' => from_cents($row['credits']),
            'profit' => from_cents($row['sales'] + $row['recorded'] - $row['expenses']),
        ];
    }
    return [
        'from' => $from,
        'to' => $to,
        'sales' => from_cents($salesTotal),
        'cash' => from_cents($cash),
        'bank' => from_cents($bank),
        'recorded' => from_cents($recorded),
        'expenses' => from_cents($expenseTotal),
        'credits' => from_cents($credits),
        'net_sales' => from_cents($salesTotal),
        'profit' => from_cents($profit),
        'supplies' => from_cents($buckets['supplies']),
        'salary' => from_cents($buckets['salary']),
        'rent' => from_cents($buckets['rent']),
        'utilities' => from_cents($buckets['utilities']),
        'other' => from_cents($buckets['other']),
        'days' => $dayRows,
        'expense_rows' => $expenseRows,
    ];
}

function staff_year_overview(int $year): array
{
    $thisYear = (int) date('Y');
    if ($year < 2020 || $year > $thisYear) {
        throw new AppError('Choose a year.');
    }
    $report = book_year_report($year);
    $names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $credits = array_fill(1, 12, 0);
    $creditTotal = 0;
    $credit = db()->prepare(
        'SELECT MONTH(opened_at) AS month_num, SUM(total) AS total
         FROM sales
         WHERE status = "credit" AND YEAR(opened_at) = ?
         GROUP BY MONTH(opened_at)'
    );
    $credit->execute([$year]);
    foreach ($credit as $row) {
        $cents = to_cents((string) $row['total']);
        $credits[(int) $row['month_num']] = $cents;
        $creditTotal += $cents;
    }
    $currentMonth = $year === $thisYear ? (int) date('n') : 12;
    $chart = [];
    for ($month = 1; $month <= 12; $month++) {
        $row = $report['months'][$month];
        $recorded = to_cents((string) $row['transfer']) + to_cents((string) $row['cash']);
        $chart[] = [
            'date' => sprintf('%04d-%02d-01', $year, $month),
            'label' => $names[$month - 1] . ' ' . $year,
            'axis' => $names[$month - 1],
            'current' => $month === $currentMonth,
            'sales' => $row['pos'],
            'recorded' => from_cents($recorded),
            'expenses' => $row['expenses'],
            'credits' => from_cents($credits[$month]),
            'profit' => $row['profit'],
        ];
    }
    $expenseRows = [];
    $expenses = db()->prepare(
        'SELECT e.spent_on, COALESCE(c.name, e.category) AS category_name, e.kind, e.amount, e.note
         FROM book_expenses e
         LEFT JOIN book_categories c ON c.id = e.category_id
         WHERE YEAR(e.spent_on) = ?
         ORDER BY e.spent_on, e.id'
    );
    $expenses->execute([$year]);
    foreach ($expenses as $row) {
        $expenseRows[] = [
            'spent_on' => (string) $row['spent_on'],
            'category' => (string) $row['category_name'],
            'kind' => (string) $row['kind'],
            'amount' => (string) $row['amount'],
            'note' => (string) $row['note'],
            'bucket' => book_category_bucket((string) $row['category_name']),
        ];
    }
    $payroll = db()->prepare('SELECT month_num, COALESCE(SUM(amount), 0) AS total FROM book_salaries WHERE year_num = ? GROUP BY month_num');
    $payroll->execute([$year]);
    foreach ($payroll as $row) {
        $cents = to_cents((string) $row['total']);
        if ($cents < 1) {
            continue;
        }
        $month = (int) $row['month_num'];
        $expenseRows[] = [
            'spent_on' => sprintf('%04d-%02d-01', $year, $month),
            'category' => 'Salaries',
            'kind' => '',
            'amount' => from_cents($cents),
            'note' => $names[$month - 1],
            'bucket' => 'salary',
        ];
    }
    for ($month = 1; $month <= 12; $month++) {
        foreach (book_recurring_for_month($year, $month) as $line) {
            $expenseRows[] = [
                'spent_on' => sprintf('%04d-%02d-01', $year, $month),
                'category' => $line['category'],
                'kind' => $line['kind'],
                'amount' => $line['amount'],
                'note' => $line['month_set'] ? 'This month' : 'Every month',
                'bucket' => $line['bucket'],
            ];
        }
    }
    usort($expenseRows, static function (array $left, array $right): int {
        return [$left['spent_on'], $left['category']] <=> [$right['spent_on'], $right['category']];
    });
    $to = $year === $thisYear ? date('Y-m-d') : sprintf('%04d-12-31', $year);
    $recorded = to_cents((string) $report['totals']['transfer']) + to_cents((string) $report['totals']['cash']);
    $parts = ['supplies' => 0, 'salary' => 0, 'rent' => 0, 'utilities' => 0, 'other' => 0];
    for ($month = 1; $month <= 12; $month++) {
        foreach ($parts as $key => $cents) {
            $parts[$key] += to_cents((string) $report['months'][$month][$key]);
        }
    }

    return [
        'from' => sprintf('%04d-01-01', $year),
        'to' => $to,
        'sales' => $report['totals']['pos'],
        'cash' => '0.00',
        'bank' => '0.00',
        'recorded' => from_cents($recorded),
        'expenses' => $report['totals']['expenses'],
        'credits' => from_cents($creditTotal),
        'net_sales' => $report['totals']['pos'],
        'profit' => $report['totals']['profit'],
        'supplies' => from_cents($parts['supplies']),
        'salary' => from_cents($parts['salary']),
        'rent' => from_cents($parts['rent']),
        'utilities' => from_cents($parts['utilities']),
        'other' => from_cents($parts['other']),
        'days' => $chart,
        'chart' => $chart,
        'expense_rows' => $expenseRows,
        'span' => 'year',
        'label' => (string) $year,
    ];
}

function staff_all_overview(): array
{
    $thisYear = (int) date('Y');
    $first = $thisYear;
    $earliest = db()->query(
        'SELECT MIN(day_key) FROM (
            SELECT DATE(closed_at) AS day_key FROM sales WHERE status = "paid" AND payment_method IN ("cash", "online")
            UNION ALL
            SELECT sale_date FROM book_days
            UNION ALL
            SELECT spent_on FROM book_expenses
        ) days'
    )->fetchColumn();
    if ($earliest) {
        $year = (int) substr((string) $earliest, 0, 4);
        if ($year >= 2000 && $year <= $thisYear) {
            $first = $year;
        }
    }
    $sales = 0;
    $recorded = 0;
    $expenses = 0;
    $profit = 0;
    for ($year = $first; $year <= $thisYear; $year++) {
        $report = book_year_report($year);
        $sales += to_cents((string) $report['totals']['pos']);
        $recorded += to_cents((string) $report['totals']['transfer']) + to_cents((string) $report['totals']['cash']);
        $expenses += to_cents((string) $report['totals']['expenses']);
        $profit += to_cents((string) $report['totals']['profit']);
    }
    $credits = to_cents((string) db()->query('SELECT COALESCE(SUM(total), 0) FROM sales WHERE status = "credit"')->fetchColumn());
    return [
        'from' => sprintf('%04d-01-01', $first),
        'to' => date('Y-m-d'),
        'sales' => from_cents($sales),
        'recorded' => from_cents($recorded),
        'expenses' => from_cents($expenses),
        'credits' => from_cents($credits),
        'profit' => from_cents($profit),
        'span' => 'all',
        'label' => 'All time',
    ];
}

function staff_overview(string $date, string $span): array
{
    $date = parse_date($date);
    if ($date > date('Y-m-d')) {
        throw new AppError('Choose today or an earlier day.');
    }
    $day = new DateTime($date);
    if ($span === 'all') {
        return staff_all_overview();
    }
    if ($span === 'year') {
        return staff_year_overview((int) $day->format('Y'));
    }
    $salary = false;
    if ($span === 'week') {
        $monday = (clone $day)->modify('-' . ((int) $day->format('N') - 1) . ' days');
        $from = $monday->format('Y-m-d');
        $to = (clone $monday)->modify('+6 days')->format('Y-m-d');
        if ($to > date('Y-m-d')) {
            $to = date('Y-m-d');
        }
    } elseif ($span === 'month') {
        $from = $day->format('Y-m-01');
        $to = $day->format('Y-m-t');
        if ($to > date('Y-m-d')) {
            $to = date('Y-m-d');
        }
        $salary = true;
        $span = 'month';
    } else {
        $from = $date;
        $to = $date;
        $span = 'day';
    }
    $overview = trading_overview($from, $to, $salary);
    if ($span !== 'month') {
        $monthFrom = $day->format('Y-m-01');
        $monthTo = $day->format('Y-m-t');
        if ($monthTo > date('Y-m-d')) {
            $monthTo = date('Y-m-d');
        }
        $monthOverview = trading_overview($monthFrom, $monthTo, true);
        $monthOverview['label'] = overview_label('month', $monthFrom, $monthTo);
        $overview['profit_span'] = $monthOverview;
    }
    if ($span === 'day') {
        $chartFrom = (new DateTime($date))->modify('-6 days')->format('Y-m-d');
        $chart = trading_overview($chartFrom, $date, false);
        $overview['chart'] = $chart['days'];
    } else {
        $overview['chart'] = $overview['days'];
    }
    $overview['span'] = $span;
    $overview['label'] = overview_label($span, $overview['from'], $overview['to']);
    return $overview;
}

function overview_label(string $span, string $from, string $to): string
{
    if ($span === 'month') {
        return format_month($from);
    }
    if ($span === 'week' && $from !== $to) {
        return format_date($from) . ' – ' . format_date($to);
    }
    return format_date($from);
}

function supply_week_start(?string $day = null): string
{
    $day = $day === null || $day === '' ? date('Y-m-d') : parse_date($day);
    $date = new DateTime($day);
    $date->modify('-' . ((int) $date->format('N') - 1) . ' days');
    return $date->format('Y-m-d');
}

function supply_qty(string $raw): string
{
    $raw = trim($raw);
    if ($raw === '') {
        return '0.00';
    }
    $cents = to_cents($raw);
    if ($cents > 99999999) {
        throw new AppError('Quantity is too large.');
    }
    return from_cents($cents);
}

function supply_line_cents(string $qty, string $price): int
{
    return intdiv(to_cents($qty) * to_cents($price) + 50, 100);
}

function supply_expense_category_id(): string
{
    $stmt = db()->prepare('SELECT id FROM book_categories WHERE name = ? AND active = 1');
    $stmt->execute(['Supply']);
    $id = $stmt->fetchColumn();
    if (!$id) {
        throw new AppError('The Supply category is missing.');
    }
    return (string) $id;
}

function supply_items(bool $activeOnly = false): array
{
    $sql = 'SELECT id, name, unit, active, sort_order FROM supply_items';
    if ($activeOnly) {
        $sql .= ' WHERE active = 1';
    }
    $sql .= ' ORDER BY sort_order, name, id';
    $out = [];
    foreach (db()->query($sql)->fetchAll() as $row) {
        $out[] = [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'unit' => (string) $row['unit'],
            'active' => (int) $row['active'] === 1,
            'sort_order' => (int) $row['sort_order'],
        ];
    }
    return $out;
}

function save_supply_item(int $id, string $name, string $unit, bool $active): void
{
    $name = trim($name);
    $unit = trim($unit);
    if ($name === '' || strlen($name) > 80 || strlen($unit) > 20) {
        throw new AppError('Enter the item name and a short unit.');
    }
    $pdo = db();
    if ($id > 0) {
        $stmt = $pdo->prepare('SELECT id FROM supply_items WHERE id = ?');
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            throw new AppError('That item was not found.', 404);
        }
        $pdo->prepare('UPDATE supply_items SET name = ?, unit = ?, active = ? WHERE id = ?')
            ->execute([$name, $unit, $active ? 1 : 0, $id]);
        return;
    }
    $sort = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM supply_items')->fetchColumn();
    $pdo->prepare('INSERT INTO supply_items (name, unit, active, sort_order) VALUES (?, ?, 1, ?)')
        ->execute([$name, $unit, $sort]);
}

function supply_week_row(string $start): ?array
{
    $stmt = db()->prepare('SELECT * FROM supply_weeks WHERE week_start = ?');
    $stmt->execute([$start]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function supply_stored_lines(int $weekId): array
{
    $stmt = db()->prepare(
        'SELECT l.item_id, l.requested_qty, l.quote_qty, l.quote_price, i.name, i.unit
         FROM supply_lines l
         JOIN supply_items i ON i.id = l.item_id
         WHERE l.week_id = ?
         ORDER BY i.sort_order, i.name, i.id'
    );
    $stmt->execute([$weekId]);
    return $stmt->fetchAll();
}

function supply_previous_qty(string $weekStart): array
{
    $stmt = db()->prepare('SELECT id FROM supply_weeks WHERE week_start < ? ORDER BY week_start DESC LIMIT 1');
    $stmt->execute([$weekStart]);
    $id = $stmt->fetchColumn();
    if (!$id) {
        return [];
    }
    $lines = db()->prepare('SELECT item_id, requested_qty FROM supply_lines WHERE week_id = ?');
    $lines->execute([(int) $id]);
    $out = [];
    foreach ($lines->fetchAll() as $row) {
        $out[(int) $row['item_id']] = from_cents(to_cents((string) $row['requested_qty']));
    }
    return $out;
}

function supply_line_view(array $row, ?string $prefill): array
{
    $quoteQty = $row['quote_qty'] === null || $row['quote_qty'] === '' ? null : from_cents(to_cents((string) $row['quote_qty']));
    $quotePrice = $row['quote_price'] === null || $row['quote_price'] === '' ? null : from_cents(to_cents((string) $row['quote_price']));
    $lineTotal = null;
    if ($quoteQty !== null && $quotePrice !== null) {
        $lineTotal = from_cents(supply_line_cents($quoteQty, $quotePrice));
    }
    return [
        'item_id' => (int) $row['item_id'],
        'name' => (string) $row['name'],
        'unit' => (string) $row['unit'],
        'requested_qty' => from_cents(to_cents((string) ($row['requested_qty'] ?? '0'))),
        'quote_qty' => $quoteQty,
        'quote_price' => $quotePrice,
        'line_total' => $lineTotal,
        'prefill_qty' => $prefill,
    ];
}

function supply_week_view(?string $day = null): array
{
    $start = supply_week_start($day);
    $week = supply_week_row($start);
    $status = $week ? (string) $week['status'] : '';
    $stored = $week ? supply_stored_lines((int) $week['id']) : [];
    $byItem = [];
    foreach ($stored as $row) {
        $byItem[(int) $row['item_id']] = $row;
    }
    $prefill = $status === '' ? supply_previous_qty($start) : [];
    $lines = [];
    if ($status === '' || $status === 'requested') {
        $seen = [];
        foreach (supply_items(true) as $item) {
            $seen[$item['id']] = true;
            $row = $byItem[$item['id']] ?? [
                'item_id' => $item['id'],
                'name' => $item['name'],
                'unit' => $item['unit'],
                'requested_qty' => '0.00',
                'quote_qty' => null,
                'quote_price' => null,
            ];
            $row['name'] = $item['name'];
            $row['unit'] = $item['unit'];
            $lines[] = supply_line_view($row, $prefill[$item['id']] ?? null);
        }
        foreach ($stored as $row) {
            if (isset($seen[(int) $row['item_id']])) {
                continue;
            }
            if (to_cents((string) $row['requested_qty']) <= 0) {
                continue;
            }
            $lines[] = supply_line_view($row, null);
        }
    } else {
        foreach ($stored as $row) {
            if (to_cents((string) $row['requested_qty']) <= 0) {
                continue;
            }
            $lines[] = supply_line_view($row, null);
        }
    }
    $totalCents = 0;
    $priced = $status === 'quoted' || $status === 'accepted';
    if ($priced) {
        foreach ($lines as $line) {
            if ($line['line_total'] !== null) {
                $totalCents += to_cents((string) $line['line_total']);
            }
        }
    }
    return [
        'week_start' => $start,
        'week_label' => format_date($start),
        'status' => $status,
        'expense_id' => $week && $week['expense_id'] !== null ? (int) $week['expense_id'] : null,
        'total' => $priced ? from_cents($totalCents) : null,
        'lines' => $lines,
    ];
}

function save_supply_request(int $userId, array $lines): array
{
    $start = supply_week_start();
    $week = supply_week_row($start);
    if ($week && $week['status'] === 'quoted') {
        throw new AppError('Supply staff already priced this week.');
    }
    if ($week && $week['status'] === 'accepted') {
        throw new AppError('This week is already accepted.');
    }
    $active = [];
    foreach (supply_items(true) as $item) {
        $active[$item['id']] = true;
    }
    $wanted = [];
    foreach ($lines as $line) {
        if (!is_array($line)) {
            continue;
        }
        $itemId = (int) ($line['item_id'] ?? 0);
        if ($itemId < 1 || !isset($active[$itemId])) {
            continue;
        }
        $qty = supply_qty((string) ($line['qty'] ?? ''));
        if (to_cents($qty) <= 0) {
            continue;
        }
        $wanted[$itemId] = $qty;
    }
    if ($wanted === []) {
        throw new AppError('Enter a quantity for at least one item.');
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $now = date('Y-m-d H:i:s');
        if ($week) {
            $weekId = (int) $week['id'];
            $pdo->prepare('UPDATE supply_weeks SET status = "requested", requested_by = ?, requested_at = ? WHERE id = ?')
                ->execute([$userId > 0 ? $userId : null, $now, $weekId]);
            $pdo->prepare('DELETE FROM supply_lines WHERE week_id = ?')->execute([$weekId]);
        } else {
            $pdo->prepare('INSERT INTO supply_weeks (week_start, status, requested_by, requested_at) VALUES (?, "requested", ?, ?)')
                ->execute([$start, $userId > 0 ? $userId : null, $now]);
            $weekId = (int) $pdo->lastInsertId();
        }
        $insert = $pdo->prepare('INSERT INTO supply_lines (week_id, item_id, requested_qty) VALUES (?, ?, ?)');
        foreach ($wanted as $itemId => $qty) {
            $insert->execute([$weekId, $itemId, $qty]);
        }
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return supply_week_view();
}

function save_supply_quote(int $userId, array $lines): array
{
    $start = supply_week_start();
    $week = supply_week_row($start);
    if (!$week || !in_array($week['status'], ['requested', 'quoted'], true)) {
        throw new AppError('There is no request to price this week.');
    }
    $stored = supply_stored_lines((int) $week['id']);
    if ($stored === []) {
        throw new AppError('There is no request to price this week.');
    }
    $posted = [];
    foreach ($lines as $line) {
        if (!is_array($line)) {
            continue;
        }
        $posted[(int) ($line['item_id'] ?? 0)] = $line;
    }
    $updates = [];
    foreach ($stored as $row) {
        $itemId = (int) $row['item_id'];
        $entry = $posted[$itemId] ?? null;
        $qtyRaw = trim((string) ($entry['qty'] ?? ''));
        $priceRaw = trim((string) ($entry['price'] ?? ''));
        if ($entry === null || $qtyRaw === '' || $priceRaw === '') {
            throw new AppError('Enter the quantity and price for each item.');
        }
        $updates[$itemId] = [supply_qty($qtyRaw), supply_qty($priceRaw)];
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $update = $pdo->prepare('UPDATE supply_lines SET quote_qty = ?, quote_price = ? WHERE week_id = ? AND item_id = ?');
        foreach ($updates as $itemId => $pair) {
            $update->execute([$pair[0], $pair[1], (int) $week['id'], $itemId]);
        }
        $pdo->prepare('UPDATE supply_weeks SET status = "quoted", quoted_by = ?, quoted_at = ? WHERE id = ?')
            ->execute([$userId > 0 ? $userId : null, date('Y-m-d H:i:s'), (int) $week['id']]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return supply_week_view();
}

function accept_supply_week(int $userId): array
{
    $pdo = db();
    $start = supply_week_start();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT * FROM supply_weeks WHERE week_start = ? FOR UPDATE');
        $stmt->execute([$start]);
        $week = $stmt->fetch();
        if (!$week || (string) $week['status'] !== 'quoted') {
            throw new AppError('There is no quote to accept.');
        }
        if ($week['expense_id'] !== null) {
            throw new AppError('This week is already accepted.');
        }
        $stored = supply_stored_lines((int) $week['id']);
        $cents = 0;
        foreach ($stored as $row) {
            if ($row['quote_qty'] === null || $row['quote_price'] === null || $row['quote_qty'] === '' || $row['quote_price'] === '') {
                throw new AppError('The quote is incomplete.');
            }
            $cents += supply_line_cents((string) $row['quote_qty'], (string) $row['quote_price']);
        }
        if ($cents <= 0) {
            throw new AppError('The quote total is zero.');
        }
        book_save_expense(
            date('Y-m-d'),
            supply_expense_category_id(),
            'Weekly supply',
            from_cents($cents),
            'Week of ' . format_date($start)
        );
        $expenseId = (int) $pdo->lastInsertId();
        $pdo->prepare('UPDATE supply_weeks SET status = "accepted", accepted_by = ?, accepted_at = ?, expense_id = ? WHERE id = ?')
            ->execute([$userId > 0 ? $userId : null, date('Y-m-d H:i:s'), $expenseId, (int) $week['id']]);
        $pdo->commit();
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
    return supply_week_view();
}

function send_back_supply_week(): array
{
    $start = supply_week_start();
    $week = supply_week_row($start);
    if (!$week || (string) $week['status'] !== 'quoted') {
        throw new AppError('There is no quote to send back.');
    }
    $pdo = db();
    $pdo->prepare('UPDATE supply_lines SET quote_qty = NULL, quote_price = NULL WHERE week_id = ?')->execute([(int) $week['id']]);
    $pdo->prepare('UPDATE supply_weeks SET status = "requested", quoted_by = NULL, quoted_at = NULL WHERE id = ?')->execute([(int) $week['id']]);
    return supply_week_view();
}
