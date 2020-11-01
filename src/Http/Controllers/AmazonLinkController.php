<?php

namespace EdgarMendozaTech\AmazonAffiliates\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use EdgarMendozaTech\AmazonAffiliates\Http\Requests\AmazonLinkRequest;
use EdgarMendozaTech\AmazonAffiliates\Services\AmazonLinkService;
use EdgarMendozaTech\AmazonAffiliates\Services\AmazonStoreService;
use EdgarMendozaTech\AmazonAffiliates\Models\AmazonStore;
use EdgarMendozaTech\AmazonAffiliates\Models\AmazonLink;

class AmazonLinkController extends Controller
{
    private $amazonLinkService;
    private $amazonStoreService;

    public function __construct()
    {
        $this->amazonLinkService = new AmazonLinkService();
        $this->amazonStoreService = new AmazonStoreService();
    }

    public function index(Request $request)
    {
        $amazonLinks = AmazonLink::with(['stores'])
            ->withCount(['stores'])
            ->orderBy('name', 'asc')
            ->paginate(28);
        $amazonLinksCount = AmazonLink::count();

        return [
            'items' => $amazonLinks,
            'items_count' => $amazonLinksCount,
        ];
    }

    public function list(Request $request)
    {
        $links = AmazonLink::orderBy('name')->get();

        return [
            'links' => $links,
        ];
    }

    public function store(AmazonLinkRequest $request)
    {
        $link = $this->amazonLinkService->store($request->all());
        $stores = $this->amazonStoreService->list();

        return [
            'link' => $link,
            'stores' => $stores,
        ];
    }

    public function edit(Request $request, AmazonLink $link)
    {
        $stores = $this->amazonStoreService->list();

        $link->load(['mediaResources', 'stores']);

        return [
            'link' => $link,
            'stores' => $stores,
        ];
    }

    public function update(AmazonLinkRequest $request, AmazonLink $link)
    {
        $link = $this->amazonLinkService->update($link, $request->all());

        $stores = $this->amazonStoreService->list();

        return [
            'link' => $link,
            'stores' => $stores,
        ];
    }

    public function destroy(Request $request, AmazonLink $link)
    {
        return $this->amazonLinkService->destroy($link);
    }
}
