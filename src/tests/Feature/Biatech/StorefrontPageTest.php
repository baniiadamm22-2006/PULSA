<?php

namespace Tests\Feature\Biatech;

use App\Livewire\Biatech\CheckoutForm;
use Livewire\Livewire;
use Tests\TestCase;

class StorefrontPageTest extends TestCase
{
    public function test_storefront_routes_render_using_demo_catalog(): void
    {
        config()->set('biatech-storefront.catalog_source', 'demo');

        $this->get(route('biatech.home'))->assertOk()->assertSee('SEMUA KEBUTUHAN');
        $this->get(route('biatech.catalog'))->assertOk();
        $this->get(route('biatech.product', 'pulsa-telkomsel-25'))->assertOk();
        $this->get(route('biatech.checkout', 'pulsa-telkomsel-25'))->assertOk();
        $this->get(route('biatech.track'))->assertOk();
        $this->get(route('biatech.help'))->assertOk();
    }

    public function test_unknown_product_returns_not_found(): void
    {
        config()->set('biatech-storefront.catalog_source', 'demo');

        $this->get(route('biatech.product', 'produk-tidak-ada'))->assertNotFound();
    }

    public function test_checkout_validates_destination_and_consent(): void
    {
        config()->set('biatech-storefront.catalog_source', 'demo');

        Livewire::test(CheckoutForm::class, ['slug' => 'pulsa-telkomsel-25'])
            ->set('destination', 'abc')
            ->set('email', 'invalid-email')
            ->set('agreed', false)
            ->call('submit')
            ->assertHasErrors(['destination', 'email', 'agreed']);
    }

    public function test_checkout_creates_demo_order_only_in_session(): void
    {
        config()->set('biatech-storefront.catalog_source', 'demo');

        Livewire::test(CheckoutForm::class, ['slug' => 'pulsa-telkomsel-25'])
            ->set('destination', '081234567890')
            ->set('email', 'demo@example.com')
            ->set('agreed', true)
            ->call('submit')
            ->assertHasNoErrors();

        $orders = session('biatech_demo_orders', []);
        $this->assertCount(1, $orders);
        $order = array_values($orders)[0];
        $this->assertTrue($order['is_demo']);
        $this->assertSame('pending', $order['payment_status']);
        $this->assertSame('not_started', $order['transaction_status']);
    }
}
