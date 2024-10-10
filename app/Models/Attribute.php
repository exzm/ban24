<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Attribute
 *
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    protected $fillable = ['tag', 'name'];
    protected $table = 'attributes';
    public $timestamps = false;

    public function getIconAttribute()
    {
        $icons = [
            'general_payment_type_card'     => '<i class="fal fa-credit-card"></i>',
            'general_payment_type_cash'     => '<i class="fal fa-money-bill-alt"></i>',
            'general_payment_type_bank'     => '<i class="fal fa-university"></i>',
            'general_payment_type_internet' => '<i class="fab fa-internet-explorer"></i>',
            'general_payment_type_visa'     => '<i class="fab fa-cc-visa"></i>',
            'general_payment_type_elkart'   => '<i class="fal fa-credit-card-front"></i>',
            'general_payment_type_alaicard' => '<i class="fal fa-credit-card-front"></i>',
        ];

        return array_get($icons, $this->tag, '');
    }

}
