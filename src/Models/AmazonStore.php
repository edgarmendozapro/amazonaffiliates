<?php

namespace EdgarMendozaTech\AmazonAffiliates\Models;

use Illuminate\Database\Eloquent\Model;

class AmazonStore extends Model
{
    protected $table = 'amazon_stores';

    protected $fillable = ['country_iso', 'default'];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return "country_iso";
    }

    public function links()
    {
        return $this->belongsToMany(
            AmazonLink::class,
            'amazon_store_links',
            'amazon_store_id',
            'amazon_link_id'
        )->withPivot('url');
    }
}
