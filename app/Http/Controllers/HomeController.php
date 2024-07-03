<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }

    public function index()
    {
        $data = [
            'products' => Product::all(),
        ];

        return view('home', $data);
    }

    public function pay(Request $request)
    {
        $response = '';
        DB::transaction(function () use ($request, &$response) {
            $transaksi = \App\Models\Transaksi::create([
                'kode'   => 'INV - ' . mt_rand(100000, 999999),
                'produk_id'   => $request->product_id,
                'harga'  => $request->harga,
                'status'   => "Pending",
            ]);

            $payload = [
                'transaction_details' => [
                    'order_id'     => $transaksi->kode,
                    'gross_amount' => $transaksi->harga,
                ],
                'customer_details' => [
                    'first_name' => "Fayyaz",
                    'email'      => "fayyaz@gmail.com",
                ],
                'item_details' => [
                    [
                        'id'            => $transaksi->produk_id,
                        'price'         => $transaksi->harga,
                        'quantity'      => 1,
                        'name'          => "barang",
                        'brand'         => 'nama_brand',
                        'category'      => 'bebas',
                        'merchant_name' => 'latihan',
                    ],
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            $transaksi->snap_token = $snapToken;
            $transaksi->save();

            $response = $snapToken;
        });

        return response()->json([
            'status'     => 'success',
            'snap_token' => $response,
        ]);
    }
}
