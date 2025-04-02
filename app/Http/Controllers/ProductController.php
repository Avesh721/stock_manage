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

use Barryvdh\DomPDF\Facade\Pdf;





class ProductController extends Controller
{
    public function product_data(request $request){

        $valid = Validator::make($request->all(), [
            'product_name' => 'required',
            'sku' => 'required|unique:products,sku',
             'quantity' => 'required|integer|min:1',
             'price'=>'required|integer|min:1',
        ]);

        if (!$valid->passes()) {
            return response()->json(['status' => 'error', 'error' => $valid->errors()->toArray()]);
        }else{

            $user = Auth::user();

            if(isset($user)){

                $arr=[
                    'name'=>$request->product_name,
                    'sku'=>$request->sku,
                    'quantity'=>$request->quantity,
                    'price'=>$request->price,
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata'),
                  'updated_at' => Carbon::now(),


                ];

                $insert_id = DB::table('products')->insertGetId($arr);

                if($insert_id> 0){

                    $arr1=[

                        'product_id'=>$insert_id,
                        'user_id'=>$user->id,
                        'type'=>'IN',
                        'stock_quantity'=>$request->quantity,
                        'remarks'=>"",
                        'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now(),


                    ];

                    $insert_stock=DB::table('stock_movements')->insertGetId($arr1);

                    if($insert_stock > 0){


                        return response()->json(['status'=>'success','msg'=>'product inserted suceesfully ']);
                    }


                }else{

                    return response()->json(['status'=>400,'msg'=>'something went wrong']);
                }

            }


        }

      }

      public function stock_movement(Request $request)
      {
          // Fetch stock movements with product details, ordered by latest created_at
          $query = StockMovement::with('product')->orderBy('created_at', 'desc');

          $data = $query->get()->map(function ($movement) {
              $recentQty = $movement->recent_quantity;
              $stockQty = $movement->stock_quantity;
              $movement->difference = $stockQty;
              $movement->type = "Added";

              if (!is_null($recentQty)) {
                  if ($stockQty > $recentQty) {
                      $movement->type = "OUT";
                      $movement->difference = $stockQty - $recentQty;
                  } elseif ($stockQty < $recentQty) {
                      $movement->type = "IN";
                      $movement->difference = $recentQty - $stockQty;
                  }
              }

              // 📝 Set the Remarks
              $movement->remarks = "{$movement->difference} {$movement->type} on " . $movement->created_at->format('Y-m-d H:i:s');

              return $movement;
          });

          // Fetch products ordered by latest created_at timestamp
          $products = Product::orderBy('created_at', 'desc')->get();

          return view('stock_movement', compact('data', 'products'));
      }



      public function edit_product_data(request $request){


        $valid = Validator::make($request->all(), [
            'pro_name' => 'required',
            'edit_quantity' => 'required|integer|min:1',
            'edit_price' => 'required|integer|min:1',
        ], [
            'pro_name.required' => 'The product name field is required.', // Custom error message
        ]);

        if (!$valid->passes()) {
            return response()->json(['status' => 'error', 'error' => $valid->errors()->toArray()]);
        }else{

            $product_id=$request->product_id;
            $quantity=$request->edit_quantity;
            if (isset($product_id) && isset($quantity)) {

                $compare_q = DB::table('products')->where('id', $product_id)->first();

                if ($compare_q) {
                    if ($compare_q->quantity != $quantity) {


                       $update=DB::table('products')->where('id',$request->product_id)->update([
                            'name' => $request->pro_name,
                            'sku'=>$request->edit_sku,
                            'quantity'=>$request->edit_quantity,
                            'price'=>$request->edit_price,
                            'updated_at' => Carbon::now(),


                        ]);

                        if($update){
                            $quantity_check = ($quantity < $compare_q->quantity) ? 'OUT' : 'IN';

                            $user = Auth::user();

                            $arr=[

                                'product_id'=>$request->product_id,
                                'user_id'=>$user->id,
                                'type'=>$quantity_check,
                                'stock_quantity'=>$compare_q->quantity,
                                'recent_quantity'=>$quantity,
                                'created_at'=>Carbon::now(),
                                'updated_at' => Carbon::now(),

                            ];

                            $insert_get=DB::table('stock_movements')->insertGetId($arr);

                            if($insert_get> 0){

                                   return response()->json(['status' => 'success', 'msg' => 'product updated succesfully'], 200);

                            }else{

                                return response()->json(['status' => 400, 'msg' => 'something went wrong' ], 400);

                            }



                        }else{

                            return response()->json(['status' => 404, 'msg' => 'something went wrong' ], 404);
                        }

                    } else {


                        $update = DB::table('products')->where('id', $request->product_id)->update([
                            'name' => $request->pro_name,
                            'sku' => $request->edit_sku,
                            'quantity' => $request->edit_quantity,
                            'price' => $request->edit_price,
                        ]);

                        if ($update) {
                            DB::table('products')->where('id', $request->product_id)->update([
                                'updated_at' => Carbon::now(),
                            ]);

                            return response()->json(['status' => 'success', 'msg' => 'Product updated successfully'], 200);
                        }else{


                            return response()->json(['status' => 'success', 'msg' => 'No changes were made'], 200);

                        }
                    }
                } else {
                    return response()->json(['status' => 404, 'msg' => 'Product not found'], 404);
                }

            } else {
                return response()->json(['status' => 400, 'msg' => 'Invalid credentials'], 400);
            }
        }

      }

      public function delete_product(request $request,$id){

        if (!empty($id)) {
$check=DB::table('products')->where('id',$id)->first();

if($check){

    $deleted = Product::find($id)?->delete();

    if ($deleted) {
        return response()->json(['status' => 'success', 'msg' => 'Product deleted successfully'], 200);
    }

}else{
    return response()->json(['status' => 400, 'msg' => 'Invalid credentialssss'], 400);


}



        }else{
            return response()->json(['status' => 400, 'msg' => 'Invalid credentials'], 400);

        }
    }

    public function search_pro(Request $request)
    {
        $query = StockMovement::with('product');

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->orWhereBetween('updated_at', [$startDate, $endDate]);
            });
        }

        // Search by product name
        if ($request->search) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $data = $query->get()->map(function ($movement) {
            $recentQty = $movement->recent_quantity;
            $stockQty = $movement->stock_quantity;
            $movement->difference = $stockQty;
            $movement->type = "Added";

            if (!is_null($recentQty)) {
                if ($stockQty > $recentQty) {
                    $movement->type = "OUT";
                    $movement->difference = $stockQty - $recentQty;
                } elseif ($stockQty < $recentQty) {
                    $movement->type = "IN";
                    $movement->difference = $recentQty - $stockQty;
                }
            }

            $movement->remarks = "{$movement->difference} {$movement->type} on " . $movement->created_at->format('Y-m-d H:i:s');


            return $movement;
        });

        return response()->json(['data' => $data]);
    }

    public  function test(request $request){


        // $data = DB::table('products')->leftJoin
        // ('stock_movements','products.id','=','stock_movements.product_id')->where('products.id','=',15)->get();

    //    $data=Product::with(['name'=>function($query){

    //     $query->where('name','lastone');

    //    }])->get();


// $data=DB::table('products')->leftJoin('stock_movements','products.id','=','stock_movements.product_id')->where('stock_movements.stock_quantity','<',5)->where('products.name','=','last')->get();


// $data = DB::table('products')
//     ->leftJoin('stock_movements', 'products.id', '=', 'stock_movements.product_id')
//     ->where([
//         ['stock_movements.stock_quantity', '<', 5],
//         ['products.name', '=', 'last']
//     ])
//     ->get();




    $data=DB::table('products')->leftJoin('stock_movements','products.id','=','stock_movements.product_id')
    ->where([
        ['stock_movements.stock_quantity','<',5],

    ['products.name','=','last']
    ])
    ->get();


        return $data;


    }

    public function verifyPayment(Request $request)
{
        DB::beginTransaction();

        try {
            // Step 1: Deduct from Payer
            $payer = User::findOrFail($payerId);
            if ($payer->balance < $amount) {
                throw new \Exception("Insufficient funds.");
            }
            $payer->balance -= $amount;
            $payer->save();

            // Step 2: Credit to Payee
            $payee = User::findOrFail($payeeId);
            $payee->balance += $amount;
            $payee->save();

            // Step 3: Log the transaction
            Transaction::create([
                'payer_id' => $payerId,
                'payee_id' => $payeeId,
                'amount' => $amount,
                'status' => 'success'
            ]);

            // Commit transaction if all steps succeed
            DB::commit();
            return response()->json(['message' => 'Payment successful'], 200);

        } catch (\Exception $e) {
            // Rollback on failure
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

     public function test1(){

        //return "hello";

        return view('pdf_data');
     }


}
