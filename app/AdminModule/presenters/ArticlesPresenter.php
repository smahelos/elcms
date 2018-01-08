<?php

namespace AdminModule;

use \App\Models\ArticleManagerModel,
    \App\Models\CommentsManagerModel;

/**
 * Class ArticlesPresenter
 * @package AdminModule
 */
class ArticlesPresenter extends BasePresenter
{
    /**
     * ArticlesPresenter constructor.
     *
     * @param ArticleManagerModel $articleManager
     * @param CommentsManagerModel $commentsManager
     */
    public function __construct(
        ArticleManagerModel $articleManager,
        CommentsManagerModel $commentsManager)
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->articleManager = $articleManager;
        $this->commentsManager = $commentsManager;
    }

    /**
     * @param null $id
     * @param bool $afterSort
     *
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \InvalidArgumentException
     */
    public function renderDefault($id = null, $afterSort = false)
    {
        $article = null;
        $this->template->articleTitle = null;
        if (null !== $id) {
            $this->redrawControl('flashes');
            if ($this->isAjax()) {
                $this->redrawControl();
                $this->redrawControl('articleForm');
            }
            $article = $this->articleManager->getArticleById($id);
            $this->template->articleTitle = $article->title;
            $this->redrawControl('articleForm');
        }

        //if (!isset($_REQUEST['do'])) {
        $this['breadcrumbs']->removeAllLinks();
        $this['breadcrumbs']->addLink(
            $this->translator->translate('ui.homepage.breadcrumb.text'),
            $this->link('Admin:default'),
            $this->translator->translate('ui.homepage.breadcrumb.title')
        );
        $this['breadcrumbs']->addLink(
            $this->translator->translate('ui.articles.breadcrumb.text'),
            $this->link('Articles:default'),
            $this->translator->translate('ui.articles.breadcrumb.title')
        );
        if ($article) {
            $this['breadcrumbs']->addLink(
                $article['title'],
                '',
                $article['title']
            );
        }
        //}

        $this->template->articles = $this->articleManager->getArticles();
        $this->template->comments = $this->commentsManager->getPublicComments();
    }

    /**
     * @param $data
     *
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \InvalidArgumentException
     */
    public function actionAddArticle($data)
    {
        $this['breadcrumbs']->addLink(
            $this->translator->translate('ui.articles.article_add'),
            $this->link('Articles:editArticle'),
            $this->translator->translate('ui.articles.article_add')
        );
    }

    /**
     *
     */
    public function actionRemoveDeletedArticles()
    {
        $this->articleManager->removeDeletedArticles();
    }

    /**
     * @throws \Nette\Application\AbortException
     */
    public function handleRemoveDeletedArticles() {
        $this->commentsManager->removeDeletedArticleComments();
        $this->articleManager->removeDeletedArticles();

        $this->flashMessage('Smazané články a jejich komentáře byly trvale odstraněny.', 'info');
        $this->redirect('Articles:default');
    }

    /**
     * @param $id
     *
     * @throws \Nette\Application\AbortException
     */
    public function handleDeleteArticle($id) {
        $this->commentsManager->deleteComments($id);
        $this->articleManager->deleteArticle($id);

        $this->flashMessage('Článek a jeho komentáře byl smazán.', 'info');
        $this->redirect('Articles:default');
    }

    /**
     * @param $id
     * @param $fromPresenter
     *
     * @throws \Nette\Application\AbortException
     * @throws \Nette\Application\BadRequestException
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \InvalidArgumentException
     */
    public function handleEditArticle($id, $fromPresenter = 'Admin:Articles')
    {
        if ($fromPresenter !== 'Admin:Articles') {
            $this->redirect('Articles:default', ['id' => $id]);
        }
        $this->redrawControl('articleForm');

        $childMenuIndexes = [];
        $article = null;
        if (null !== $id) {
            $article = $this->articleManager->getArticleById($id);
            $childMenuIndexes = $this->articleManager->getAllChildArticlesById($id);
        }
        if (!$article) {
            $this->error('Stránka nebyla nalezena');
        }

        $this['breadcrumbs']->addLink(
            $this->translator->translate('ui.articles.breadcrumb.text'),
            $this->link('Articles:default'),
            $this->translator->translate('ui.articles.breadcrumb.title')
        );
        $this['breadcrumbs']->addLink(
            $article['title'],
            '',
            $article['title']
        );
        $this->redrawControl('breadcrumbs');

        $this->template->article = $article;

        $comments = $this->commentsManager->getPublicCommentsByArticleId($id);
        $this->template->comments = $comments;

        $this->template->childMenuIndexes = $childMenuIndexes;
    }
}