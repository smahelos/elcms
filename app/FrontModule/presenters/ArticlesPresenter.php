<?php

namespace FrontModule;

use \Nette\Application\UI\Form,
    \App\Models\ArticleManagerModel;

class ArticlesPresenter extends BasePresenter
{
    /** @var ArticleManagerModel */
    private $articleManager;

    public function __construct(ArticleManagerModel $articleManager)
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->articleManager = $articleManager;
    }

    public function renderDetail($articleId)
    {
        $article = $this->articleManager->getArticleById($articleId);
        if (!$article) {
            $this->error('Stránka nebyla nalezena');
        }

        $this->template->article = $article;
        $this->template->comments = $article->related('comment')->order('created_at');
    }

    protected function createComponentCommentForm()
    {
        $form = new Form; // means Nette\Application\UI\Form

        $form->addText('name', 'Jméno:')
            ->setRequired();

        $form->addEmail('email', 'Email:');

        $form->addTextArea('content', 'Komentář:')
            ->setRequired();

        $form->addSubmit('send', 'Publikovat komentář');

        $form->onSuccess[] = [$this, 'commentFormSucceeded'];

        return $form;
    }

    public function commentFormSucceeded($form, $values)
    {
        $articleId = $this->getParameter('articleId');

        $this->articleManager->insertComment($articleId, $values);

        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }

    protected function createComponentArticleForm()
    {
        $form = new Form;

        $form->addText('title', 'Titulek:')
            ->setRequired();

        $form->addTextArea('content', 'Obsah:')
            ->setRequired();

        $form->addSubmit('send', 'Uložit a publikovat');

        $form->onSuccess[] = [$this, 'articleFormSucceeded'];

        return $form;
    }

    public function articleFormSucceeded($form, $values)
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->error('Pro vytvoření, nebo editování příspěvku se musíte přihlásit.');
        }

        $articleId = $this->getParameter('articleId');

        if ($articleId) {
            $this->articleManager->updateArticle($articleId, $values);
        } else {
            $article = $this->articleManager->insertArticle($values);
            $articleId = $article['id'];
        }

        $this->flashMessage('Článek byl úspěšně publikován.', 'success');

        $this->redirect('detail', $articleId);
    }

    public function actionEdit($articleId)
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Login:login');
        }

        $article = $this->articleManager->getArticleById($articleId);
        if (!$articleId) {
            $this->error('Článek nebyl nalezen');
        }
        $this['articleForm']->setDefaults($article);
    }

    public function actionCreate()
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Login:login');
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Homepage:');
    }
}
