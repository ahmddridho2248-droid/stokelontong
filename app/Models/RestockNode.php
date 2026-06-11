<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockNode extends Model
{
    protected $fillable = ['product_id', 'quantity', 'status', 'next_node_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function nextNode()
    {
        return $this->belongsTo(RestockNode::class, 'next_node_id');
    }

    public static function enqueue($productId, $quantity)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($productId, $quantity, &$newNode) {
            $tail = self::where('status', 'pending')
                        ->whereNull('next_node_id')
                        ->lockForUpdate()
                        ->first();

            $newNode = self::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'status' => 'pending',
                'next_node_id' => null,
            ]);

            if ($tail) {
                $tail->update(['next_node_id' => $newNode->id]);
            }
        });

        return $newNode;
    }

    public static function dequeue()
    {
        return \Illuminate\Support\Facades\DB::transaction(function () {
            $head = self::where('status', 'pending')
                        ->whereNotIn('id', function($query) {
                            $query->select('next_node_id')
                                  ->from('restock_nodes')
                                  ->whereNotNull('next_node_id')
                                  ->where('status', 'pending');
                        })
                        ->lockForUpdate()
                        ->first();

            if ($head) {
                $head->update(['status' => 'processed']);
                return $head;
            }

            return null;
        });
    }

    public static function getQueue()
    {
        $pendingNodes = self::with('product')->where('status', 'pending')->get()->keyBy('id');
        
        if ($pendingNodes->isEmpty()) {
            return collect();
        }

        $nextIds = $pendingNodes->pluck('next_node_id')->filter();
        $headId = $pendingNodes->keys()->diff($nextIds)->first();

        $sortedQueue = collect();
        $currentId = $headId;

        while ($currentId && $pendingNodes->has($currentId)) {
            $node = $pendingNodes->get($currentId);
            $sortedQueue->push($node);
            $currentId = $node->next_node_id;
        }

        return $sortedQueue;
    }
}
