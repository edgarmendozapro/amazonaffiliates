<?php

namespace EdgarMendozaTech\AmazonAffiliates\Services;

use EdgarMendozaTech\AmazonAffiliates\Models\AmazonStore;

class AmazonStoreService
{
    public function list()
    {
        return AmazonStore::orderBy('country_iso', 'asc')->get();
    }
}
