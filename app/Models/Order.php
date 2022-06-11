<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = ['order'];

    public function contract()
    {

        return $this->hasOne(Contract::class, 'contracts_order_fk');
    }

    public function orderNotes()
    {

        return $this->hasMany(OrderNote::class);
    }

    public function orderNotesLimited()
    {

        $notes  = $this->hasMany(OrderNote::class)->limit(3)->get();
        $count = $this->hasMany(OrderNote::class)->count();

        return ['notes' => $notes, 'count' => $count];
    }

    public function printingProvider()
    {

        return $this->belongsTo(Provider::class);
    }

    public function shippingAgencyProvider()
    {

        return $this->belongsTo(Provider::class, 'order_shipping_provider_id_fk');
    }

    public function orderType()
    {

        return $this->belongsTo(OrderStatus::class, 'order_order_status_fk');
    }
}
