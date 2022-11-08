<?php

namespace wcf\acp\form;

use wcf\form\AbstractFormBuilderForm;
use wcf\system\event\EventHandler;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\BooleanFormField;
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
    public $objectEditLinkController = KasMailEditForm::class;

    /**
     * @inheritDoc
     */
    protected function createForm()
    {
        parent::createForm();

        $this->form->appendChild(
            FormContainer::create('data')
                ->appendChildren([
                    PasswordFormField::create('mail_password')
                        ->required(),
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
                        ->required(),
                    TextFormField::create('local_part')
                        ->label('local_part')
                        ->required(),
                        TextFormField::create('domain_part')
                        ->label('domain_part')
                        ->required(),
                    // TODO Add start end time
                    SingleSelectionFormField::create('responder')
                        ->label('responder')
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
                        ], true),
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
                        ], true),
                    TextFormField::create('mail_responder_displayname')
                        ->label('mail_responder_displayname'),
                    MultilineTextFormField::create('responder_text')
                        ->label('responder_text'),
                    TextFormField::create('copy_adress')
                        ->label('copy_adress')
                        ->description('comma seperated list'),
                    TextFormField::create('mail_sender_alias')
                        ->label('mail_sender_alias')
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

        $this->setFormAction();
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

        $kasAPI = new KasApi();
        $kasAPI->add_mailaccount($formData['data']);

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
}
