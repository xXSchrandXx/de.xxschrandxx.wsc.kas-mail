<?php

namespace wcf\acp\action;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use wcf\acp\page\KasMailPage;
use wcf\action\AbstractAction;
use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\request\LinkHandler;

final class KasMailResetListAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public $neededPermission = ['admin.kas.canManageMails'];

    /**
     * @inheritDoc
     */
    public function execute()
    {
        parent::execute();

        KasMailCacheBuilder::getInstance()->reset();

        $this->executed();

        if (isset($_POST['noRedirect'])) {
            return new EmptyResponse();
        } else {
            return new RedirectResponse(
                LinkHandler::getInstance()->getControllerLink(KasMailPage::class)
            );
        }
    }
}