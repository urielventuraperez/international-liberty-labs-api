<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flight_time', 'notify_by_email', 'folio'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }

}
