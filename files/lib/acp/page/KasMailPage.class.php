<?php

namespace wcf\acp\page;

use wcf\page\AbstractPage;
use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\WCF;

class KasMailPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $neededPermission = ['admin.kas.canManageMails'];

    protected $mails;

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        $this->mails = KasMailCacheBuilder::getInstance()->getData();
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
