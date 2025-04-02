<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $primaryKey = 'id';

    protected $fillable = ['product_id', 'quantity', 'movement_type', 'created_at'];



    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }
}

?>
