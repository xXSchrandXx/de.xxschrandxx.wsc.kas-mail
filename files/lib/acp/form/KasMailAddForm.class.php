<?php

namespace wcf\acp\form;

use wcf\form\AbstractFormBuilderForm;
use wcf\system\cache\builder\KasMailCacheBuilder;
use wcf\system\event\EventHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\IFormField;
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
            $domainPart = $adress[1];
        }

        $this->form->appendChild(
            FormContainer::create('data')
                ->appendChildren([
                    PasswordFormField::create('mail_password')
                        ->placeholder(($this->formAction === 'edit') ? 'wcf.acp.updateServer.loginPassword.noChange' : '')
                        ->required(($this->formAction === 'edit') ? 0 : 1),
                    SingleSelectionFormField::create('show_password')
                        ->label('show_password')
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
                        ->value(($this->formAction == 'edit') ? $this->formObject['show_password'] : 'N')
                        ->required(),
                    TextFormField::create('local_part')
                        ->label('local_part')
                        ->value(($this->formAction == 'edit' && isset($localPart)) ? $localPart : '')
                        ->required(),
                    // TODO Add saved value
                    DomainSingleSelectionFormField::create('domain_part')
                        ->label('domain_part')
                        ->value(($this->formAction == 'edit' && isset($domainPart)) ? $domainPart : 'none')
                        ->required(),
                    // TODO Add start end time
                    SingleSelectionFormField::create('responder')
                        ->label('responder')
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
                        ->label('mail_responder_content_type')
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
                        ->label('mail_responder_displayname')
                        ->value(($this->formAction == 'edit' && isset($this->formObject['mail_responder_displayname'])) ? $this->formObject['mail_responder_displayname'] : ''),
                    MultilineTextFormField::create('responder_text')
                        ->label('responder_text')
                        ->value(($this->formAction == 'edit' && isset($this->formObject['responder_text'])) ? $this->formObject['responder_text'] : ''),
                    TextFormField::create('copy_adress')
                        ->label('copy_adress')
                        ->value(($this->formAction == 'edit' && isset($this->formObject['copy_adress'])) ? $this->formObject['copy_adress'] : '')
                        ->description('copy_adress.description(comma seperated list)'),
                    TextFormField::create('mail_sender_alias')
                        ->label('mail_sender_alias')
                        ->value(($this->formAction == 'edit' && isset($this->formObject['mail_sender_alias'])) ? $this->formObject['mail_sender_alias'] : '')
                ])
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
            $parameters['id'] = $this->formObject['mail_login'];
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
            if (empty($formData['data']['mail_password'])) {
                $formData['data']['mail_new_password'] = $this->formObject['mail_password'];
            } else {
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

        if ($this->formAction === 'create' && $this->objectEditLinkController) {
            WCF::getTPL()->assign(
                'objectEditLink',
                LinkHandler::getInstance()->getControllerLink($this->objectEditLinkController, [
                    'application' => $this->objectEditLinkApplication,
                    'id' => $this->objectAction->getReturnValues()['returnValues']->getObjectID(),
                ])
            );
        }
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
