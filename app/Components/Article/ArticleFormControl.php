<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 26. 10. 2017
 * Time: 15:32
 */

use \Nette\Application\UI\Form,
    \Nette\Application\UI\Control,
    \App\Models\ArticleManagerModel,
    \App\Models\CommentsManagerModel,
    \Nette\Utils\Finder;

/**
 * Class ArticleFormControl
 *
 * @method void onArticleFormSuccess(Form $form, $values)
 */
class ArticleFormControl extends Control
{
    /** @var ArticleManagerModel */
    public $articleManager;

    /** @var CommentsManagerModel */
    public $commentsManager;

    /** @var callable[] */
    public $onArticleFormSuccess = [];

    /** @persistent */
    //public $backlink = '';

    /** @var $id */
    private $id;

    /** @var $defaultTemplatesDir */
    private $defaultTemplatesDir;

    /** @var $defaultTemplatesDirPath */
    private $defaultTemplatesDirPath;

    /**
     * ArticleFormControl constructor.
     *
     * @param $id
     * @param ArticleManagerModel $articleManager
     * @param CommentsManagerModel $commentsManager
     */
    public function __construct(
        $id,
        ArticleManagerModel $articleManager,
        CommentsManagerModel $commentsManager
    )
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->id = $id;
        $this->defaultTemplatesDir = '/elcms/app/FrontModule/templates/Articles/';
        $this->defaultTemplatesDirPath = $_SERVER['DOCUMENT_ROOT'] . '/elcms/app/FrontModule/templates/Articles/';
        $this->articleManager = $articleManager;
        $this->commentsManager = $commentsManager;
    }

    /**
     * @return Form
     */
    public function createComponentForm(): \Nette\Application\UI\Form
    {
        $defaultArticleValues = [];
        $article = null;

        $httpRequest = $this->presenter->getHttpRequest();
        $currentItemId = $httpRequest->getQuery('articlesDatagrid-item_id');

        if (NULL !== $currentItemId && '' !== $currentItemId) {
            $this->id = (int)$currentItemId;
        }
        if (null !== $this->id) {
            $article = $this->articleManager->getArticleById($this->id);
        }

        if (null !== $article) {
            $defaultArticleValues['parent'] = $article['parent'];
            if ($defaultArticleValues['parent'] === null) {
                $defaultArticleValues['parent'] = 0;
            }
            $defaultArticleValues['template'] = $article['template'];
            $defaultArticleValues['title'] = $article['title'];
            $defaultArticleValues['menuindex'] = $article['menuindex'];
            $defaultArticleValues['show_in_menu'] = $article['show_in_menu'];
            $defaultArticleValues['perex'] = $article['perex'];
            $defaultArticleValues['content'] = $article['content'];
            $defaultArticleValues['published'] = $article['published'];
            $defaultArticleValues['deleted'] = $article['deleted'];
        } else {
            $defaultArticleValues['parent'] = 0;
            $defaultArticleValues['template'] = 'default.latte';
            $defaultArticleValues['title'] = '';
            $defaultArticleValues['menuindex'] = 0;
            $defaultArticleValues['show_in_menu'] = 1;
            $defaultArticleValues['perex'] = '';
            $defaultArticleValues['content'] = '';
            $defaultArticleValues['published'] = 0;
            $defaultArticleValues['deleted'] = 0;
        }

        $form = new Form;

        $form->addText('parent', 'Rodič')
            ->setDefaultValue($defaultArticleValues['parent']);
            //->setRequired();

        $form->addSelect('template', 'Šablona', $this->getTemplates())
            //->setPrompt($defaultArticleValues['template'])
            ->setDefaultValue($defaultArticleValues['template'])
            ->setRequired();

        $form->addText('menuindex', 'Menuindex (pořadí ve stromu dokumentů)')
            ->setDefaultValue($defaultArticleValues['menuindex']);

        $form->addCheckbox('show_in_menu', 'Zobrazit v menu')
            ->setDefaultValue($defaultArticleValues['show_in_menu']);

        $form->addText('title', 'Titulek')
            ->setDefaultValue($defaultArticleValues['title'])
            ->setRequired();

        $form->addTextArea('perex', 'Perex')
            ->setDefaultValue($defaultArticleValues['perex']);

        $form->addTextArea('content', 'Obsah')
            ->setDefaultValue($defaultArticleValues['content'])
            ->setRequired();

        $form->addCheckbox('published', 'Zveřejněn')
            ->setDefaultValue($defaultArticleValues['published']);

        $form->addCheckbox('deleted', 'Smazán')
            ->setDefaultValue($defaultArticleValues['deleted']);

        $form->addHidden('articleId')
            ->setDefaultValue($this->id);

        $form->addSubmit('submit', 'Uložit a publikovat');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    /**
     * @param Form $form
     * @param $values
     */
    public function processForm(Form $form, $values)
    {
        $this->onArticleFormSuccess($form, $values);
    }

    /**
     *
     */
    public function render()
    {
        $template = $this->template;

        $httpRequest = $this->presenter->getHttpRequest();
        $currentItemId = $httpRequest->getQuery('articlesDatagrid-item_id');

        if (NULL !== $currentItemId && '' !== $currentItemId) {
            $this->id = (int)$currentItemId;
        }

        if (null !== $this->id) {
            $article = $this->articleManager->getArticleById($this->id);
            $template->templateName = $article['template'];

            $comments = $this->commentsManager->getPublicCommentsByArticleId($this->id);
            $template->comments = $comments;
        }
        $template->defaultTemplatesDir = $this->defaultTemplatesDir;

        $template->render(__DIR__ . '/ArticleFormControl.latte');
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->id;
    }

    /**
     * @param string $dir
     *
     * @return array
     */
    public function getTemplates($dir = ''): array
    {
        $templates = [];
        if (null === $dir || $dir === '') {
            $dir = $this->defaultTemplatesDirPath;
        }

        $files = Finder::findFiles('*.latte')->in($dir);
        /* @var $files Finder */
        foreach ($files as $key => $file) {
            $fileName = $file->getFilename();
            $templates[$fileName] = $fileName;
        }

        return $templates;
    }

    /**
     * @param $id
     * @param $commentName
     * @param $articleId
     */
    public function handleDeleteComment($id, $commentName, $articleId) {
        $this->commentsManager->deleteSingleComment($id);

        $this->presenter->flashMessage('Komentář ' . $commentName . ' byl smazán', 'info');
        $this->presenter->redrawControl('flashes');

        $this->presenter->redrawControl('articleForm');
    }
}