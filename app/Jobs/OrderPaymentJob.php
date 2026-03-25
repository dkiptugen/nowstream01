<?php

namespace App\Jobs;

use App\Events\PaymentMade;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $orderNumber,
        public $amount,
        public $receipt,
        public $name,
        public $msisdn,
        public $transtime,
        public $response
    ) {
    }

    public function handle(): void
    {
        try {
            $order = Order::where('order_number', $this->orderNumber)
                ->with('items.product')
                ->firstOrFail();

            $transaction = Transaction::find($order->latest_transaction_id);
            if (!is_null($transaction)) {
                $transaction->name = $this->name;
                $transaction->msisdn = $this->msisdn;
                $transaction->receipt = $this->receipt;
                $transaction->amount_paid = $this->amount;
                $transaction->date_paid = Carbon::parse($this->transtime);
                $transaction->response = $this->response;
                $transaction->save();
            }

            $order->payment_status = 'paid';
            $order->paid_at = Carbon::parse($this->transtime);
            $order->save();

            $product = optional($order->items->first())->product;
            if ($product) {
                Ticket::firstOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'event_id' => $product->payable_id,
                    ],
                    [
                        'type' => $product->name,
                        'price' => $order->total_amount,
                    ]
                );
            }

            event(new PaymentMade($order->fresh()));
        } catch (ModelNotFoundException $exception) {
            Log::error('Order payment target not found: ' . $exception->getMessage());
        } catch (Exception $exception) {
            Log::error('Order payment processing failed: ' . $exception->getMessage());
        }
    }
}
