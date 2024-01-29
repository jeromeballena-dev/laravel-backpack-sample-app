<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'deals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['submission_date', 'account_id', 'iso_id', 'sales_stage'];
    // protected $hidden = [];
    // protected $dates = [];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    
    public function iso()
    {
        return $this->belongsTo(Iso::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($deal) {
            // Get the account associated with this deal
            $account = Account::find($deal->account_id);

            // Count the existing deals for this account
            $existingDealsCount = Deal::where('account_id', $deal->account_id)->count();

            // Generate the deal name
            $deal->deal_name = $account->business_name . ' ' . ($existingDealsCount + 1);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
