<?php

namespace App\Livewire\Biatech;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TrackOrder extends Component
{
    public string $invoice = '';
    public string $email = '';

    #[Locked]
    public ?array $foundOrder = null;

    public bool $searched = false;

    public function search(): void
    {
        $this->validate([
            'invoice' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:190'],
        ]);

        $invoice = Str::upper(trim($this->invoice));
        $order = session()->get('biatech_demo_orders.'.$invoice);
        $this->searched = true;
        $this->foundOrder = null;

        // The reference and contact must match an order in this browser's session.
        if (is_array($order) && hash_equals((string) ($order['contact'] ?? ''), Str::lower(trim($this->email)))) {
            $this->foundOrder = $order;
        }
    }

    public function render(): View
    {
        return view('livewire.biatech.track-order');
    }
}
