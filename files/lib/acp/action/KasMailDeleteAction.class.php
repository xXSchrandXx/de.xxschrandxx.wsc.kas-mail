<?php

namespace wcf\acp\action;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use wcf\acp\page\KasMailPage;
use wcf\action\AbstractAction;
use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\kas\KasApi;
use wcf\system\request\LinkHandler;

final class KasMailDeleteAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public $neededPermission = ['admin.kas.canManageMails'];

    /**
     * Mail Login to delete
     * @var string
     */
    public $mailLogin;

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['mail_login'])) {
            $this->mailLogin = (string)$_REQUEST['mail_login'];
        }
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        parent::execute();

        $kasAPI = new KasApi();
        $kasAPI->delete_mailaccount([
            'mail_login' => $this->mailLogin
        ]);

        $this->executed();

        if (isset($_POST['noRedirect'])) {
            return new EmptyResponse();
        } else {
            return new RedirectResponse(
                LinkHandler::getInstance()->getControllerLink(KasMailPage::class)
            );
        }
    }

    /**
     * @inheritDoc
     */
    protected function executed()
    {
        parent::executed();

        KasMailCacheBuilder::getInstance()->reset();
    }
}