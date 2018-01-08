<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 25. 10. 2017
 * Time: 12:36
 */


use \Nette\Application\UI\Form,
    \Nette\Application\UI\Control,
    \Nette\Security\AuthenticationException,
    \Nette\Utils\ArrayHash,
    \Nette\Security\User;


/**
 * Class LoginFormControl
 *
 * @method void onLoginFormSuccess(Form $form, $values)
 */
class LoginFormControl extends Control
{
    /** @var \Nette\Security\User */
    private $user;

    /** @var callable[] */
    public $onLoginFormSuccess = [];

    /**
     * @param \Nette\Security\User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
    }

    /**
     * @return Form
     */
    public function createComponentForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno:')
            ->setRequired('Prosím vyplňte své uživatelské jméno.');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte své heslo.');

        $form->addSubmit('submit', 'Přihlásit');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    /**
     * @param Form $form
     * @param $values
     */
    public function processForm(Form $form, $values)
    {
        $this->onLoginFormSuccess($form, $values);
    }

    /**
     *
     */
    public function render()
    {
        $template = $this->template;
        $template->render(__DIR__ . '/LoginFormControl.latte');
    }
}