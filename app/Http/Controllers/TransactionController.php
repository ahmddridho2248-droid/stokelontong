<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating Outbound Transaction (Barang Keluar)
     */
    public function createOut()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('transactions.create-out', compact('products'));
    }

    /**
     * Store Outbound Transaction
     */
    public function storeOut(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'type' => 'out',
                'date' => now()->toDateString(),
                'notes' => $request->notes,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                
                if ($item['quantity'] > $product->stock) {
                    throw new \Exception("Kuantitas untuk produk {$product->name} melebihi stok yang tersedia (Stok: {$product->stock}).");
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Transaksi barang keluar berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating Inbound Transaction (Barang Masuk)
     */
    public function createIn()
    {
        $products = Product::all();
        return view('transactions.create-in', compact('products'));
    }

    /**
     * Store Inbound Transaction
     */
    public function storeIn(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'type' => 'in',
                'date' => now()->toDateString(),
                'notes' => $request->notes,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                ]);

                $product->increment('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Transaksi barang masuk berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // Other standard resource methods can be kept empty for now or implemented later
    public function create() {}
    public function store(Request $request) {}
    public function show(Transaction $transaction) {}
    public function edit(Transaction $transaction) {}
    public function update(Request $request, Transaction $transaction) {}
    public function destroy(Transaction $transaction) {}
}
