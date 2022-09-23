<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'vend_supplier_id',
        'name',
        'source',
        'version',
        'default_markup',
        'description',
        'first_name',
        'last_name',
        'company',
        'email',
        'phone',
        'mobile',
        'fax',
        'website',
        'twitter',
        'address',
        'activated',
    ];



    public function products() {
        return $this->hasMany(Product::class);
    }
}
