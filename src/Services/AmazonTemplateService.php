<?php

namespace EdgarMendozaTech\AmazonAffiliates\Services;

use EdgarMendozaTech\Trackables\IpService;
use EdgarMendozaTech\AmazonAffiliates\Models\AmazonStore;
use EdgarMendozaTech\AmazonAffiliates\Models\AmazonLink;

class AmazonTemplateService
{
    public function compile(string $content): string
    {
        $codeLinks = $this->getCodeLinks($content);

        if(count($codeLinks) === 0) {
            return $content;
        }

        if(count($codeLinks[0]) === 0) {
            return $content;
        }

        $store = $this->getStore();

        foreach($codeLinks as $codeLink) {
            if(count($codeLink) > 0) {
                $codeLink = $codeLink[0];
                list($style, $code) = $this->getDataFromCode($codeLink);
                $content = $this->replaceLinkCodeWithHtml($content, $codeLink, $style, $code, $store);
            }
        }

        return $content;
    }

    public function getAmazonStoreUrlFrom(string $code): string
    {
        $store = $this->getStore();
        $link = $store->links()->where('code', $code)->first();
        return $link->pivot->url;
    }

    private function getCodeLinks($content)
    {
        $pattern = "/\@\[amz [b|i] \w+\]/";
        preg_match_all($pattern, $content, $matchs);
        return $matchs;
    }

    private function getDataFromCode(string $command)
    {
        $command = substr($command, 6);
        $command = substr($command, 0, strlen($command) - 1);
        $parts = explode(" ", $command);
        $style = $parts[0];
        $code = $parts[1];
        return [$style, $code];
    }

    private function getStore(): AmazonStore
    {
        if(session()->has('amazonStoreId')) {
            return AmazonStore::with('links')->find(session('amazonStoreId'));
        }

        $ipService = new IpService();
        $countryISO = $ipService->ip_info('Visitor', 'Country Code');

        $amazonStore = AmazonStore::with('links')->where('country_iso', $countryISO)->first();
        if($amazonStore === null) {
            $amazonStore = AmazonStore::with('links')->where('default', true)->first();
            if($amazonStore === null) {
                return Exception("No hay tienda por defecto");
            }
        }

        session(['amazonStoreId' => $amazonStore->id]);

        return $amazonStore;
    }

    private function replaceLinkCodeWithHtml($content, $command, $style, $code, $store)
    {
        $link = $store->links()->where('code', $code)->first();

        if($link === null) {
            return $content;
        }

        $htmlTemplate = "";
        if($style === "i") {
            $htmlTemplate = $this->getInlineLink($link);
        }
        else if($style === "b") {
            $htmlTemplate = $this->getInlineBlockLink($link);
        }

        $content = str_replace($command, $htmlTemplate, $content);

        return $content;
    }

    private function getInlineLink(AmazonLink $link)
    {
        return "<a href='{$link->pivot->url}' class='amazon-i' target='_blank' rel='nofollow noopener'>{$link->name}</a>";
    }

    private function getInlineBlockLink(AmazonLink $link)
    {
        $mediaResource = $link->mediaResources()->first();
        $imageTemplate = "";

        if($mediaResource !== null) {
            $image = $mediaResource;
            $thumbnail = $mediaResource->mediaResources()->first();
            if($thumbnail !== null) {
                $image = $thumbnail;
            }

            $imageTemplate = "
                <div class='image'>
                    <a href='{$link->pivot->url}' target='_blank' rel='nofollow noopener'><img class='lazyload' data-src='{$image->url}'></a>
                </div>";
        }

        return "<div class='amazon-b'>
            {$imageTemplate}
            <div class='info'>
                <a href='{$link->pivot->url}' target='_blank' rel='nofollow noopener' class='link-name'>{$link->name}</a>
                <div class='link-button-wrapper'>
                    <a href='{$link->pivot->url}' target='_blank' rel='nofollow noopener' class='link-button'>Ver en Amazon</a>
                </div>
            </div>
        </div>";
    }
}
