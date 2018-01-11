<?php

namespace AdminModule;

use \Nette\Application\UI\Presenter,
    \WebLoader\Nette\CssLoader,
    \WebLoader\Nette\JavaScriptLoader,
    \WebLoader\Nette\LoaderFactory,
    \App\Models\ArticleManagerModel,
    \App\Models\CommentsManagerModel,
    \App\Models\UsersModel;


/**
 * Class BasePresenter
 * @package AdminModule
 */
class BasePresenter extends Presenter
{
    /** @var \IUserFormControlFactory @inject */
    public $userFormControl;

    /** @var \IBreadcrumbsControlFactory @inject */
    public $breadcrumbsControl;

    /** @var \IArticlesDatagridControlFactory @inject */
    public $articlesDatagridControl;

    /** @var \IUsersDatagridControlFactory @inject */
    public $usersDatagridControl;

    /** @var \IArticleFormControlFactory @inject */
    public $articleFormControl;

    /** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;

    /** @var LoaderFactory @inject */
    public $webLoader;

    /** @var UsersModel @inject */
    public $usersModel;

    /** @var ArticleManagerModel @inject */
    public $articleManager;

    /** @var CommentsManagerModel @inject */
    public $commentsManager;

    /** @var $usersPhotoPath */
    public $usersPhotoPath = '/uploads/users/';

    /** @var $uploadsImagesDir */
    public $uploadsImagesDir;

    /**
     * @throws \Nette\Application\AbortException
     */
    public function startup()
    {
        parent::startup();

        if (!$this->user->isInRole('admin')) {
            $this->flashMessage('Nemáte dostatečná práva');
            $this->redirect(':Admin:Login:login');
        }

        $this->template->usersPhotoPath = $this->usersPhotoPath;
        $this->template->loggedInUser = $this->usersModel->getUserById($this->user->id);
        $this->uploadsImagesDir = $this->presenter->context->parameters['globalConfig']['uploadsImagesDir'];
        $this->template->uploadsImagesDir = $this->presenter->context->parameters['globalConfig']['uploadsImagesDir'];
    }

    /**
     *
     * @param \IUserFormControlFactory $factory
     */
    public function injectIUserFormControlFactory(\IUserFormControlFactory $factory)
    {
        $this->userFormControl = $factory;
    }

    /**
     *
     * @param \IBreadcrumbsControlFactory $factory
     */
    public function injectIBreadcrumbsControlFactory(\IBreadcrumbsControlFactory $factory)
    {
        $this->breadcrumbsControl = $factory;
    }

    /**
     *
     * @param \IArticlesDatagridControlFactory $factory
     */
    public function IArticlesDatagridControlFactory(\IArticlesDatagridControlFactory $factory)
    {
        $this->articlesDatagridControl = $factory;
    }

    /**
     *
     * @param \IUsersDatagridControlFactory $factory
     */
    public function IUsersDatagridControlFactory(\IUsersDatagridControlFactory $factory)
    {
        $this->usersDatagridControl = $factory;
    }

    /**
     *
     * @param \IArticleFormControlFactory $factory
     */
    public function injectIArticleFormControlFactory(\IArticleFormControlFactory $factory)
    {
        $this->articleFormControl = $factory;
    }

    /**
     *
     */
    public function beforeRender()
    {
        $this->setLayout('layoutAdmin');
        $this->template->adminAssetsPath = $this->getHttpRequest()->getUrl()->getBasePath() . 'assets/admin/';
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
     * @return \UserFormControl
     */
    protected function createComponentUserForm(): \UserFormControl
    {
        $id = null;
        if (null !== $this->getParameter('id')) {
            $id = $this->getParameter('id');
        } elseif (null !== $this->getParameter('userForm-id')) {
            $id = $this->getParameter('userForm-id');
        } elseif (isset($_GET['userForm-id'])) {
            $id = $_GET['articleForm-id'];
        }

        $control = $this->userFormControl->create($id);
        $control->redrawControl();

        return $control;
    }

    /**
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \InvalidArgumentException
     *
     * @return \BreadcrumbsControl
     */
    protected function createComponentBreadcrumbs(): \BreadcrumbsControl
    {
        $control = $this->breadcrumbsControl->create();
        $control->addLink(
            $this->translator->translate('ui.homepage.breadcrumb.text'),
            $this->link('Admin:default'),
            $this->translator->translate('ui.homepage.breadcrumb.title')
        );

        return $control;
    }

    /**
     * @return \ArticlesDatagridControl
     */
    protected function createComponentArticlesDatagrid(): \ArticlesDatagridControl
    {
        return $this->articlesDatagridControl->create($treeView = false);
    }

    /**
     * @return \ArticlesDatagridControl
     */
    protected function createComponentArticlesTreeDatagrid(): \ArticlesDatagridControl
    {
        return $this->articlesDatagridControl->create($treeView = true);
    }

    /**
     * @return \UsersDatagridControl
     */
    protected function createComponentUsersDatagrid(): \UsersDatagridControl
    {
        return $this->usersDatagridControl->create();
    }

    /**
     * @return \ArticleFormControl
     */
    protected function createComponentArticleForm() :\ArticleFormControl
    {
        $id = null;
        if (null !== $this->getParameter('id')) {
            $id = $this->getParameter('id');
        } elseif (null !== $this->getParameter('articleForm-articleId')) {
            $id = $this->getParameter('articleForm-articleId');
        } elseif (isset($_GET['articleForm-articleId'])) {
            $id = $_GET['articleForm-articleId'];
        }

        $control = $this->articleFormControl->create($id);
        $control->onArticleFormSuccess[] = [$this, 'articleFormSucceeded'];
        $control->redrawControl();

        return $control;
    }

    /**
     * @param $form
     * @param $values
     *
     * @throws \Nette\Application\AbortException
     */
    public function articleFormSucceeded($form, $values)
    {
        if (isset($_GET['articleForm-id'])) {
            $id = $_GET['articleForm-id'];
        } elseif ($this->getParameter('id')) {
            $id = $this->getParameter('id');
        } else {
            $httpRequest = $this->getHttpRequest();
            $id = $httpRequest->getPost('articleId');
        }

        if (null !== $id) {
            $this->articleManager->updateArticle($id, $values);
        } else {
            $this->articleManager->insertArticle($values);
        }

        $this->flashMessage('Článek byl úspěšně publikován.', 'success');
        $this->redirect('default');
    }

    /**
     * @throws \Nette\Application\AbortException
     */
    public function handleLogOut()
    {
        $this->user->logout();
        $this->flashMessage('Byl jste odhlášen', 'info');
        $this->redirect(':Front:Homepage:default');
    }

    /* TODO: we need handle edit user here, but better to move it into UsersModel */
    /**
     * @param $id
     *
     * @throws \Nette\Application\BadRequestException
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \InvalidArgumentException
     */
    public function handleEditUser($id)
    {
        $this->redrawControl('userForm');

        $user = null;
        if (null !== $id) {
            $user = $this->usersModel->getUserById($id);
        }
        if (!$user) {
            $this->error('Uživatel nebyl nalezen');
        }

        $this['breadcrumbs']->addLink(
            $this->translator->translate('ui.articles.breadcrumb.text'),
            $this->link('Articles:default'),
            $this->translator->translate('ui.articles.breadcrumb.title')
        );
        $this->redrawControl('breadcrumbs');

        $this->template->user = $user;
    }
}