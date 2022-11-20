<?php

namespace wcf\page;

use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\cache\builder\KasMaillistCacheBuilder;
use wcf\system\WCF;
use wcf\util\ArrayUtil;

class KasMailPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $loginRequired = true;

    /**
     * @inheritDoc
     */
    public $neededPermission = ['mod.kas.canSeeMails'];

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
        parent::readParameters();

        if (isset($_REQUEST['section'])) {
            $this->section = $_REQUEST['section'];
        }
        if ($this->section === 'mail') {
            $allowed = ArrayUtil::trim(\explode(
                "\n",
                WCF::getSession()->getPermission('mod.kas.mailList')
            ));
            foreach (KasMailCacheBuilder::getInstance()->getData() as $mail) {
                if (!(in_array($mail['mail_login'], $allowed) || in_array($mail['mail_adresses'], $allowed))) {
                    continue;
                }
                \array_push($this->mails, $mail);
            }
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
