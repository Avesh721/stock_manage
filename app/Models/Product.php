<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'sku', 'quantity', 'price'];


    // Relationship to fetch all stock movements of a product
    public function product()
    {
        return $this->hasMany(StockMovement::class, 'product_id', 'id');
    }

    public function lastMovement()
    {
        return $this->hasOne(StockMovement::class)->latestOfMany();
    }

    public function previousMovement()
    {
        return $this->hasOne(StockMovement::class)
            ->where('id', '<', optional($this->lastMovement)->id)
            ->latestOfMany();
    }
    public function stock_data(){


            return $this->hasMany(StockMovement::class,'product_id','id');


    }
public function name(){

    return $this->hasone(user::class,'name','name');
}

}
