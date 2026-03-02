<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PesapalService;

class PesapalController extends Controller
{
    protected $pesapal;

    public function __construct(PesapalService $pesapal)
    {
        $this->pesapal = $pesapal;
    }

    public function registerIPN()
    {
        $result = $this->pesapal->registerIPN();
        return response()->json($result);
    }

    public function pay()
    {
        $order = [
            'id'               => uniqid(), // Your system ID
            'currency'         => 'KES',
            'amount'           => 1000,
            'description'      => 'Product Purchase',
            'callback_url'     => config('pesapal.callback_url'),
            'notification_id'  => 'your-registered-ipn-id', // Get from getIPNList()
            'billing_address'  => [
                'email_address' => 'test@example.com',
                'phone_number'  => '0700000000',
                'first_name'    => 'Test',
                'last_name'     => 'User'
            ]
        ];

        $result = $this->pesapal->submitOrder($order);
        return redirect()->to($result['redirect_url']);
    }

    public function ipnListener(Request $request)
    {
        $trackingId = $request->get('OrderTrackingId');
        $status     = $this->pesapal->getTransactionStatus($trackingId);

        // Save status in DB or perform your logic here
        return response()->json($status);
    }
}
