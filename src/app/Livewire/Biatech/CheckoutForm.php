<?php

namespace App\Livewire\Biatech;

use App\Services\Storefront\CatalogService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CheckoutForm extends Component
{
    #[Locked]
    public string $productSlug;

    public string $destination = '';
    public string $email = '';
    public bool $agreed = false;

    public function mount(string $slug): void
    {
        $this->productSlug = $slug;
    }

    public function submit(CatalogService $catalog): mixed
    {
        abort_unless($catalog->isDemo(), 403, 'Checkout database belum diaktifkan. Integrasikan OrderService terlebih dahulu.');

        $product = $catalog->findProduct($this->productSlug);
        abort_if($product === null, 404);

        $isElectricity = $product['category'] === 'token-listrik-pln';

        $this->validate([
            'destination' => $isElectricity
                ? ['required', 'string', 'regex:/^[0-9]{10,13}$/']
                : ['required', 'string', 'regex:/^\+?[0-9]{8,15}$/'],
            'email' => ['required', 'email', 'max:190'],
            'agreed' => ['accepted'],
        ], [
            'destination.regex' => $isElectricity
                ? 'Masukkan nomor meter / ID pelanggan 10–13 digit.'
                : 'Masukkan nomor tujuan valid (8–15 digit; boleh diawali +).',
            'agreed.accepted' => 'Silakan konfirmasi ringkasan pembelian terlebih dahulu.',
        ]);

        $invoice = 'BT-DEMO-'.Str::upper(Str::random(10));
        $order = [
            'invoice' => $invoice,
            'product_slug' => $product['slug'],
            'product_name' => $product['name'],
            'category' => $product['category_name'],
            'destination' => $this->destination,
            'contact' => Str::lower(trim($this->email)),
            'amount' => $product['price'],
            'payment_status' => 'pending',
            'transaction_status' => 'not_started',
            'order_status' => 'awaiting_payment',
            'created_at' => now()->toDateTimeString(),
            'is_demo' => true,
        ];

        // Session-only fake order. No transaction is inserted and no external API is called.
        session()->put('biatech_demo_orders.'.$invoice, $order);

        return redirect()->route('biatech.payment', ['invoice' => $invoice]);
    }

    public function render(CatalogService $catalog): View
    {
        $product = $catalog->findProduct($this->productSlug);
        abort_if($product === null, 404);

        return view('livewire.biatech.checkout-form', compact('product'));
    }
}
