<?php

namespace EdgarMendozaTech\AmazonAffiliates\Models;

use Illuminate\Database\Eloquent\Model;
use EdgarMendozaTech\MediaResource\MediaResource;

class AmazonLink extends Model
{
    protected $table = 'amazon_links';

    protected $fillable = ['name', 'code'];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function mediaResources()
    {
        return $this->belongsToMany(
            MediaResource::class,
            'amazon_link_media_resources',
            'amazon_link_id',
            'media_resource_id'
        );
    }

    public function stores()
    {
        return $this->belongsToMany(
            AmazonStore::class,
            'amazon_store_links',
            'amazon_link_id',
            'amazon_store_id'
        )->withPivot('url');
    }
}
