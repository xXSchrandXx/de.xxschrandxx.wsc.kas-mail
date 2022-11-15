<?php

namespace wcf\acp\form;

use wcf\form\AbstractFormBuilderForm;
use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\event\EventHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\kas\DomainSingleSelectionFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\PasswordFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\kas\KasApi;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class KasMailAddForm extends AbstractFormBuilderForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.configuration.kas.kasMailAddForm';

    /**
     * @inheritDoc
     */
    public $neededPermission = ['admin.kas.canManageMails'];

    /**
     * @inheritDoc
     */
    public $objectEditLinkController = KasMailEditForm::class;

    /**
     * Cached mail to edit
     * @var array
     */
    public $formObject;

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if ($this->formAction === 'edit') {
            $mailLogin = "";
            if (isset($_REQUEST['mail_login'])) {
                $mailLogin = (string)$_REQUEST['mail_login'];
            }
            foreach (KasMailCacheBuilder::getInstance()->getData() as $mail) {
                if ($mail['mail_login'] !== $mailLogin) {
                    continue;
                }
                $this->formObject = $mail;
                break;
            }
            if (!isset($this->formObject)) {
                throw new IllegalLinkException();
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function createForm()
    {
        parent::createForm();

        if ($this->formAction === 'edit') {
            $adresses = explode(',', $this->formObject['mail_adresses']);
            $adress = explode('@', $adresses[0]);
            $localPart = $adress[0];
            $nodes = [
                SingleSelectionFormField::create('is_active')
                ->label('wcf.acp.form.kasMail.is_active')
                ->options([
                    0 => [
                        'label' => 'wcf.global.form.boolean.yes',
                        'value' => 'Y',
                        'depth' => 0
                    ],
                    1 => [
                        'label' => 'wcf.global.form.boolean.no',
                        'value' => 'N',
                        'depth' => 0
                    ],
                    3 => [
                        'lavel' => 'wcf.global.form.forbidden',
                        'value' => 'forbidden',
                        'depth' => 0
                    ]
                ], true)
                ->value(($this->formAction == 'edit' && isset($this->formObject['is_active'])) ? $this->formObject['is_active'] : 'Y'),

            ];
        } else {
            $nodes = [
                TextFormField::create('local_part')
                    ->label('wcf.acp.form.kasMail.local_part')
                    ->value(($this->formAction == 'edit' && isset($localPart)) ? $localPart : '')
                    ->required(),
                DomainSingleSelectionFormField::create('domain_part')
                    ->required()
                ];
        }

        \array_push($nodes,
            PasswordFormField::create('mail_password')
                ->placeholder(($this->formAction === 'edit') ? 'wcf.acp.updateServer.loginPassword.noChange' : '')
                ->required(($this->formAction === 'edit') ? 0 : 1),
            SingleSelectionFormField::create('show_password')
                ->label('wcf.acp.form.kasMail.show_password')
                ->options([
                    0 => [
                        'label' => 'wcf.global.form.boolean.yes',
                        'value' => 'Y',
                        'depth' => 0
                    ],
                    1 => [
                        'label' => 'wcf.global.form.boolean.no',
                        'value' => 'N',
                        'depth' => 0
                    ]
                ], true)
                ->value(($this->formAction == 'edit' && isset($this->formObject['show_password'])) ? $this->formObject['show_password'] : 'N')
                ->required(),
            // TODO Add start end time
            SingleSelectionFormField::create('responder')
                ->label('wcf.acp.form.kasMail.responder')
                ->options([
                    0 => [
                        'label' => 'wcf.global.form.boolean.no',
                        'value' => 'N',
                        'depth' => 0
                    ],
                    1 => [
                        'label' => 'wcf.global.form.boolean.yes',
                        'value' => 'Y',
                        'depth' => 0
                    ]
                ], true)
                ->value(($this->formAction == 'edit' && isset($this->formObject['responder'])) ? $this->formObject['responder'] : 'N'),
            SingleSelectionFormField::create('mail_responder_content_type')
                ->label('wcf.acp.form.kasMail.mail_responder_content_type')
                ->options([
                    0 => [
                        'label' => 'text',
                        'value' => 'text',
                        'depth' => 0
                    ],
                    1 => [
                        'label' => 'html',
                        'value' => 'html',
                        'depth' => 0
                    ]
                ], true)
                ->value(($this->formAction == 'edit' && isset($this->formObject['mail_responder_content_type'])) ? $this->formObject['mail_responder_content_type'] : 'text'),
            TextFormField::create('mail_responder_displayname')
                ->label('wcf.acp.form.kasMail.mail_responder_displayname')
                ->value(($this->formAction == 'edit' && isset($this->formObject['mail_responder_displayname'])) ? $this->formObject['mail_responder_displayname'] : ''),
            MultilineTextFormField::create('responder_text')
                ->label('wcf.acp.form.kasMail.responder_text')
                ->value(($this->formAction == 'edit' && isset($this->formObject['responder_text'])) ? $this->formObject['responder_text'] : ''),
            TextFormField::create('copy_adress')
                ->label('wcf.acp.form.kasMail.copy_adress')
                ->value(($this->formAction == 'edit' && isset($this->formObject['copy_adress'])) ? $this->formObject['copy_adress'] : '')
                ->description('wcf.acp.form.kasMail.copy_adress.description'),
            TextFormField::create('mail_sender_alias')
                ->label('wcf.acp.form.kasMail.mail_sender_alias')
                ->value(($this->formAction == 'edit' && isset($this->formObject['mail_sender_alias'])) ? $this->formObject['mail_sender_alias'] : '')
        );

        $container = $this->form->appendChild(
            FormContainer::create('data')
                ->appendChildren($nodes)
        );
    }

    /**
     * @inheritDoc
     */
    public function readData()
    {
        if (!empty($_POST) || !empty($_FILES)) {
            $this->submit();
        }

        // call readData event
        EventHandler::getInstance()->fireAction($this, 'readData');

        $parameters = [];
        if (isset($this->formObject)) {
            $parameters['mail_login'] = $this->formObject['mail_login'];
        }
        $this->form->action(LinkHandler::getInstance()->getControllerLink(static::class, $parameters));
    }

    /**
     * @inheritDoc
     */
    public function save()
    {
        // call save event
        EventHandler::getInstance()->fireAction($this, 'save');

        $formData = $this->form->getData();
        if (!isset($formData['data'])) {
            $formData['data'] = [];
        }
        $formData['data'] = \array_merge($this->additionalFields, $formData['data']);

        if ($this->formAction === 'edit') {
            $formData['data']['mail_login'] = $this->formObject['mail_login'];
            if (!empty($formData['data']['mail_password'])) {
                $formData['data']['mail_new_password'] = $formData['data']['mail_password'];
            }
            unset($formData['data']['mail_password']);
        }

        foreach (['mail_responder_displayname', 'responder_text', 'copy_adress', 'mail_sender_alias'] as $entry) {
            if (isset($formData['data'][$entry]) && empty($formData['data'][$entry])) {
                unset($formData['data'][$entry]);
            }
        }

        try {
            $kasAPI = new KasApi();
            if ($this->formAction === 'create') {
                $kasAPI->add_mailaccount($formData['data']);
            } else {
                $kasAPI->update_mailaccount($formData['data']);
            }
        } catch (\KasApi\KasApiException $e) {
            WCF::getTPL()->assign([
                'faultCode' => $e->getFaultcode(),
                'faultString' => $e->getFaultstring()
            ]);
            return;
        }

        $this->saved();

        WCF::getTPL()->assign('success', true);
    }

    /**
     * @inheritDoc
     */
    public function saved()
    {
        parent::saved();

        KasMailCacheBuilder::getInstance()->reset();
    }
}
