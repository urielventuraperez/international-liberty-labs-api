<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratories extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'licence', 'licence_folio', 'procedure', 'rfc',
        'taxpayer', 'risk', 'last_predial_payment', 'siresol_folio', 'last_siresol_payment',
        'land_use', 'land_use_folio', 'land_use_type', 'land_use_vigency', 'commercial_business',
        'specific_activity', 'digital_file_folio', 'expiration_ocuppation', 'proof_of_ownership',
        'civil_protection', 'civil_protection_vigency'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

}
