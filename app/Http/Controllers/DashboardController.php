<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\StockMovement;


class DashboardController extends Controller
{


public function product_d(Request $request)
{

   // return "hello";
    $user = Auth::user();

    if (isset($user)) {
        $product_d = Product::orderBy('created_at', 'desc')->get();
        $stock_data = DB::table('stock_movements')->get();

        // Set the number of days to filter recent movements
        $days = $request->input('days', 1);
        $recentMovementCount = collect($stock_data)
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->count();

        // Check if quantity is added or removed


        return view('dashboard', compact('product_d', 'stock_data', 'days', 'recentMovementCount'));
    }
}

}
