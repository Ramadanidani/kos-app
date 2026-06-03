<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{
    protected $fillable = [
        'bank_name', 'account_number', 'account_name',
        'qris_image', 'whatsapp', 'notes'
    ];
}