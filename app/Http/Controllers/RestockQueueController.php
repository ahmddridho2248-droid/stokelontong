<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestockQueueController extends Controller
{
    public function index()
    {
        $queue = \App\Models\RestockNode::getQueue();
        $products = \App\Models\Product::orderBy('name')->get();

        return view('restocks.index', compact('queue', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        \App\Models\RestockNode::enqueue($request->product_id, $request->quantity);

        return redirect()->route('restocks.index')->with('success', 'Barang berhasil dimasukkan ke antrean.');
    }

    public function process()
    {
        $processedNode = \App\Models\RestockNode::dequeue();

        if ($processedNode) {
            return redirect()->route('restocks.index')->with('success', 'Barang pertama berhasil diproses.');
        }

        return redirect()->route('restocks.index')->with('error', 'Antrean kosong.');
    }
}
