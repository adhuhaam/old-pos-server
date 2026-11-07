const app = document.getElementById('app');
const state = {
  screen: 'auth',
  mode: 'in',
  tab: 'order',
  name: '',
  phone: '',
  password: '',
  profile: null,
  tableId: 0,
  table: null,
  menu: null,
  bills: [],
  payId: 0,
  payMethod: 'cash',
  slipFile: null,
  slipPreview: '',
  bankUrl: '',
  currency: 'MVR',
  busy: false,
  error: '',
  notice: '',
};

function esc(value) {
  return String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
  }[char]));
}

function money(amount) {
  const number = Number(amount || 0);
  const text = number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  return (state.currency || 'MVR') + ' ' + text;
}

function apiUrl(path) {
  return '../api/index.php?route=' + encodeURIComponent(path);
}

async function api(path, options) {
  const headers = {};
  const token = localStorage.getItem('cafe_customer_token');
  if (token) {
    headers.Authorization = 'Bearer ' + token;
    headers['X-Auth-Token'] = token;
  }
  let body;
  if (options && options.form) {
    body = options.form;
  } else if (options && options.body) {
    headers['Content-Type'] = 'application/json';
    body = JSON.stringify(options.body);
  }
  let response;
  try {
    response = await fetch(apiUrl(path), {
      method: options && options.method ? options.method : 'GET',
      headers,
      body,
      cache: 'no-store',
    });
  } catch (error) {
    const failure = new Error('Cannot reach the server. Check the cafe Wi-Fi.');
    failure.status = 0;
    throw failure;
  }
  const data = await response.json().catch(() => ({}));
  if (!response.ok) {
    const failure = new Error(data.error || 'Something went wrong.');
    failure.status = response.status;
    throw failure;
  }
  return data;
}

function tableFromUrl() {
  const value = new URLSearchParams(window.location.search).get('table');
  const id = parseInt(value || '0', 10);
  if (id > 0) {
    sessionStorage.setItem('cafe_customer_table', String(id));
    return id;
  }
  return parseInt(sessionStorage.getItem('cafe_customer_table') || '0', 10) || 0;
}

function clearSlip() {
  if (state.slipPreview) {
    URL.revokeObjectURL(state.slipPreview);
  }
  state.slipFile = null;
  state.slipPreview = '';
}

function render() {
  if (!state.profile) {
    renderAuth();
    return;
  }
  if (state.screen === 'pay') {
    renderPay();
    return;
  }
  if (state.tab === 'bills') {
    renderBills();
    return;
  }
  renderOrder();
}

function renderAuth() {
  const register = state.mode === 'up';
  app.innerHTML =
    '<header class="top"><div><p class="muted">Table order</p><h1>' + esc(window.CAFE_NAME || 'Cafe') + '</h1></div></header>' +
    (state.error ? '<p class="error">' + esc(state.error) + '</p>' : '') +
    '<section class="auth"><div class="picks">' +
      '<button type="button" id="mode-in" class="' + (register ? 'ghost' : 'primary') + '">Sign in</button>' +
      '<button type="button" id="mode-up" class="' + (register ? 'primary' : 'ghost') + '">Register</button>' +
    '</div>' +
    '<p class="muted">' + (register ? 'New here? Add your name, phone, and a password.' : 'Use the phone number and password for your account.') + '</p>' +
    '<form id="auth-form">' +
      (register ? '<label>Name<input id="cust-name" required maxlength="80" value="' + esc(state.name) + '"></label>' : '') +
      '<label>Phone<input id="cust-phone" inputmode="tel" required maxlength="20" value="' + esc(state.phone) + '"></label>' +
      '<label>Password<input id="cust-password" type="password" required minlength="4" maxlength="80"></label>' +
      '<button class="primary" type="submit"' + (state.busy ? ' disabled' : '') + '>' + (register ? 'Create account' : 'Sign in') + '</button>' +
    '</form></section>';
  document.getElementById('mode-in').addEventListener('click', () => { state.mode = 'in'; state.error = ''; render(); });
  document.getElementById('mode-up').addEventListener('click', () => { state.mode = 'up'; state.error = ''; render(); });
  document.getElementById('auth-form').addEventListener('submit', (event) => {
    event.preventDefault();
    submitAuth();
  });
}

async function submitAuth() {
  state.busy = true;
  state.error = '';
  state.name = (document.getElementById('cust-name') || {}).value || state.name;
  state.phone = document.getElementById('cust-phone').value;
  state.password = document.getElementById('cust-password').value;
  render();
  try {
    const path = state.mode === 'up' ? '/customer/register' : '/customer/login';
    const body = state.mode === 'up'
      ? { name: state.name, phone: state.phone, password: state.password }
      : { phone: state.phone, password: state.password };
    const result = await api(path, { method: 'POST', body });
    localStorage.setItem('cafe_customer_token', result.token);
    state.password = '';
    state.profile = { name: result.name, phone: result.phone };
    state.tab = state.tableId ? 'order' : 'bills';
    await loadHome();
  } catch (error) {
    state.error = error.message;
  } finally {
    state.busy = false;
    render();
  }
}

function chrome(title) {
  return '<header class="top"><div><p class="muted">' + esc(state.profile.name) + '</p><h1>' + esc(title) + '</h1></div>' +
    '<button class="ghost" id="sign-out" type="button">Sign out</button></header>' +
    (state.error ? '<p class="error">' + esc(state.error) + '</p>' : '') +
    (state.notice ? '<p class="muted">' + esc(state.notice) + '</p>' : '');
}

function tabBar() {
  return '<nav class="tabs"><button type="button" data-tab="order" class="' + (state.tab === 'order' ? 'current' : '') + '">Order</button>' +
    '<button type="button" data-tab="bills" class="' + (state.tab === 'bills' ? 'current' : '') + '">Bills</button></nav>';
}

function bindChrome() {
  const out = document.getElementById('sign-out');
  if (out) {
    out.addEventListener('click', async () => {
      try { await api('/customer/logout', { method: 'POST' }); } catch (error) {}
      localStorage.removeItem('cafe_customer_token');
      state.profile = null;
      state.table = null;
      state.menu = null;
      clearSlip();
      render();
    });
  }
  app.querySelectorAll('[data-tab]').forEach((button) => {
    button.addEventListener('click', () => {
      state.tab = button.getAttribute('data-tab') === 'bills' ? 'bills' : 'order';
      state.error = '';
      state.notice = '';
      state.screen = 'home';
      if (state.tab === 'bills') {
        loadBills();
      } else {
        render();
      }
    });
  });
}

function renderOrder() {
  const table = state.table;
  let body = '<p class="muted">Scan the QR code on your table to order.</p>';
  if (table && table.blocked) {
    body = '<p class="card">' + esc(table.message) + '</p>';
  } else if (table && !table.order) {
    body = '<section class="card"><h2>' + esc(table.table_name) + '</h2><p class="muted">Start an order for this table.</p>' +
      '<button class="primary" id="start-order" type="button"' + (state.busy ? ' disabled' : '') + '>Start order</button></section>';
  } else if (table && table.order) {
    body = orderBody(table);
  }
  app.innerHTML = chrome(table && table.table_name ? table.table_name : 'Order') + body + tabBar();
  bindChrome();
  const start = document.getElementById('start-order');
  if (start) start.addEventListener('click', startOrder);
  app.querySelectorAll('[data-add]').forEach((button) => {
    button.addEventListener('click', () => addItem(parseInt(button.getAttribute('data-add'), 10)));
  });
  app.querySelectorAll('[data-qty]').forEach((button) => {
    button.addEventListener('click', () => changeQty(parseInt(button.getAttribute('data-line'), 10), parseInt(button.getAttribute('data-qty'), 10)));
  });
  const pay = document.getElementById('go-pay');
  if (pay) pay.addEventListener('click', () => openPay(state.table.order.id));
  const cancel = document.getElementById('cancel-empty');
  if (cancel) cancel.addEventListener('click', cancelEmpty);
}

function orderBody(table) {
  const order = table.order;
  const waiting = order.pay_review === 'pending';
  const lines = (order.lines || []).map((line) =>
    '<div class="line"><span>' + esc(line.item_name) + '</span><span class="qty">' +
      (waiting
        ? '<strong>' + line.qty + '</strong>'
        : '<button type="button" data-line="' + line.id + '" data-qty="' + (line.qty - 1) + '">−</button>' +
          '<strong>' + line.qty + '</strong>' +
          '<button type="button" data-line="' + line.id + '" data-qty="' + (line.qty + 1) + '">+</button>') +
    '</span></div>'
  ).join('');
  const menu = (state.menu && state.menu.categories || []).map((category) =>
    '<h2>' + esc(category.name) + '</h2>' +
    category.items.map((item) =>
      '<article class="item"><div><strong>' + esc(item.name) + '</strong><div class="muted">' + esc(money(item.price)) + '</div></div>' +
      '<button class="primary" type="button" data-add="' + item.id + '">Add</button></article>'
    ).join('')
  ).join('');
  const empty = (order.lines || []).length === 0;
  return '<section class="card"><h2>Your order · ' + esc(order.bill_no) + '</h2>' +
    (lines || '<p class="muted">Nothing added yet.</p>') +
    '<p><strong>' + esc(money(order.total)) + '</strong></p>' +
    (waiting
      ? '<p class="muted">Payment sent. The cafe will confirm the photo.</p>'
      : (empty
        ? '<button class="ghost" id="cancel-empty" type="button">Leave table</button>'
        : '<button class="primary" id="go-pay" type="button">Pay</button>')) +
    '</section>' + (waiting ? '' : menu);
}

function renderBills() {
  const groups = [
    ['open', 'Open'],
    ['credit', 'Credit/Hold'],
    ['paid', 'Paid'],
  ];
  const body = groups.map(([status, label]) => {
    const rows = state.bills.filter((bill) => bill.status === status);
    if (!rows.length) {
      return '';
    }
    return '<h2>' + label + '</h2>' + rows.map((bill) => {
      const items = (bill.lines || []).map((line) => '<li>' + line.qty + ' × ' + esc(line.item_name) + ' · ' + esc(money(line.line_total)) + '</li>').join('');
      const action = bill.pay_review === 'pending'
        ? '<p class="muted">Waiting for confirmation.</p>'
        : (status === 'paid'
          ? '<p class="muted">' + esc(bill.payment_label || 'Paid') + '</p>'
          : '<button class="primary" type="button" data-pay="' + bill.id + '">Pay ' + esc(money(bill.total)) + '</button>');
      return '<article class="bill"><p class="bill-no">Bill ' + esc(bill.bill_no) + ' · ' + esc(bill.table_name) + '</p>' +
        '<ul>' + items + '</ul><p><strong>' + esc(money(bill.total)) + '</strong></p>' + action + '</article>';
    }).join('');
  }).join('');
  app.innerHTML = chrome('Bills') + (body || '<p class="muted">No bills yet.</p>') + tabBar();
  bindChrome();
  app.querySelectorAll('[data-pay]').forEach((button) => {
    button.addEventListener('click', () => openPay(parseInt(button.getAttribute('data-pay'), 10)));
  });
}

function openPay(id) {
  clearSlip();
  state.payId = id;
  state.payMethod = 'cash';
  state.screen = 'pay';
  state.error = '';
  render();
  loadBankPhoto();
}

function payBill() {
  return state.bills.find((bill) => bill.id === state.payId) || (state.table && state.table.order && state.table.order.id === state.payId ? state.table.order : null);
}

function renderPay() {
  const bill = payBill();
  if (!bill) {
    state.screen = 'home';
    state.tab = 'bills';
    renderBills();
    return;
  }
  const bank = state.payMethod === 'online';
  app.innerHTML = chrome('Pay bill ' + bill.bill_no) +
    '<section class="card"><p>' + esc(bill.table_name) + '</p><p><strong>' + esc(money(bill.total)) + '</strong></p>' +
    '<div class="pay">' +
      '<button type="button" id="pay-cash" class="' + (bank ? 'ghost' : 'primary') + '">Cash</button>' +
      '<button type="button" id="pay-bank" class="' + (bank ? 'primary' : 'ghost') + '">Bank</button>' +
    '</div>' +
    (bank
      ? '<p class="muted">Photo of the payment</p><img class="bank" id="bank-photo" alt="" hidden><p class="muted" id="bank-missing" hidden>No bank photo has been added yet.</p>' +
        (state.slipPreview ? '<img class="proof" src="' + esc(state.slipPreview) + '" alt="">' : '') +
        '<div class="picks"><button class="ghost" id="slip-camera" type="button">Take photo</button><button class="ghost" id="slip-gallery" type="button">Gallery</button></div>' +
        '<input id="slip-camera-file" type="file" accept="image/*" capture="environment" hidden>' +
        '<input id="slip-gallery-file" type="file" accept="image/*" hidden>'
      : '<p class="muted">Cash is recorded when you confirm.</p>') +
    '<button class="primary" id="confirm-pay" type="button"' + (state.busy ? ' disabled' : '') + '>Confirm payment</button>' +
    '<button class="ghost" id="pay-back" type="button">Back</button></section>';
  document.getElementById('pay-cash').addEventListener('click', () => { state.payMethod = 'cash'; render(); });
  document.getElementById('pay-bank').addEventListener('click', () => { state.payMethod = 'online'; render(); loadBankPhoto(); });
  document.getElementById('pay-back').addEventListener('click', () => { state.screen = 'home'; clearSlip(); render(); });
  document.getElementById('confirm-pay').addEventListener('click', confirmPay);
  bindSlip();
}

function bindSlip() {
  const take = (input) => {
    const file = input.files && input.files[0];
    if (!file) return;
    clearSlip();
    state.slipFile = file;
    state.slipPreview = URL.createObjectURL(file);
    render();
    loadBankPhoto();
  };
  const camera = document.getElementById('slip-camera');
  const gallery = document.getElementById('slip-gallery');
  const cameraFile = document.getElementById('slip-camera-file');
  const galleryFile = document.getElementById('slip-gallery-file');
  if (camera && cameraFile) {
    camera.addEventListener('click', () => cameraFile.click());
    cameraFile.addEventListener('change', () => take(cameraFile));
  }
  if (gallery && galleryFile) {
    gallery.addEventListener('click', () => galleryFile.click());
    galleryFile.addEventListener('change', () => take(galleryFile));
  }
}

async function loadBankPhoto() {
  const img = document.getElementById('bank-photo');
  if (!img) return;
  try {
    const headers = {};
    const token = localStorage.getItem('cafe_customer_token');
    if (token) {
      headers.Authorization = 'Bearer ' + token;
      headers['X-Auth-Token'] = token;
    }
    const response = await fetch(apiUrl('/customer/bank-image'), { headers, cache: 'no-store' });
    if (!response.ok) {
      img.hidden = true;
      const missing = document.getElementById('bank-missing');
      if (missing) missing.hidden = false;
      return;
    }
    const blob = await response.blob();
    if (state.bankUrl) URL.revokeObjectURL(state.bankUrl);
    state.bankUrl = URL.createObjectURL(blob);
    const current = document.getElementById('bank-photo');
    if (current) {
      current.src = state.bankUrl;
      current.hidden = false;
    }
  } catch (error) {}
}

async function confirmPay() {
  const bill = payBill();
  if (!bill || state.busy) return;
  if (state.payMethod === 'online' && !state.slipFile) {
    state.error = 'Take a photo of the payment.';
    render();
    loadBankPhoto();
    return;
  }
  state.busy = true;
  state.error = '';
  render();
  try {
    let result;
    if (state.payMethod === 'online') {
      const form = new FormData();
      form.append('payment_method', 'online');
      form.append('slip', state.slipFile);
      result = await api('/customer/orders/' + bill.id + '/pay', { method: 'POST', form });
    } else {
      result = await api('/customer/orders/' + bill.id + '/pay', { method: 'POST', body: { payment_method: 'cash' } });
    }
    clearSlip();
    state.notice = result.waiting
      ? 'Payment sent. The cafe will confirm the photo.'
      : 'Paid ' + money(result.total) + '.';
    state.screen = 'home';
    state.tab = 'bills';
    if (state.table && state.table.order && state.table.order.id === bill.id) {
      state.table.order = result.waiting ? result : null;
    }
    await loadBills();
  } catch (error) {
    state.error = error.message;
    state.busy = false;
    render();
    loadBankPhoto();
  }
}

async function startOrder() {
  state.busy = true;
  state.error = '';
  render();
  try {
    state.table = await api('/customer/tables/' + state.tableId + '/open', { method: 'POST' });
  } catch (error) {
    state.error = error.message;
  } finally {
    state.busy = false;
    render();
  }
}

async function addItem(itemId) {
  if (!state.table || !state.table.order || state.busy) return;
  state.busy = true;
  state.error = '';
  try {
    const order = await api('/customer/orders/' + state.table.order.id + '/lines', { method: 'POST', body: { item_id: itemId, qty: 1 } });
    state.table.order = order;
  } catch (error) {
    state.error = error.message;
  } finally {
    state.busy = false;
    render();
  }
}

async function changeQty(lineId, qty) {
  if (!state.table || !state.table.order || state.busy) return;
  state.busy = true;
  state.error = '';
  try {
    const order = await api('/customer/orders/' + state.table.order.id + '/lines/' + lineId, { method: 'POST', body: { qty: Math.max(0, qty) } });
    state.table.order = order;
  } catch (error) {
    state.error = error.message;
  } finally {
    state.busy = false;
    render();
  }
}

async function cancelEmpty() {
  if (!state.table || !state.table.order) return;
  state.busy = true;
  try {
    await api('/customer/orders/' + state.table.order.id + '/cancel', { method: 'POST' });
    state.table.order = null;
    state.notice = 'Table left empty.';
  } catch (error) {
    state.error = error.message;
  } finally {
    state.busy = false;
    render();
  }
}

async function loadBills() {
  state.busy = true;
  try {
    const result = await api('/customer/bills');
    state.bills = result.bills || [];
    if (result.currency_symbol) state.currency = result.currency_symbol;
    state.error = '';
  } catch (error) {
    if (error.status === 401) {
      localStorage.removeItem('cafe_customer_token');
      state.profile = null;
    } else {
      state.error = error.message;
    }
  } finally {
    state.busy = false;
    render();
  }
}

async function loadHome() {
  state.menu = await api('/customer/menu');
  if (state.menu && state.menu.currency_symbol) state.currency = state.menu.currency_symbol;
  if (state.tableId) {
    state.table = await api('/customer/tables/' + state.tableId);
  }
  await loadBills();
}

async function boot() {
  state.tableId = tableFromUrl();
  const token = localStorage.getItem('cafe_customer_token');
  if (!token) {
    render();
    return;
  }
  try {
    state.profile = await api('/customer/me');
    state.tab = state.tableId ? 'order' : 'bills';
    await loadHome();
  } catch (error) {
    if (error.status === 401) {
      localStorage.removeItem('cafe_customer_token');
      state.profile = null;
    } else {
      state.error = error.message;
    }
    render();
  }
}

boot();
