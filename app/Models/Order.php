<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

	protected $fillable = [
		'user_id',
		'qty',
		'status',
		'stage',
		'minted',
		'sent_to_wallet'
	];
	
	public static function allOrders($where=[]){
			$query = self::select(
				'orders.*',
				\DB::raw("CONCAT(users.first_name,' ',users.last_name) as user"),
			)
    		->leftJoin('users', 'users.id', 'orders.user_id')
    		->where($where)
			->orderBy('orders.created_at', 'asc')
    		->get();

    	return $query;
	}
}
