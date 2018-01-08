<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 25. 10. 2017
 * Time: 10:46
 */

namespace AdminModule;

use \Nette\Application\UI,
    \Nette\Application\UI\Form,
    \App\Models\UsersModel,
    \App\Models\ArticleManagerModel,
    \App\Models\CommentsManagerModel,
    \Nette\InvalidArgumentException;

/**
 * Class UsersPresenter
 * @package AdminModule
 */
class UsersPresenter extends BasePresenter
{
    /**
     * UsersPresenter constructor.
     *
     * @param UsersModel $users
     * @param ArticleManagerModel $articleManager
     * @param CommentsManagerModel $commentsManager
     */
    public function __construct(
        UsersModel $users,
        ArticleManagerModel $articleManager,
        CommentsManagerModel $commentsManager)
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->usersModel = $users;
        $this->articleManager = $articleManager;
        $this->commentsManager = $commentsManager;
    }

    /**
     * @throws UI\InvalidLinkException
     * @throws \InvalidArgumentException
     */
    public function renderDefault(){
        $users = $this->usersModel->findAll();

        if (!isset($_REQUEST['do'])) {
            $this['breadcrumbs']->addLink(
                $this->translator->translate('ui.users.breadcrumb.text'),
                $this->link('Users:default'),
                $this->translator->translate('ui.users.breadcrumb.text')
            );
            $this->redrawControl('breadcrumbs');
        }

        $this->template->users = $users;
    }

    /**
     * @param $id
     *
     * @throws UI\InvalidLinkException
     * @throws \Nette\Application\BadRequestException
     * @throws \InvalidArgumentException
     */
    public function renderEditUser($id){
        $this->redrawControl('userForm');

        $user = null;
        if (null !== $id) {
            $user = $this->usersModel->getUserById($id);
        }
        if (!$user) {
            $this->error('Uživatel nebyl nalezen');
        }

        $this['breadcrumbs']->addLink(
            $this->translator->translate('ui.users.breadcrumb.text'),
            $this->link('Users:default'),
            $this->translator->translate('ui.users.breadcrumb.text')
        );
        $this->redrawControl('breadcrumbs');

        $this->template->user = $user;
    }

    public function renderRegister(){
    }

}