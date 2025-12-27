<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('email', '!=', 'admin@admin.com')->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) return;

        for ($o = 1; $o <= 8; $o++) {
            $user = $users->random();

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $user->id,
                'total' => 0,
                'status' => collect(['pending', 'in_progress', 'completed'])->random(),
                'payment_status' => collect(['unpaid', 'paid'])->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemsCount = rand(1, 5);
            $picked = $products->random(min($itemsCount, $products->count()));

            $total = 0;

            foreach ($picked as $product) {
                $qty = rand(1, 3);
                $unit = (float) ($product->price ?? rand(5, 100));
                $line = $unit * $qty;
                $total += $line;

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $line,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('orders')->where('id', $orderId)->update([
                'total' => $total,
                'updated_at' => now(),
            ]);
        }
    }
}
