<?php

namespace App\Livewire\Biatech;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PaymentSimulator extends Component
{
    #[Locked]
    public string $invoice;

    public function mount(string $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function simulate(string $scenario): void
    {
        abort_unless(in_array($scenario, ['pending', 'success', 'failed', 'expired', 'review'], true), 422);
        $order = session()->get('biatech_demo_orders.'.$this->invoice);
        abort_unless(is_array($order) && ($order['is_demo'] ?? false) === true, 404);

        [$order['payment_status'], $order['transaction_status'], $order['order_status']] = match ($scenario) {
            'success' => ['paid', 'success', 'completed'],
            'failed' => ['failed', 'not_started', 'cancelled'],
            'expired' => ['expired', 'not_started', 'cancelled'],
            'review' => ['paid', 'unknown', 'needs_review'],
            default => ['pending', 'not_started', 'awaiting_payment'],
        };

        session()->put('biatech_demo_orders.'.$this->invoice, $order);
    }

    public function render(): View
    {
        $order = session()->get('biatech_demo_orders.'.$this->invoice);
        abort_unless(is_array($order), 404);

        return view('livewire.biatech.payment-simulator', compact('order'));
    }
}
