<?php

namespace FrontModule;

use \App\Models\ArticleManagerModel;


class HomepagePresenter extends BasePresenter
{
    /** @var ArticleManagerModel */
    private $articleManager;

    public function __construct(ArticleManagerModel $articleManager)
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->articleManager = $articleManager;
    }

    public function renderDefault()
    {
        $this->template->articles = $this->articleManager->getArticles(5);
    }
}
