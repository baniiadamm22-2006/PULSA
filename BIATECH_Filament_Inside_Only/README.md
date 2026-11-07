# BIATECH — Fill existing Filament v3 Resource interiors (11 menus)

**Scope:** Only replaces the `form()`, `table()`, and `infolist()` methods in existing `*Resource.php` classes. It does not create new resources, routes, panel providers, models, migrations, database rows, or frontend files. It does not alter navigation metadata, permissions, or the boilerplate admin UI.

Source assumptions are based on the BIATECH backend/migration archives previously generated in this project. **The current database was not accessible to the package author**; use the preflight and verify schema before applying.

## Menus included

- Settings, Suppliers, Supplier Products: create/edit forms and searchable/filterable tables.
- Orders, Order Items, Payments, Payment Webhooks, Refunds, Transactions, Transaction Logs, Admin Activity Logs (only if an application Resource exists): tables with filters where useful, detailed View infolists; **read-only** to prevent fabricated payment/status events.

Orders/Payments/Transactions/Refunds appear as real records only after an order/payment/supplier integration creates them. A blank table is normal without transactions; these screens do not activate Midtrans/Digiflazz.

## Installation

1. Place the ZIP in `/root/perkuliahan/pulsa` and unzip.
2. Check that your models/migrations still have the fields from BIATECH's previous implementation; in particular `settings`, `suppliers`, `supplier_products`, `orders`, `order_items`, `payments`, `payment_webhooks`, `refunds`, `transactions`, `transaction_logs`, `admin_activity_logs`.
3. From Docker project root:

```bash
cd /root/perkuliahan/pulsa
python3 BIATECH_Filament_Inside_Only/apply.py
python3 BIATECH_Filament_Inside_Only/apply.py --apply

for x in Setting Supplier SupplierProduct Order OrderItem Payment PaymentWebhook Refund Transaction TransactionLog AdminActivityLog; do
  docker compose exec -T php php -l "app/Filament/Admin/Resources/${x}Resource.php" || exit 1
done

docker compose exec -T -u www-data php php artisan optimize:clear
docker compose exec -T php php artisan route:list --path=admin
```

**Note:** Admin Activity Logs may come from `filament-logger`, not an application Resource; the installer skips it if missing. The lint loop assumes current provider's `App/Filament/Admin/Resources` namespace; the installer supports `App/Filament/Resources` as well. Preflight will refuse ambiguous/missing files; do not bypass it.

## Rollback

```bash
cd /root/perkuliahan/pulsa
python3 BIATECH_Filament_Inside_Only/restore.py
docker compose exec -T -u www-data php php artisan optimize:clear
```

## Testing

1. `/admin/suppliers/create`: name, code, reference, active state. Save one test supplier.
2. `/admin/settings/create`: key `public.site_name`, type `string`, value `BIATECH`.
3. `/admin/supplier-products/create`: choose existing supplier and product (requires Product records in DB). Save unique pair/SKU.
4. Verify list and edit views; check role permissions if the Create action is missing (Filament Shield).
5. Open read-only detail pages for existing order/payment/transaction records.

**Security:** Do not paste gateway secrets into Settings/Suppliers. Settings are restricted to `public.*`. Public API credentials should be stored in environment/secret management, not in this CRUD.
