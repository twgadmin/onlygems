<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

	protected $fillable = [
		'user_id',
		'order_id',
		'name',
		'grading_co',
		'grading_co_serial_number',
		'year',
		'set',
		'card',
		'parralel',
		'grade',
		'category',
		'estimated_value'
	];
	
	public static function orderDetails($where=[]){
			$query = self::select(
				'orders.status as orderStatus',
				'order_details.*',
				\DB::raw("CONCAT(users.first_name,' ',users.last_name) as user")
			)
    		->leftJoin('users', 'users.id', 'order_details.user_id')
    		->leftJoin('orders', 'orders.id', 'order_details.order_id')
    		->where($where)
			->orderBy('order_details.created_at', 'asc')
    		->get();

    	return $query;
	}
}
