<?php

namespace wcf\acp\action;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use wcf\acp\page\KasMailPage;
use wcf\action\AbstractAction;
use wcf\system\cache\builder\KasMaillistCacheBuilder;
use wcf\system\request\LinkHandler;

final class KasMaillistResetListAction extends AbstractAction
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

        KasMaillistCacheBuilder::getInstance()->reset();

        $this->executed();

        if (isset($_POST['noRedirect'])) {
            return new EmptyResponse();
        } else {
            return new RedirectResponse(
                LinkHandler::getInstance()->getControllerLink(KasMailPage::class, ['section' => 'list'])
            );
        }
    }
}
