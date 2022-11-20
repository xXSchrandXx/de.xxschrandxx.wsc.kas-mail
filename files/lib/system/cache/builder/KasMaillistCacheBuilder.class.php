<?php

namespace wcf\system\cache\builder;

use wcf\system\kas\KasApi;

class KasMaillistCacheBuilder extends AbstractCacheBuilder
{
    public function rebuild(array $parameters)
    {
        $data = [];
        try {
            $api = new KasApi();
            $data = $api->get_mailforwards();
        } catch (\KasApi\KasApiException $e) {
            // TODO
            \wcf\functions\exception\logThrowable($e);
        }
        return $data;
    }
}
