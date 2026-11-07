<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;
use Throwable;

class ScanAllCreateTest extends TestCase
{
    public function test_all_filament_create_pages(): void
    {
        $connection = DB::connection();
        $database = $connection->getDatabaseName();

        $this->assertSame(
            'mysql',
            $connection->getDriverName(),
            'Scanner harus menggunakan MariaDB/MySQL.'
        );

        $this->assertStringEndsWith(
            '_testing',
            $database,
            'Gunakan database khusus berakhiran _testing.'
        );

        $adminEmail = env('FILAMENT_TEST_ADMIN_EMAIL');

        $this->assertNotEmpty(
            $adminEmail,
            'Atur FILAMENT_TEST_ADMIN_EMAIL di .env.testing.'
        );

        $admin = User::query()
            ->where('email', $adminEmail)
            ->first();

        $this->assertNotNull(
            $admin,
            'User admin tidak ditemukan di database testing.'
        );

        $results = [];

        foreach (Filament::getPanels() as $panel) {
            Filament::setCurrentPanel($panel);

            foreach ($panel->getResources() as $resource) {
                $name = class_basename($resource);

                try {
                    $pages = $resource::getPages();

                    if (! isset($pages['create'])) {
                        continue;
                    }

                    $page = $pages['create']->getPage();

                    if (! is_subclass_of($page, CreateRecord::class)) {
                        $results[] = [
                            'resource' => $name,
                            'status' => 'SKIP',
                            'message' => 'Bukan halaman CreateRecord.',
                        ];

                        continue;
                    }

                    $modelClass = $resource::getModel();

                    if (! is_subclass_of($modelClass, Model::class)) {
                        throw new RuntimeException(
                            'Model Eloquent tidak valid.'
                        );
                    }

                    if (! method_exists($modelClass, 'factory')) {
                        $results[] = [
                            'resource' => $name,
                            'status' => 'SKIP',
                            'message' => 'Model factory belum tersedia.',
                        ];

                        continue;
                    }

                    $model = new $modelClass();

                    if (
                        $model->getConnection()->getName()
                        !== DB::getDefaultConnection()
                    ) {
                        $results[] = [
                            'resource' => $name,
                            'status' => 'SKIP',
                            'message' => 'Menggunakan koneksi database lain.',
                        ];

                        continue;
                    }

                    $results[] = $this->scanCreate(
                        $panel,
                        $admin,
                        $resource,
                        $page
                    );
                } catch (Throwable $exception) {
                    $results[] = [
                        'resource' => $name,
                        'status' => 'FAIL',
                        'message' => $exception->getMessage(),
                    ];
                }
            }
        }

        $this->printReport($results);

        $failed = count(array_filter(
            $results,
            fn (array $result): bool =>
                $result['status'] === 'FAIL'
        ));

        $passed = count(array_filter(
            $results,
            fn (array $result): bool =>
                $result['status'] === 'PASS'
        ));

        $this->assertGreaterThan(
            0,
            $passed + $failed,
            'Tidak ada Create Resource yang dapat diuji.'
        );

        $this->assertSame(
            0,
            $failed,
            "{$failed} Resource gagal dalam automated Create test."
        );
    }

    private function scanCreate(
        object $panel,
        User $admin,
        string $resource,
        string $page
    ): array {
        $connection = DB::connection();
        $initialLevel = $connection->transactionLevel();
        $name = class_basename($resource);

        try {
            $connection->beginTransaction();

            $modelClass = $resource::getModel();

            $data = $modelClass::factory()
                ->make()
                ->getAttributes();

            $model = new $modelClass();

            unset(
                $data[$model->getKeyName()],
                $data['created_at'],
                $data['updated_at'],
                $data['deleted_at']
            );

            $before = $modelClass::query()->count();

            Livewire::actingAs(
                $admin,
                $panel->getAuthGuard()
            )
                ->test($page)
                ->fillForm($data)
                ->call('create')
                ->assertHasNoFormErrors();

            $after = $modelClass::query()->count();

            if ($after <= $before) {
                throw new RuntimeException(
                    'Create selesai tetapi record baru tidak ditemukan.'
                );
            }

            return [
                'resource' => $name,
                'status' => 'PASS',
                'message' => 'Create berhasil.',
            ];
        } catch (Throwable $exception) {
            return [
                'resource' => $name,
                'status' => 'FAIL',
                'message' => $exception->getMessage(),
            ];
        } finally {
            while (
                $connection->transactionLevel() > $initialLevel
            ) {
                $connection->rollBack();
            }
        }
    }

    private function printReport(array $results): void
    {
        echo PHP_EOL;
        echo '========== FILAMENT CREATE SCANNER =========='
            . PHP_EOL;

        foreach ($results as $result) {
            $message = preg_replace(
                '/\s+/',
                ' ',
                $result['message']
            );

            echo sprintf(
                '[%s] %s: %s',
                $result['status'],
                $result['resource'],
                mb_substr($message ?? '', 0, 350)
            ) . PHP_EOL;
        }

        $counts = array_count_values(
            array_column($results, 'status')
        );

        echo PHP_EOL;
        echo 'Total : ' . count($results) . PHP_EOL;
        echo 'PASS  : ' . ($counts['PASS'] ?? 0) . PHP_EOL;
        echo 'FAIL  : ' . ($counts['FAIL'] ?? 0) . PHP_EOL;
        echo 'SKIP  : ' . ($counts['SKIP'] ?? 0) . PHP_EOL;
        echo '============================================='
            . PHP_EOL;
    }
}
