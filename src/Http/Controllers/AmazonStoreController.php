<?php

namespace EdgarMendozaTech\AmazonAffiliates\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use EdgarMendozaTech\AmazonAffiliates\Models\AmazonStore;

class AmazonStoreController extends Controller
{
    public function index()
    {
        $stores = AmazonStore::withCount(['links'])
            ->orderBy('country_iso', 'asc')
            ->paginate(28);

        return [
            'items' => $stores,
            'items_count' => $stores->count(),
        ];
    }

    public function edit(Request $request, AmazonStore $store)
    {
        return [
            'store' => $store,
        ];
    }

    public function update(Request $request, AmazonStore $store)
    {
        $store->default = $request->default;
        $store->save();

        return [
            'store' => $store,
        ];
    }
}
