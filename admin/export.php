<?php

require __DIR__ . '/_init.php';
require_local_page();
start_app_session();

$role = (string) ($_SESSION['role'] ?? '');
if (!role_opens_admin($role) && empty($_SESSION['owner_ok'])) {
    header('Location: ../login.php');
    exit;
}

$kind = (string) ($_GET['kind'] ?? '');
$area = $kind === 'customers' ? 'admin.customers' : 'admin.sales';
if (($kind !== 'sales' && $kind !== 'credit' && $kind !== 'customers') || !session_can($area)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Your role cannot export that list.';
    exit;
}

function export_items(array $lines): string
{
    $parts = [];
    foreach ($lines as $line) {
        $parts[] = (int) $line['qty'] . ' x ' . (string) $line['item_name'];
    }
    return implode('; ', $parts);
}

function send_csv(string $filename, array $header, array $rows): void
{
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $out = fopen('php://output', 'wb');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, $header);
    foreach ($rows as $row) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}

try {
    if ($kind === 'customers') {
        $rows = [];
        foreach (list_customers() as $customer) {
            $rows[] = [
                $customer['name'],
                $customer['phone'],
                (string) $customer['open_bills'],
                $customer['open_total'],
                $customer['credit_limit'] === null ? '' : $customer['credit_limit'],
                $customer['left'] === null ? '' : $customer['left'],
                format_date((string) $customer['created_at']),
            ];
        }
        send_csv('customers.csv', ['Name', 'Phone', 'Bills', 'Credit total', 'Limit', 'Left', 'Added'], $rows);
    }

    $day = trim((string) ($_GET['date'] ?? ''));
    $from = $day !== '' ? parse_date($day) : '2020-01-01';
    $to = $day !== '' ? $from : date('Y-m-d');
    $report = sales_for_range($from, $to);

    if ($kind === 'credit') {
        $rows = [];
        foreach ($report['credits'] as $bill) {
            $rows[] = [
                bill_number((int) $bill['id']),
                format_when((string) $bill['opened_at']),
                (string) $bill['table_name'],
                (string) $bill['customer_name'],
                export_items($bill['lines'] ?? []),
                $bill['total'],
            ];
        }
        send_csv('credit.csv', ['Bill', 'Opened', 'Table', 'Customer', 'Items', 'Total'], $rows);
    }

    $rows = [];
    foreach ($report['sales'] as $sale) {
        if ($sale['payment_method'] !== 'cash' && $sale['payment_method'] !== 'online') {
            continue;
        }
        $rows[] = [
            bill_number((int) $sale['id']),
            format_when((string) $sale['closed_at']),
            (string) $sale['table_name'],
            (string) $sale['payment_label'],
            (string) $sale['customer_name'],
            export_items($sale['lines'] ?? []),
            $sale['total'],
            !empty($sale['held']) ? 'Yes' : '',
            (string) $sale['settlement_reference'],
        ];
    }
    $name = $day !== '' ? 'sales-' . $from . '.csv' : 'sales.csv';
    send_csv($name, ['Bill', 'Paid', 'Table', 'Payment', 'Customer', 'Items', 'Total', 'Held', 'Settlement'], $rows);
} catch (Throwable $error) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=utf-8');
    echo $error instanceof AppError ? $error->getMessage() : 'The export could not be made.';
}
