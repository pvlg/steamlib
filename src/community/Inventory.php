<?php

namespace pvlg\steamlib\community;

class Inventory extends CommunityBase
{
    private function loadInventory($appId, $contextId, $profileId)
    {
        if ($profileId === null) {
            $profileId = $this->steam->getProfileId();
        }

        $response = $this->http->get("profiles/$profileId/inventory/json/$appId/$contextId/");

        if ($response->getStatusCode() !== 200) {
            throw new \Exception();
        }

        $result = json_decode($response->getBody(), true);
        if ($result['success'] !== true) {
            throw new \Exception();
        }

        return $result;
    }

    public function getInventory($appId, $contextId, $profileId = null)
    {
        $inventory = $this->loadInventory($appId, $contextId, $profileId);

        return new \pvlg\steamlib\community\inventory\Inventory($inventory);
    }
}