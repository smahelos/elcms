<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 8. 11. 2017
 * Time: 11:41
 */

use \Ublaboo\DataGrid\DataGrid,
    \App\Models\ArticleManagerModel;

/**
 * Class ArticlesDatagridControl
 */
class ArticlesDatagridControl extends \Nette\Application\UI\Control
{
    /** @var ArticleManagerModel */
    public $articleManager;

    /** @var Nette\Database\Context */
    private $database;

    /** @var $table */
    public $table;

    /** @var $treeView */
    public $treeView;

    /**
     * ArticlesDatagridControl constructor.
     *
     * @param \Nette\Database\Context $database
     * @param ArticleManagerModel $articleManager
     * @param $treeView
     */
    public function __construct(
        Nette\Database\Context $database,
        ArticleManagerModel $articleManager,
        $treeView
    )
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->database = $database;
        $this->articleManager = $articleManager;
        $this->table = 'articles';
        $this->treeView = $treeView;
    }

    /**
     *
     */
    public function render()
    {
        $template = $this->template;
        $template->render(__DIR__ . '/ArticlesDatagridControl.latte');
    }

    /**
     * @param $name
     *
     * @throws Ublaboo\Datagrid\Exception\DataGridException
     */
    public function createComponentArticlesGrid($name)
    {
        $grid = new DataGrid($this, $name);
        $grid->setTranslator($this->presenter->translator);

        $query = "
            SELECT *
            FROM {$this->table}
            ORDER BY id
            ";

        if ($this->treeView === true) {
            $query = "
            SELECT c.*, c_b.count as has_children
            FROM {$this->table} AS c
            LEFT JOIN (SELECT COUNT(id) AS count, parent FROM {$this->table} GROUP BY parent) AS c_b
              ON c_b.parent = c.id
            WHERE c.parent is NULL
            ORDER BY menuindex
            ";
        }

        $dataSource = $this->database->query($query)->fetchAll();

        $grid->setDataSource($dataSource);

        if ($this->treeView === true) {
            $grid->setTreeView([$this, 'getChildren'], 'has_children');
            $grid->setTemplateFile(__DIR__ . '/customArticlesDatagridTreeTemplate.latte');
            $grid->setSortable();
            $grid->setSortableHandler('articlesDatagrid:sort!');
            $grid->addColumnText('title', '');
        } else {
            $grid->addColumnText('id', 'ublaboo_datagrid.ID')
                ->setSortable();
            $grid->addColumnLink('title', 'ublaboo_datagrid.name', 'editArticle!')
                ->setSortable();
            $grid->addColumnText('published', 'ublaboo_datagrid.published')
                ->setSortable();
            $grid->addColumnText('created_at', 'ublaboo_datagrid.created')
                ->setSortable();
        }
    }

    /**
     * @param bool $tree
     *
     * @return bool
     */
    public function setTreeView($tree = false): bool
    {
        return $tree;
    }

    /**
     * @param int $parentId
     *
     * @return array|\Nette\Database\IRow[]|\Nette\Database\ResultSet
     */
    public function getChildren(int $parentId)
    {
        $query = "
            SELECT c.*, c_b.count as has_children
            FROM articles AS c
            LEFT JOIN (SELECT COUNT(id) AS count, parent FROM articles GROUP BY parent) AS c_b
              ON c_b.parent = c.id
            WHERE c.parent = {$parentId}
            ORDER BY menuindex
            ";

        return $this->database->query($query)->fetchAll();
    }

    /**
     * @param int $itemId
     * @param int $newParentId
     * @param int $oldParentId
     *
     * @throws \Nette\Application\AbortException
     */
    public function handleSort($itemId = null, $newParentId = null, $oldParentId = null)
    {
        /**
         * request parameters
         */
        $httpRequest = $this->presenter->getHttpRequest();
        $currentItemId = $httpRequest->getQuery('articlesTreeDatagrid-item_id');
        $targetParentItemId = $httpRequest->getQuery('articlesTreeDatagrid-parent_id');
        $prevItemId = $httpRequest->getQuery('articlesTreeDatagrid-prev_id');
        $nextItemId = $httpRequest->getQuery('articlesTreeDatagrid-next_id');

        /**
         * set sorted item ID
         */
        if (null !== $itemId && '' !== $itemId) {
            $itemId = (int)$itemId;
            /* get old parent id of sorted document */
            $oldParentId = $this->articleManager->getArticleById($itemId)->parent;
        } elseif (null !== $currentItemId && '' !== $currentItemId) {
            $itemId = (int)$currentItemId;
            /* get old parent id of sorted document */
            $oldParentId = $this->articleManager->getArticleById($itemId)->parent;
        } else {
            $itemId = null;
        }

        /**
         * set sorted item parent ID and flashMessage text
         */
        if (null !== $newParentId && '' !== $newParentId) {
            $newParentId = (int)$newParentId;
            $flashMessage = 'Dokument ' . $itemId . ' byl úspěšně přesunut pod dokument ' . $newParentId;
        } elseif (null !== $targetParentItemId && '' !== $targetParentItemId) {
            $newParentId = (int)$targetParentItemId;
            $flashMessage = 'Dokument ' . $itemId . ' byl úspěšně přesunut pod dokument ' . $newParentId;
        } else {
            $newParentId = null;
            $flashMessage = 'Dokument ' . $itemId . ' byl úspěšně přesunut.';
        }

        /**
         * set flashMessage
         */
        if (!empty($flashMessage)) {
            $this->presenter->flashMessage($flashMessage, 'success');
        }

        /**
         * set new menuindexes for other items than current sorted
         *
         * $sortParams array have to have this order of variables:
         * itemId, prevItemId, nextItemId, oldParentId, newParentId
         */
        $sortParams = [];
        $sortParams[] = $itemId;
        $sortParams[] = $prevItemId;
        $sortParams[] = $nextItemId;
        $sortParams[] = $oldParentId;
        $sortParams[] = $newParentId;
        $this->articleManager->updateMenuIndexesAfterSort($sortParams);

        /**
         * set new item menuindex
         */
        $newMenuIndex = $this->articleManager->setItemMenuIndex($prevItemId, $nextItemId);

        /**
         * update parent and menuindex of current sorted item after sort
         */
        $this->articleManager->updateArticleAfterSort($itemId, $newParentId, $newMenuIndex);

        $this->presenter->redirect('Articles:default', ['id' => $itemId]);
    }

    /**
     * @param $id
     *
     * @throws \Nette\Application\AbortException
     */
    public function handleEditArticle($id)
    {
        $this->presenter->redirect('Articles:default', ['id' => $id]);
    }

}