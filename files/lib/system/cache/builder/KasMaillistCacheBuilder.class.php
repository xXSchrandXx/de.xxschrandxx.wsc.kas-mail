<?php

namespace wcf\system\cache\builder;

use wcf\system\kas\KasApi;

class KasMaillistCacheBuilder extends AbstractCacheBuilder
{
    public function rebuild(array $parameters)
    {
        $data = [];
        try {
            $api = new KasApi(null, null, null, true);
            $data = $api->get_mailinglists();
        } catch (\KasApi\KasApiException $e) {
            // TODO
            \wcf\functions\exception\logThrowable($e);
        }
        foreach ($data as $id => $maillist) {
            try {
                $additional = $api->get_mailinglists([
                    'mailinglist_name' => $maillist['mailinglist_name']
                ]);
                if (!isset($additional) || empty($additional) || !array_key_exists('mailinglist_subscriber', $additional)) {
                    $data[$id]['mailinglist_subscriber'] = [];
                    continue;
                }
                $data[$id]['mailinglist_subscriber'] = $additional['mailinglist_subscriber'];
            } catch (\KasApi\KasApiException $e) {
                // TODO
                \wcf\functions\exception\logThrowable($e);
                $data[$id]['mailinglist_subscriber'] = [];
            }
        }
        return $data;
    }
}
