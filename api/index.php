<?php

require __DIR__ . '/lib.php';

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $path = request_path();

    if ($method === 'GET' && $path === '/') {
        json_out(['ok' => true]);
    }

    if ($method === 'POST' && ($path === '/auth/login' || $path === '/auth/staff')) {
        require_cafe_network();
        $body = read_json();
        $user = authenticate_user((string) ($body['username'] ?? ''), (string) ($body['password'] ?? ''));
        if (!role_uses_tailscale((string) $user['role']) && !is_shop_floor()) {
            throw new AppError('Waiters sign in on the cafe Wi-Fi.', 403);
        }
        json_out([
            'token' => issue_token($user['id']),
            'role' => $user['role'],
            'name' => $user['name'],
            'username' => $user['username'],
        ]);
    }

    if ($method === 'POST' && $path === '/auth/owner') {
        require_local();
        $body = read_json();
        owner_login((string) ($body['password'] ?? ''));
        json_out(['ok' => true]);
    }

    if ($method === 'POST' && $path === '/auth/logout') {
        require_local();
        logout_owner();
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && $path === '/menu') {
        require_cafe_network();
        require_staff_area('orders');
        json_out(menu());
    }

    if ($method === 'GET' && preg_match('#^/items/(\d+)/photo$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        $file = item_photo_file((int) $matches[1]);
        if ($file === '') {
            json_error(404, 'No photo for this item.');
        }
        send_jpeg($file, true);
    }

    if ($method === 'GET' && $path === '/tables') {
        require_cafe_network();
        require_staff_area('orders');
        json_out(floor_tables());
    }

    if ($method === 'GET' && $path === '/me') {
        require_cafe_network();
        $staff = staff_user();
        if ($staff === null) {
            throw new AppError('Sign in required.', 401);
        }
        assert_staff_network($staff);
        json_out(staff_profile((int) $staff['id']));
    }

    if ($method === 'POST' && $path === '/me') {
        require_cafe_network();
        $staff = staff_user();
        if ($staff === null) {
            throw new AppError('Sign in required.', 401);
        }
        assert_staff_network($staff);
        $body = read_json();
        update_own_account(
            (int) $staff['id'],
            (string) ($body['name'] ?? ''),
            (string) ($body['current_password'] ?? ''),
            (string) ($body['new_password'] ?? ''),
            bearer_token()
        );
        json_out(staff_profile((int) $staff['id']));
    }

    if ($method === 'POST' && $path === '/shift/in') {
        require_cafe_network();
        $staff = staff_user();
        if ($staff === null) {
            throw new AppError('Sign in required.', 401);
        }
        assert_staff_network($staff);
        json_out(['shift' => clock_in_user((int) $staff['id'])]);
    }

    if ($method === 'POST' && $path === '/shift/out') {
        require_cafe_network();
        $staff = staff_user();
        if ($staff === null) {
            throw new AppError('Sign in required.', 401);
        }
        assert_staff_network($staff);
        clock_out_user((int) $staff['id']);
        json_out(['shift' => null]);
    }

    if ($method === 'POST' && preg_match('#^/tables/(\d+)/open$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        json_out(open_table((int) $matches[1]));
    }

    if ($method === 'GET' && preg_match('#^/orders/(\d+)$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        json_out(order_payload((int) $matches[1]));
    }

    if ($method === 'POST' && preg_match('#^/orders/(\d+)/customer$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        $body = read_json();
        json_out(assign_bill_customer((int) $matches[1], (int) ($body['customer_id'] ?? 0)));
    }

    if ($method === 'POST' && preg_match('#^/orders/(\d+)/lines/(\d+)$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        $body = read_json();
        json_out(set_order_line_qty((int) $matches[1], (int) $matches[2], (int) ($body['qty'] ?? -1)));
    }

    if ($method === 'POST' && preg_match('#^/orders/(\d+)/lines$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        $body = read_json();
        json_out(add_order_item((int) $matches[1], (int) ($body['item_id'] ?? 0), (int) ($body['qty'] ?? 1)));
    }

    if ($method === 'GET' && $path === '/bank-image') {
        require_cafe_network();
        require_staff_area('orders');
        $file = bank_image_file();
        if ($file === '') {
            json_error(404, 'No bank photo yet.');
        }
        send_jpeg($file);
    }

    if ($method === 'POST' && preg_match('#^/orders/(\d+)/close$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        $type = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
        $payment = '';
        $slip = null;
        if (str_contains($type, 'multipart/form-data')) {
            $payment = (string) ($_POST['payment_method'] ?? '');
            if (isset($_FILES['slip']) && is_array($_FILES['slip']) && (int) ($_FILES['slip']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $slip = $_FILES['slip'];
            }
        } else {
            $body = read_json();
            $payment = (string) ($body['payment_method'] ?? '');
            $customer = [
                'id' => (int) ($body['customer_id'] ?? 0),
                'name' => (string) ($body['customer_name'] ?? ''),
                'phone' => (string) ($body['customer_phone'] ?? ''),
            ];
        }
        json_out(close_order((int) $matches[1], $payment, $slip, $customer ?? []));
    }

    if ($method === 'POST' && preg_match('#^/orders/(\d+)/cancel$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        cancel_order((int) $matches[1]);
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && $path === '/customers') {
        require_cafe_network();
        require_staff_area('orders');
        json_out(['customers' => search_customers((string) ($_GET['q'] ?? ''))]);
    }

    if ($method === 'GET' && $path === '/customers/book') {
        require_cafe_network();
        $staff = require_staff_area('orders');
        json_out([
            'customers' => customer_directory((string) ($_GET['q'] ?? '')),
            'can_approve' => staff_can_approve($staff),
            'currency_symbol' => settings()['currency_symbol'],
        ]);
    }

    if ($method === 'POST' && $path === '/customers') {
        require_cafe_network();
        require_staff_area('orders');
        $body = read_json();
        $id = staff_create_customer((string) ($body['name'] ?? ''), (string) ($body['phone'] ?? ''));
        json_out(['id' => $id], 201);
    }

    if ($method === 'POST' && preg_match('#^/customers/(\d+)/name$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        $body = read_json();
        rename_customer((int) $matches[1], (string) ($body['name'] ?? ''));
        json_out(customer_staff_view((int) $matches[1]));
    }

    if ($method === 'POST' && preg_match('#^/customers/(\d+)/limit$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('credit.limit');
        $body = read_json();
        save_credit_limit((int) $matches[1], (string) ($body['credit_limit'] ?? ''));
        json_out(customer_staff_view((int) $matches[1]));
    }

    if ($method === 'GET' && preg_match('#^/customers/(\d+)$#', $path, $matches)) {
        require_cafe_network();
        $staff = require_staff_area('orders');
        $view = customer_staff_view((int) $matches[1]);
        $view['can_approve'] = staff_can_approve($staff);
        $view['currency_symbol'] = settings()['currency_symbol'];
        json_out($view);
    }

    if ($method === 'POST' && preg_match('#^/sales/(\d+)/payment-approve$#', $path, $matches)) {
        require_cafe_network();
        $staff = require_staff_area('orders');
        if (!staff_can_approve($staff)) {
            json_error(403, 'Only a manager can approve a payment.');
        }
        approve_customer_payment((int) $matches[1]);
        json_out(['ok' => true]);
    }

    if ($method === 'POST' && preg_match('#^/sales/(\d+)/payment-reject$#', $path, $matches)) {
        require_cafe_network();
        $staff = require_staff_area('orders');
        if (!staff_can_approve($staff)) {
            json_error(403, 'Only a manager can approve a payment.');
        }
        reject_customer_payment((int) $matches[1]);
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && preg_match('#^/sales/(\d+)/slip$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        $file = slip_file((int) $matches[1]);
        if ($file === '') {
            json_error(404, 'No payment photo.');
        }
        send_jpeg($file);
    }

    if ($method === 'GET' && $path === '/credit') {
        require_cafe_network();
        require_staff_area('orders');
        json_out(['credits' => credit_sales()]);
    }

    if ($method === 'GET' && $path === '/credits/book.pdf') {
        require_cafe_network();
        require_staff_area('orders');
        send_credit_book_pdf();
    }

    if ($method === 'GET' && preg_match('#^/customers/(\d+)/credits$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('orders');
        json_out(customer_credit_bills((int) $matches[1]));
    }

    if ($method === 'POST' && $path === '/settlements') {
        require_cafe_network();
        require_staff_area('orders');
        $type = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
        $slip = null;
        if (str_contains($type, 'multipart/form-data')) {
            $payment = (string) ($_POST['payment_method'] ?? '');
            $customerId = (int) ($_POST['customer_id'] ?? 0);
            $saleIds = array_filter(explode(',', (string) ($_POST['sale_ids'] ?? '')), static function ($id) {
                return $id !== '';
            });
            if (isset($_FILES['slip']) && is_array($_FILES['slip']) && (int) ($_FILES['slip']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $slip = $_FILES['slip'];
            }
        } else {
            $body = read_json();
            $payment = (string) ($body['payment_method'] ?? '');
            $customerId = (int) ($body['customer_id'] ?? 0);
            $saleIds = is_array($body['sale_ids'] ?? null) ? $body['sale_ids'] : [];
        }
        json_out(settle_bills($saleIds, $customerId, $payment, $slip));
    }

    if ($method === 'GET' && $path === '/sales') {
        require_cafe_network();
        if (bearer_token() !== null) {
            require_staff_area('today');
        } else {
            require_owner_api();
        }
        if (isset($_GET['from']) && trim((string) $_GET['from']) !== '') {
            json_out(sales_for_range((string) $_GET['from'], (string) ($_GET['to'] ?? date('Y-m-d'))));
        }
        json_out(sales_for_date((string) ($_GET['date'] ?? date('Y-m-d'))));
    }

    if ($method === 'GET' && $path === '/overview') {
        require_cafe_network();
        require_staff_area('today');
        json_out(staff_overview((string) ($_GET['date'] ?? date('Y-m-d')), (string) ($_GET['span'] ?? 'day')));
    }

    if ($method === 'GET' && $path === '/categories') {
        require_local();
        require_owner_api();
        json_out(['categories' => list_categories()]);
    }

    if ($method === 'POST' && $path === '/categories') {
        require_local();
        require_owner_api();
        $body = read_json();
        $id = save_category(null, (string) ($body['name'] ?? ''), (int) ($body['sort_order'] ?? 0));
        json_out(['id' => $id], 201);
    }

    if ($method === 'POST' && preg_match('#^/categories/(\d+)/delete$#', $path, $matches)) {
        require_local();
        require_owner_api();
        delete_category((int) $matches[1]);
        json_out(['ok' => true]);
    }

    if ($method === 'POST' && preg_match('#^/categories/(\d+)$#', $path, $matches)) {
        require_local();
        require_owner_api();
        $body = read_json();
        save_category((int) $matches[1], (string) ($body['name'] ?? ''), (int) ($body['sort_order'] ?? 0));
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && $path === '/items') {
        require_local();
        require_owner_api();
        json_out(['items' => list_items()]);
    }

    if ($method === 'POST' && $path === '/items') {
        require_local();
        require_owner_api();
        $id = save_item(null, read_json());
        json_out(['id' => $id], 201);
    }

    if ($method === 'POST' && preg_match('#^/items/(\d+)$#', $path, $matches)) {
        require_local();
        require_owner_api();
        save_item((int) $matches[1], read_json());
        json_out(['ok' => true]);
    }

    if ($method === 'POST' && $path === '/tables') {
        require_local();
        require_owner_api();
        $body = read_json();
        $id = save_table(null, (string) ($body['name'] ?? ''), (int) ($body['sort_order'] ?? 0), true);
        json_out(['id' => $id], 201);
    }

    if ($method === 'POST' && preg_match('#^/tables/(\d+)/delete$#', $path, $matches)) {
        require_local();
        require_owner_api();
        delete_table((int) $matches[1]);
        json_out(['ok' => true]);
    }

    if ($method === 'POST' && preg_match('#^/tables/(\d+)$#', $path, $matches)) {
        require_local();
        require_owner_api();
        $body = read_json();
        $active = !isset($body['active']) || !($body['active'] === false || $body['active'] === 0 || $body['active'] === '0');
        save_table((int) $matches[1], (string) ($body['name'] ?? ''), (int) ($body['sort_order'] ?? 0), $active);
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && $path === '/expense-categories') {
        require_cafe_network();
        require_staff_area('expenses');
        $categories = [];
        foreach (book_categories() as $id => $name) {
            $categories[] = ['id' => (string) $id, 'name' => (string) $name];
        }
        json_out(['categories' => $categories]);
    }

    if ($method === 'GET' && $path === '/expenses') {
        require_cafe_network();
        require_staff_area('expenses');
        $month = trim((string) ($_GET['month'] ?? date('Y-m')));
        json_out(staff_month_expenses($month));
    }

    if ($method === 'GET' && preg_match('#^/expenses/(\d+)/proof$#', $path, $matches)) {
        require_cafe_network();
        require_staff_area('expenses');
        $file = proof_file((int) $matches[1]);
        if ($file === '') {
            json_error(404, 'No proof for this expense.');
        }
        $mime = [
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
        ][strtolower(pathinfo($file, PATHINFO_EXTENSION))] ?? 'application/octet-stream';
        send_stored_file($file, $mime);
    }

    if ($method === 'POST' && $path === '/expenses') {
        require_cafe_network();
        require_staff_area('expenses');
        $type = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
        if (str_contains($type, 'multipart/form-data')) {
            $proof = null;
            if (isset($_FILES['proof']) && is_array($_FILES['proof']) && (int) ($_FILES['proof']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $proof = $_FILES['proof'];
            }
            book_save_expense(
                (string) ($_POST['spent_on'] ?? ''),
                (string) ($_POST['category_id'] ?? ''),
                (string) ($_POST['kind'] ?? ''),
                (string) ($_POST['amount'] ?? ''),
                (string) ($_POST['note'] ?? ''),
                null,
                $proof,
                false
            );
        } else {
            $body = read_json();
            book_save_expense(
                (string) ($body['spent_on'] ?? ''),
                (string) ($body['category_id'] ?? ''),
                (string) ($body['kind'] ?? ''),
                (string) ($body['amount'] ?? ''),
                (string) ($body['note'] ?? ''),
                null,
                null,
                false
            );
        }
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && $path === '/supply/week') {
        require_cafe_network();
        require_staff_area('expenses');
        json_out(supply_week_view());
    }

    if ($method === 'POST' && $path === '/supply/week') {
        require_cafe_network();
        $staff = require_staff_area('expenses');
        $body = read_json();
        $lines = $body['lines'] ?? [];
        json_out(save_supply_request((int) $staff['id'], is_array($lines) ? $lines : []));
    }

    if ($method === 'POST' && $path === '/settings') {
        require_local();
        require_owner_api();
        update_settings(read_json());
        json_out(['ok' => true]);
    }

    if ($method === 'POST' && $path === '/customer/register') {
        $body = read_json();
        json_out(register_customer((string) ($body['name'] ?? ''), (string) ($body['phone'] ?? ''), (string) ($body['password'] ?? '')));
    }

    if ($method === 'POST' && $path === '/customer/login') {
        $body = read_json();
        json_out(login_customer((string) ($body['phone'] ?? ''), (string) ($body['password'] ?? '')));
    }

    if ($method === 'POST' && $path === '/customer/logout') {
        require_shop_floor();
        logout_customer();
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && $path === '/customer/me') {
        json_out(require_customer());
    }

    if ($method === 'GET' && $path === '/customer/menu') {
        require_customer();
        json_out(menu());
    }

    if ($method === 'GET' && $path === '/customer/bank-image') {
        require_customer();
        $file = bank_image_file();
        if ($file === '') {
            json_error(404, 'No bank photo yet.');
        }
        send_jpeg($file);
    }

    if ($method === 'GET' && preg_match('#^/customer/items/(\d+)/photo$#', $path, $matches)) {
        require_customer();
        $file = item_photo_file((int) $matches[1]);
        if ($file === '') {
            json_error(404, 'No photo for this item.');
        }
        send_jpeg($file, true);
    }

    if ($method === 'GET' && preg_match('#^/customer/tables/(\d+)$#', $path, $matches)) {
        $customer = require_customer();
        json_out(customer_table_state((int) $customer['id'], (int) $matches[1]));
    }

    if ($method === 'POST' && preg_match('#^/customer/tables/(\d+)/open$#', $path, $matches)) {
        $customer = require_customer();
        json_out(customer_start_order((int) $customer['id'], (int) $matches[1]));
    }

    if ($method === 'POST' && preg_match('#^/customer/orders/(\d+)/lines/(\d+)$#', $path, $matches)) {
        $customer = require_customer();
        $body = read_json();
        json_out(customer_set_qty((int) $customer['id'], (int) $matches[1], (int) $matches[2], (int) ($body['qty'] ?? 0)));
    }

    if ($method === 'POST' && preg_match('#^/customer/orders/(\d+)/lines$#', $path, $matches)) {
        $customer = require_customer();
        $body = read_json();
        json_out(customer_add_item((int) $customer['id'], (int) $matches[1], (int) ($body['item_id'] ?? 0), (int) ($body['qty'] ?? 1)));
    }

    if ($method === 'POST' && preg_match('#^/customer/orders/(\d+)/cancel$#', $path, $matches)) {
        $customer = require_customer();
        customer_cancel_order((int) $customer['id'], (int) $matches[1]);
        json_out(['ok' => true]);
    }

    if ($method === 'GET' && $path === '/customer/bills') {
        $customer = require_customer();
        json_out([
            'currency_symbol' => settings()['currency_symbol'],
            'cafe_name' => settings()['cafe_name'],
            'bills' => customer_bills((int) $customer['id']),
        ]);
    }

    if ($method === 'POST' && preg_match('#^/customer/orders/(\d+)/pay$#', $path, $matches)) {
        $customer = require_customer();
        $type = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
        $payment = '';
        $slip = null;
        if (str_contains($type, 'multipart/form-data')) {
            $payment = (string) ($_POST['payment_method'] ?? '');
            if (isset($_FILES['slip']) && is_array($_FILES['slip']) && (int) ($_FILES['slip']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $slip = $_FILES['slip'];
            }
        } else {
            $body = read_json();
            $payment = (string) ($body['payment_method'] ?? '');
        }
        json_out(customer_pay_bill((int) $customer['id'], (int) $matches[1], $payment, $slip));
    }

    json_error(404, 'Not found.');
} catch (AppError $error) {
    json_error($error->status, $error->getMessage());
} catch (PDOException $error) {
    json_error(500, 'Database error. Start MySQL and import database/schema.sql.');
}
