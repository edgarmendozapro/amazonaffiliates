<?php

namespace EdgarMendozaTech\AmazonAffiliates\Services;

use EdgarMendozaTech\AmazonAffiliates\Models\AmazonLink;

class AmazonLinkService
{
    public function store(array $data)
    {
        $code = $this->generateCode();
        return $this->setData(new AmazonLink(['code' => $code]), $data);
    }

    public function update(AmazonLink $amazonLink, array $data): AmazonLink
    {
        return $this->setData($amazonLink, $data);
    }

    public function destroy(AmazonLink $amazonLink): array
    {
        $amazonLink->delete();

        return [
            'msg' => 'success',
        ];
    }

    private function generateCode()
    {
        while(true) {
            $generatedCode = $this->randString(5);
            $amazonLink = AmazonLink::where('code', $generatedCode)->first();
            if($amazonLink === null) {
                return $generatedCode;
            }
        }
    }

    private function randString($length) {
        $char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $char = str_shuffle($char);
        for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
            $rand .= $char[mt_rand(0, $l)];
        }
        return $rand;
    }

    private function setData(AmazonLink $amazonLink, array $data): AmazonLink
    {
        $amazonLink->name = $data['name'];
        $amazonLink->save();

        $amazonLink = $this->setMediaResources($amazonLink, $data);
        $amazonLink = $this->setAmazonStores($amazonLink, $data);

        return $amazonLink;
    }

    private function setMediaResources(AmazonLink $amazonLink, array $data): AmazonLink
    {
        $amazonLink->mediaResources()->sync($data['media_resources']);
        return $amazonLink;
    }

    private function setAmazonStores(AmazonLink $amazonLink, array $data): AmazonLink
    {
        $pivotData = [];
        foreach($data['amazon_stores'] as $amazonStoreData) {
            $pivotData[$amazonStoreData['store_id']] = ['url' => $amazonStoreData['url']];
        }
        $amazonLink->stores()->sync($pivotData);

        return $amazonLink;
    }
}
