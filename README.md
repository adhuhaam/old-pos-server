# Ithaa Corner POS

Point of sale for Ithaa Corner. It runs on this PC in XAMPP. Waiters use a phone app on the cafe Wi-Fi. The owner uses the admin site on this computer, or from another device on Tailscale.

The cafe is not GST-registered. Bills are receipts. There is no tax, no inventory, and no receipt printer. Currency is MVR. The clock is Maldives time (`Indian/Maldives`, UTC+5).

Dates on screen are `DD MMM YYYY`, for example `21 Oct 2026`. Times are `21 Oct 2026 8:32 PM`. Reports, sales, books, expenses, and hours use a calendar picker for choosing a day or month. Amounts show two decimals and thousands commas, for example `MVR 1,234.50`. Typed amounts may include commas (`1,234.50` or `1000`). Stored amounts stay plain decimals.

## Where to open it

| Who | Address |
| --- | --- |
| This PC | `http://localhost/pos/` |
| Waiter phone on cafe Wi-Fi | `http://192.168.18.8/pos/waiter/` |
| Owner over Tailscale | `http://100.116.152.20/pos/` |

`192.168.18.8` is this PC’s cafe-network address and can change when the router hands out a new one. Check it with `ipconfig`. The Tailscale address is this machine (`ithaa`, `ithaa.snowy-pride.ts.net`) and is not the public internet.

`http://localhost/pos/` sends a signed-in superadmin to the admin site and a signed-in waiter to the phone app. Everyone else goes to the shared sign-in page.

## Sign in

One sign-in page: `http://localhost/pos/login.php`.

- **Superadmin** opens the admin site and can change every module.
- **Manager** opens the phone app, with the same floor as a waiter, and can add an expense or a supply from that app. The admin site stays with the superadmin.
- **Waiter** opens the phone app and keeps a token on that phone.

A brand-new database creates two accounts the first time the site runs, and only if they are missing:

- Superadmin `admin` / `admin`
- Waiter `waiter` / `1234`

Change both before service, under **Users**. This cafe’s superadmin username is `adhuhaam`. Use the password saved on that account. Any waiter still using `1234` should get a new password.

The last active superadmin cannot be demoted, deactivated, or deleted. The account you are signed in with cannot be deleted.

## Who can connect

Waiter and API requests are accepted from:

- this PC (`127.0.0.1`)
- cafe Wi-Fi (`10.x`, `172.16–31.x`, `192.168.x`)
- Tailscale (`100.64–127.x`)

A connection from the public internet is refused. The admin pages are for this PC and Tailscale.

Windows Firewall must allow Apache on private networks (inbound TCP port 80). If a phone cannot open the app, that rule is the first thing to check.

## Waiter app

Install it from Chrome on the phone: menu, then **Install app** or **Add to Home screen**. On iPhone, use Share, then Add to Home Screen. The icon is the Ithaa shell. Installed, it opens without the browser bar, stays upright, and does not zoom. Hold the phone upright. Landscape shows a turn-the-phone message.

The app works in the browser on cafe Wi-Fi. Offline caching of the app shell starts on `localhost` or HTTPS. A plain `http://192.168…` address still runs the sales screens; it does not cache the shell for offline use.

English is the standard language. **বাং / EN** switches the waiter’s own buttons and labels. Receipts, print, and share stay in English. On an open bill in Bangla, the English item name stays first and the Bangla name is a small line under it.

Tabs along the bottom:

### Tables

Hut 1–4, Outdoor 1–4, and Indoor 1, grouped by area. Tap a free table, add items, then leave it. The timer starts when the first item is added.

A table that already has items belongs to the waiter who started it. Another waiter sees “This table is already open” and cannot add to it. An empty open table can be claimed. A superadmin can edit any open table.

### Items

The menu, with a photo, name, and price when a photo has been added. Items are added only after that waiter has their own table or an editable credit bill. Hidden items stay off this list.

### Bill

Every open table bill, refreshed every few seconds. The waiter who owns it can add items, change quantities, and take payment. Other waiters can view it. **Close** leaves the screen. The bill stays on the table until it is paid. An empty table with no items is cleared.

### Credit

Unpaid credit bills: customer, phone, table, item count, date, and the amount still open. Search by name or phone. Open a bill to add items or take cash or bank. Cash or bank closes it. There is no second Credit button on a bill that is already on credit.

If the customer has a limit, it is shown here and when that customer is chosen. A blank limit means no cap. `0.00` means nothing more can be put on credit. Adding items that would push the open total past the limit is blocked. Paying the bill down is always allowed. Lowering a limit does not erase what is already owed.

### Sales

A dashboard for the selected day. Move a day at a time. Future days are not available.

The top shows the day’s closed sales, the bill count, and a bar for cash, bank, and credit. Three tiles filter the list: **Cash**, **Bank**, and **Credit/Hold**. Tap the total to show every bill again. Tap the same tile again to clear that filter. **Day**, **Week**, and **Month** switch the range. Each range shows sales, expenses, Credit/Hold, net sales, and profit, with a small chart. Week and month also list each day and the expenses. A month includes that month’s salaries. Recorded transfer and cash from the books are shown when a period has them, and they are part of profit. Open credit is not part of sales or profit.

Credit on this dashboard is still open and is outside the sales total. Each credit row shows the customer, phone, table, time, limit, and that it is still open. Opening it goes to that bill. **Close** returns to Sales.

A closed bill opens as a receipt. The receipt shows when the bill was opened, when it was paid, and the payment. Items are grouped by the day they were added, so a Credit/Hold bill that grows over more than one day keeps those days. **Share** sends a picture (WhatsApp, Viber, or a saved file named like `ithaa-receipt.png`). **Print** uses the phone’s print dialog, laid out for an 80 mm slip. A bill opened on one day and paid after midnight counts as sales on the payment day.

## Taking payment

On a table bill the waiter chooses **Cash**, **Bank**, or **Credit/Hold**. Credit/Hold keeps the bill with the customer until cash or bank is taken. Those bills are the held bills. The receipt is an old-style bill: bill number from the sale, table, opened and paid times, payment, and a ruled item table with rate and amount. A manager has an Expenses tab for expenses and supplies. A waiter does not. A superadmin sets roles and their access on Users.

**Cash** asks for confirmation, then closes the bill and frees the table. The bill cannot be changed after that.

**Bank** shows the total and the QR or account photo from **Settings**. The customer scans it. The waiter can attach a slip with **Take photo** or **Gallery**, then tap **Customer paid**. The slip is optional. The bill closes without one. Slips are JPEG, up to 8 MB, and are shown on the admin sales cards.

**Credit** asks **Existing customer** or **New customer**.

- Existing: search by name or phone, see what is still open, tap the customer.
- New: name and phone. The phone is 5 to 15 digits and is unique.

The bill stays with that customer, the table is freed, and items can still be added until cash or bank is taken. Credit is not income until it is paid.

**Combine / Settle Bills** is on an open table bill and on a credit bill. The waiter can include or skip the current bill, search a customer by name or phone, and tick any of that customer's open credit bills. Cash or bank settles the ticked bills in one payment. Unticked bills stay as they are. The table is freed only when the current table bill is included. Each bill keeps its own number, items, and date. The receipt lists those bills under one total. Admin sales show the settlement number, such as S00012. Cancelling the screen does not change the bill.

## Receipts

The slip shows:

- Ithaa Corner
- Receipt
- Table, date, and time
- Server, when a waiter name is stored
- Customer, when the bill has one
- Item name, quantity, and line price
- Total
- CASH, BANK, or CREDIT
- THANK YOU

It does not show an address, phone number, tax, invoice title, barcode, cash tendered, or change. Those were never supplied.

## Admin site

Open `http://localhost/pos/admin/` after signing in as superadmin. The sidebar groups are Register, Menu, Floor, Cafe, and Books.

### Today

Open tables, open bills, today’s ticket count, total, cash, bank, and **Held, inside total**. Held is the part of today’s total that came from credit bills paid today. It is already inside the total. The list under the cards is today’s closed bills, with a Held mark and the customer name when those apply.

### Sales

The same figures for any day. The date is a date picker, and the arrows move one day. It opens on today. Filter the list by cash, bank, or credit, and search by table or item. Each card lists the lines and a bank slip when one was saved.

A superadmin can add a sale for a chosen day. Pick a table, cash, bank, or credit, and up to five menu items. Prices come from the menu. Cash and bank join that day’s sales. Credit stays open and needs a customer. A manager cannot add a sale. The bill does not open the table.

Held credit stays off the books until it is paid. It is then added to that day’s sales.

### Customers

A superadmin can add a customer here, with a name, phone, and an optional credit limit. Each credit customer shows what is still open, how much room is left, and a limit field. Save a limit such as `1,000.00`. Leave it blank for no cap. A row is marked when the open amount is already over the limit. Waiters can still add a customer while putting a bill on credit. Adding a customer does not create a sale.

### Items

The menu, grouped like the waiter app. Each group has its own shade. An item only says On menu or Hidden, in plain text. Each card shows the name, price, and photo. Edit, hide or show, and move it up or down inside its category. Search, category chips, and On menu / Hidden filters sit above the list. New items are added at the top.

**Photo** is optional. Choose a JPG, PNG, or WebP under 8 MB on the item form. The waiter menu shows it beside the item. A new photo replaces the old one. Tick **Remove the current photo** and save to take it off. Old bills do not show the photo.

Hiding an item removes it from the waiter menu. Old bills keep the name and price they were sold at. Old demo items stay in the database and stay hidden, so past sales are not deleted.

Current menu, in MVR:

| Item | Price |
| --- | --- |
| Black Coffee Hard | 10 |
| Black Coffee Normal | 7 |
| Milk Coffee | 10 |
| Cereal Coffee | 15 |
| Nuts Half | 5 |
| Nuts Full | 10 |
| Hedhika | 5 |
| Water Bottle Small | 5 |
| Water Bottle Big | 10 |
| Bisket | 5 |
| Lemon Tea | 10 |
| Black Tea Large | 5 |
| Black Tea Small | 3 |

### Categories

Drink and food groups for the menu, shown as cards. Each card opens that group on Items. Order here is the order waiters see.

### Tables

Add, rename, reorder, or hide a table. A hidden table stays off the floor unless it still has an open bill. The floor groups names that start with Hut, Outdoor, and Indoor.

### Users

Name, username, password, role (superadmin, manager, or waiter), and active. Usernames are stored in lowercase. A manager is created here by a superadmin.

### Settings

Currency symbol and the bank photo. The photo is what waiters show on the Bank sheet. Prices and receipts use the symbol. For this cafe it is MVR.

## Books

POS bills that are paid are added on their own, on the day and month of `closed_at`. The spreadsheet days are extra, for transfer and cash that were recorded by hand.

**Income** for a month is POS sales plus recorded transfer and cash.

**Expenses** are salaries plus Supply, Rent, Utility bills, Other, and any category you add.

**Profit** is income minus expenses.

Open credit is left out of income. When that bill is paid, the amount joins the settlement day and month as cash or bank, and the bill is marked Held.

### Dashboard

Year table of POS, transfer, cash, sales, supplies, salary, rent, utilities, other, and profit. The page opens on this month, and this month is first in the month list and the year table. The selected month also has its own summary above the table. Pick another month to edit that month’s recorded days. The day field is a date picker. Saving a date that already exists updates that day. Leave both amounts empty, or enter 0, to clear the day.

The old sales sheet is already entered by date. Each of those days is marked **Previous book** and keeps its transfer and cash. Register bills stay separate. Electricity and rent from that sheet are expenses on the first of the month, noted Previous book. Suman’s August and September salary from that sheet is on Salaries.

Partners have a name and a share percent. The year profit is split by those percents. Shares are edited on this page.

### Salaries

Add a person with a name and designation. Each person has twelve month amounts for the selected year. On the current year, this month is the first amount. Save updates the name, designation, and the months. Remove deletes that person from the books.

**Hours** is under Cafe. It opens on this month, with a month picker. Set an hourly rate, pick the Salaries person, and **Save into Salaries** writes that one month. Other months stay as they are. An open shift counts until clock-out and stops at 16 hours if the phone is left clocked in.

### Expenses

The list opens on this month. Use the month picker to change it, or clear the month and press Show for the whole year. Add or edit a date, category, kind, amount, and note. The date is a date picker. A manager adds an expense or a supply from the phone app, and does not open this site. **Edit** is on each row. **Proof** is an optional photo or PDF (JPG, PNG, WebP, GIF, or PDF, up to 8 MB), meant for a supply receipt and available on any category. The list and the monthly report open the file. Editing keeps the current file unless a new one is chosen or **Remove the current proof** is ticked. Removing the expense removes the file.

Categories start as Supply, Rent, Utility bills, and Other. More categories can be added on the expenses page.

Proof files sit outside the web root. Only a signed-in superadmin can open them.

### Reports

Opens on this month. The month control is a month picker. Cards show income, expenses, profit, POS, recorded days, credit opened this month, and credit still open across all months.

**Daily sales** lists each day that had cash, bank, or open credit. **Sales** lists each bill. Open credit uses the day the bill was opened and is marked Open. A paid bill that began as credit is marked Held and uses the day it was paid.

**Item sales** is under the month picker. From and To are date pickers and start as the first and last day of the month on screen. Clear **To**, or set it to the same day, for a single date. The result is quantity, amount, bill count, a row per date, and each bill that included the item. Hidden items remain in the list so old sales can still be filtered. Paid bills use the day they were closed. Open credit uses the day the bill was opened.

## Setup

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. The site folder is `C:\xampp\htdocs\pos`, served as `http://localhost/pos`. The working copy in Cursor is `C:\Users\User\Desktop\pos`. Copy changed files into the XAMPP folder or the live site keeps the old copy.
3. In phpMyAdmin (`http://localhost/phpmyadmin`), import `database/schema.sql` into a new database. If this cafe is already running, do not import that file again. Opening the admin site once updates older databases (credit, held bills, book tables, proof column).
4. Database settings are in `api/config.php`: host `127.0.0.1`, database `cafe_pos`, user `root`, empty password, timezone `Indian/Maldives`. Set `db_pass` if MySQL has a password.
5. Open `http://localhost/pos/login.php`.

Apache here is XAMPP’s `httpd.exe` (Apache 2.4 with PHP 8.2), not a Windows service named Apache2.4. PHP is `C:\xampp\php\php.exe`.

## What is stored

- **Users** and sign-in tokens. Roles are superadmin, manager, and waiter. An optional hourly rate sits on the user. Clock in and clock out are stored as shifts.
- **Menu** categories and items, with a hidden flag and a sort order.
- **Tables** and which table currently holds an open bill.
- **Sales** with lines. Status is open, credit, or paid. Payment is cash, bank (`online` in the database), or credit. `closed_at` is set when cash or bank is taken, and that day is the sales day. Credit keeps `closed_at` empty and `held` on until it is paid. Each line stores `added_at`, the day that quantity was added. Adding the same item on a later day keeps a separate line.
- **Customers** with name, unique phone, and an optional credit limit. A blank limit is no cap.
- **Books:** partners, recorded days, people, monthly salaries, expense categories, and expenses with an optional proof file.
- **Uploads** under `data/uploads/` (item photos, bank photo, payment slips, expense proofs). The folder is blocked by `.htaccess`. Admin opens files through `admin/media.php`. Waiters load item photos through the API.

Money is stored as decimal maloti and calculated in cents so `1,234.50` cannot drift.

## Day to day

1. Start Apache and MySQL in XAMPP.
2. Waiters open the installed app, or `http://192.168.18.8/pos/waiter/` if the address is still that one.
3. They take tables, cash, bank, and credit from the phone. **Light** switches the phone to a light theme. **Account** changes the signed-in name and password. **Clock in** and **Clock out** record the shift for Hours.
4. You review Today, Sales, Customers, and Reports on this PC.
5. You enter salaries, supplies, rent, and other costs under Books. Attach a photo or PDF when a supply needs proof.
6. Held credit shows up in sales on the day it is paid, not on the day the tab was opened.
