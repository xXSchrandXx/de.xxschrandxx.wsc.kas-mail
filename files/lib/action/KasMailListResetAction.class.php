<?php
namespace wcf\action;

use Laminas\Diactoros\Response\JsonResponse;
use wcf\system\cache\builder\KasMailCacheBuilder;

class KasMailListResetAction extends AbstractSecureAction {
    /**
     * @inheritDoc
     */
    public function execute()
    {
        parent::execute();

        KasMailCacheBuilder::getInstance()->reset();

        return new JsonResponse([true]);
    }
}
