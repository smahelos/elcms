<?php

namespace AdminModule;

use \Nette,
    \Nette\Application\UI\Form,
    \Nette\Database\Context,
    \Nette\Security\AuthenticationException,
    \Nette\Utils\ArrayHash,
    \Nette\Application\UI\Presenter,
    \WebLoader\Nette\CssLoader,
    \WebLoader\Nette\JavaScriptLoader,
    \WebLoader\Nette\LoaderFactory;

class LoginPresenter extends Presenter
{
    /** @var \ILoginFormControlFactory @inject */
    public $loginFormControl;

    /** @var Nette\Database\Context */
    private $database;

    /** @var LoaderFactory @inject */
    public $webLoader;

    /**
     * LoginPresenter constructor.
     *
     * @param Context $database
     */
    public function __construct(Context $database)
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->database = $database;
    }

    /**
     * @return CssLoader
     */
    protected function createComponentCss(): CssLoader
    {
        return $this->webLoader->createCssLoader('admin');
    }
    /**
     * @return JavaScriptLoader
     */
    protected function createComponentJs(): JavaScriptLoader
    {
        return $this->webLoader->createJavaScriptLoader('admin');
    }

    /**
     *
     */
    public function beforeRender()
    {
        $this->setLayout('layoutLoginAdmin');
    }

    /**
     *
     * @param \ILoginFormControlFactory $factory
     */
    public function injectILoginFormControlFactory(\ILoginFormControlFactory $factory)
    {
        $this->loginFormControl = $factory;
    }

    /**
     * @return \LoginFormControl
     */
    protected function createComponentLoginFormControl(): \LoginFormControl
    {
        $control = $this->loginFormControl->create();
        $control->onLoginFormSuccess[] = [$this, 'logInFormSucceeded'];

        return $control;
    }

    /**
     * @param Form $form
     * @param ArrayHash $values
     *
     * @throws \Nette\Application\AbortException
     */
    public function logInFormSucceeded(Form $form, ArrayHash $values)
    {
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Admin:');
        } catch (AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    /**
     * @throws \Nette\Application\AbortException
     */
    public function actionLogout()
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Admin:');
    }

}
