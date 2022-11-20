<?php

namespace wcf\acp\page;

use wcf\page\AbstractPage;
use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\cache\builder\KasMaillistCacheBuilder;
use wcf\system\WCF;

class KasMailPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.configuration.kas.kasMailPage';

    /**
     * @inheritDoc
     */
    public $neededPermission = ['admin.kas.canManageMails'];

    /**
     * @var string
     */
    protected $section = 'mail';

    /**
     * List of cached mails
     * @var array
     */
    protected $mails = [];

    /**
     * List of cached maillists
     * @var array
     */
    protected $maillists = [];

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        if (isset($_REQUEST['section'])) {
            $this->section = $_REQUEST['section'];
        }
        if ($this->section === 'mail') {
            $this->mails = KasMailCacheBuilder::getInstance()->getData();
        } else {
            $this->maillists = KasMaillistCacheBuilder::getInstance()->getData();
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
            'section' => $this->section,
            'mails' => $this->mails,
            'maillists' => $this->maillists
        ]);
    }
}
