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
        $this->defaultTemplatesDir = '../FrontModule/templates/Articles/';
        $this->defaultTemplatesDirPath = $_SERVER['DOCUMENT_ROOT'] . '/../app/FrontModule/templates/Articles/';
        $this->articleManager = $articleManager;
        $this->commentsManager = $commentsManager;
    }

    /**
     * @return Form
     */
    public function createComponentForm(): \Nette\Application\UI\Form
    {
        $article = null;
        $httpRequest = $this->presenter->getHttpRequest();
        $currentItemId = $httpRequest->getQuery('articlesDatagrid-item_id');
        if (NULL !== $currentItemId && '' !== $currentItemId) {
            $this->id = (int)$currentItemId;
        }
        if (null !== $this->id) {
            $article = $this->articleManager->getArticleById($this->id);
        }

        $form = new Form;

        $form->addText('parent', 'Rodič');

        $form->addSelect('template', 'Šablona', $this->getTemplates())
            //->setPrompt($defaultArticleValues['template'])
            ->setRequired();

        $form->addText('menuindex', 'Menuindex (pořadí ve stromu dokumentů)');

        $form->addText('title', 'Titulek')
            ->setRequired();

        $form->addTextArea('perex', 'Perex');

        $form->addTextArea('content', 'Obsah')
            ->setRequired();

        $form->addCheckbox('published', 'Zveřejněn');

        $form->addCheckbox('deleted', 'Smazán');

        $form->addCheckbox('show_in_menu', 'Zobrazit v menu');

        $form->addText('created_at', 'Datum a čas vytvoření')
            ->setAttribute('placeholder', 'yyyy-mm-dd hh:mm');

        $form->addText('published_at', 'Datum a čas zveřejnění')
            ->setAttribute('placeholder', 'yyyy-mm-dd hh:mm');

        $form->addText('updated_at', 'Datum a čas poslední úpravy')
            ->setAttribute('placeholder', 'yyyy-mm-dd hh:mm');

        $form->addHidden('articleId');

        $form->addSubmit('submit', 'Uložit a publikovat');

        $parent = 0;
        if (null !== $article) {
            $parent = $article['parent'];
            if ($article['parent'] === null) {
                $parent = 0;
            }

            $form->setDefaults([
                'parent' => $parent,
                'template' => $article['template'],
                'title' => $article['title'],
                'menuindex' => $article['menuindex'],
                'show_in_menu' => $article['show_in_menu'],
                'perex' => $article['perex'],
                'content' => $article['content'],
                'published' => $article['published'],
                'deleted' => $article['deleted'],
                'created_at' => $article['created_at'],
                'published_at' => $article['published_at'],
                'updated_at' => $article['updated_at']
            ]);
        } else {
            $form->setDefaults([
                'parent' => $parent,
                'template' => 'default.latte',
                'menuindex' => $this->articleManager->getHighestMenuIndexInNode(null) + 1,
                'show_in_menu' => 1,
            ]);
        }

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
        $template->templateName = 'default.latte';
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