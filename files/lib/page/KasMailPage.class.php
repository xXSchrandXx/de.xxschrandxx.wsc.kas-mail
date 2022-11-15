<?php

namespace wcf\page;

use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\WCF;
use wcf\util\ArrayUtil;

class KasMailPage extends AbstractPage
{
    /**
     * @inheritDoc
     */
    public $neededPermission = ['mod.kas.canSeeMails'];

    /**
     * List of cached mails
     * @var array
     */
    protected $mails = [];

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

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
