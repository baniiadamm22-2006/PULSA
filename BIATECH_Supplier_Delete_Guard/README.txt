BIATECH Supplier Delete Guard (Filament 3)

Files:
1. SupplierResource.php -> src/app/Filament/Admin/Resources/SupplierResource.php
2. SupplierResource/Pages/EditSupplier.php -> src/app/Filament/Admin/Resources/SupplierResource/Pages/EditSupplier.php

The files are based on previously shared BIATECH source; local changes may differ.
Create backups and examine diffs before installing.
No changes to models, data, migrations, .env, or Admin Panel provider.
Supplier Delete checks supplierProducts and transactions; bulk delete disabled.

In project root (/root/perkuliahan/pulsa), after placing this folder:

cp src/app/Filament/Admin/Resources/SupplierResource.php /tmp/Biatech-SupplierResource.before.php
cp src/app/Filament/Admin/Resources/SupplierResource/Pages/EditSupplier.php /tmp/Biatech-EditSupplier.before.php

cp BIATECH_Supplier_Delete_Guard/SupplierResource.php src/app/Filament/Admin/Resources/SupplierResource.php
cp BIATECH_Supplier_Delete_Guard/SupplierResource/Pages/EditSupplier.php src/app/Filament/Admin/Resources/SupplierResource/Pages/EditSupplier.php

docker compose exec -T php php -l app/Filament/Admin/Resources/SupplierResource.php
docker compose exec -T php php -l app/Filament/Admin/Resources/SupplierResource/Pages/EditSupplier.php
docker compose exec -T php php artisan optimize:clear

git diff --check
