<?php
namespace wcf\system\cache\builder;

use wcf\system\kas\KasApi;

class KasMailCacheBuilder extends AbstractCacheBuilder {
    public function rebuild(array $parameters) {
        $data = [];
        try {
            $api = new KasApi();
            $data = $api->get_mailaccounts();
        } catch (\KasApi\KasApiException $e) {
            // TODO
            \wcf\functions\exception\logThrowable($e);
        }
        return $data;
    }
}
