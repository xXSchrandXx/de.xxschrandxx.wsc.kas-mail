<?php

namespace wcf\acp\form;

class KasMailEditForm extends KasMailAddForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.configuration.kas.kasMailPage';

    /**
     * @inheritDoc
     */
    public $formAction = 'edit';
}
