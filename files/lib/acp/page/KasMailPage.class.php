<?php

namespace wcf\acp\page;

use wcf\page\AbstractPage;
use wcf\system\kas\KasApi;
use wcf\system\WCF;

class KasMailPage extends AbstractPage
{
    protected $kasApi;
    protected $mails;

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        $this->kasApi = new KasApi();
        try {
            $this->mails = $this->kasApi->get_mailaccounts();
        } catch (\KasApi\KasApiException $e) {
            // TODO
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        // assign sorting parameters
        WCF::getTPL()->assign([
            'mails' => $this->mails
        ]);
    }
}
