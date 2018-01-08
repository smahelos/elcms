<?php

namespace AdminModule;

use \App\Models\ArticleManagerModel,
    \App\Models\CommentsManagerModel;

/**
 * Class AdminPresenter
 * @package AdminModule
 */
class AdminPresenter extends BasePresenter
{
    /**
     * AdminPresenter constructor.
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
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \InvalidArgumentException
     */
    public function renderDefault()
    {
        $this->template->articleTitle = null;

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
    }
}