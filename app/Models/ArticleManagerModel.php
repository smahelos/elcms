<?php

namespace App\Models;

use Nette;


/**
 * Class ArticleManagerModel
 * @package App\Models
 */
class ArticleManagerModel
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    /** @var $table */
    public $table;

    /** @var $tableComments */
    public $tableComments;

    /**
     * ArticleManagerModel constructor.
     *
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
        $this->table = 'articles';
        $this->tableComments = 'comments';
    }

    /**
     * @param $values
     */
    public function insertArticle($values)
    {
        $values = $this->setCreated($values);
        $values = $this->setPublished($values);
        $values = $this->setDeleted($values);
        $values->created_at = date('Y-m-d H:i:s');
        $values->updated_at = date('Y-m-d H:i:s');

        if ((int)$values->parent === 0) {
            $values->parent = null;
        }
        unset($values['articleId'], $values['created_at_time'], $values['published_at_time'], $values['updated_at_time']);

        $this->database->table($this->table)->insert($values);
    }

    /**
     * @param $id
     * @param $values
     */
    public function updateArticle($id, $values)
    {
        $values = $this->setCreated($values);
        $values = $this->setPublished($values);
        $values = $this->setDeleted($values);
        if ((int)$values->parent === 0) {
            $values->parent = null;
        }

        $this->database->table($this->table)
            ->where('id', $id)
            ->update([
                'parent' => $values->parent,
                'template' => $values->template,
                'title' => $values->title,
                'menuindex' => $values->menuindex,
                'show_in_menu' => $values->show_in_menu,
                'perex' => $values->perex,
                'content' => $values->content,
                'published' => $values->published,
                'deleted' => $values->deleted,
                'published_at' => $values->published_at,
                'created_at' => $values->created_at,
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => $values->deleted_at,
            ]);
    }

    /**
     * @param $id
     * @param $menuIndex
     */
    public function updateArticleMenuIndex($id, $menuIndex)
    {
        $this->database->table($this->table)
            ->where('id', $id)
            ->update([
                'menuindex' => $menuIndex,
            ]);
    }

    /**
     * @param $id
     * @param $parent
     * @param $menuIndex
     */
    public function updateArticleAfterSort($id, $parent, $menuIndex)
    {
        $this->database->table($this->table)
            ->where('id', $id)
            ->update([
                'parent' => $parent,
                'menuindex' => $menuIndex
            ]);
    }

    /**
     * @param $id
     */
    public function deleteArticle($id)
    {
        $this->database->table($this->table)
            ->where('id', $id)
            ->update([
                'deleted' => 1,
            ]);
    }

    /**
     *
     */
    public function removeDeletedArticles()
    {
        $this->database->table($this->table)
            ->where('deleted', 1)
            ->delete();
    }

    /**
     * @param int $limit
     *
     * @return Nette\Database\Table\Selection
     */
    public function getArticles($limit = 1000000): Nette\Database\Table\Selection
    {
        return $this->database->table($this->table)
            ->order('created_at DESC')
            ->limit($limit);
    }

    /**
     * @param int $limit
     *
     * @return Nette\Database\Table\Selection
     */
    public function getPublicArticles($limit = 1000000): Nette\Database\Table\Selection
    {
        return $this->database->table($this->table)
            ->where('published', 1)
            ->order('created_at DESC')
            ->limit($limit);
    }

    /**
     * @param $id
     *
     * @return Nette\Database\Table\IRow
     */
    public function getArticleById($id): Nette\Database\Table\IRow
    {
        return $this->database->table($this->table)->get($id);
    }

    /**
     * @param $id
     *
     * @return Nette\Database\Table\Selection
     */
    public function getPublishedChildArticlesById($id): Nette\Database\Table\Selection
    {
        return $this->database->table('articles')
            ->where('parent', $id)
            ->where('published', 1);
    }

    /**
     * @param $id
     *
     * @return Nette\Database\Table\Selection
     */
    public function getAllChildArticlesById($id): Nette\Database\Table\Selection
    {
        return $this->database->table('articles')
            ->where('parent', $id);
    }

    /**
     * @param $id
     * @param $values
     */
    public function insertComment($id, $values)
    {
        $this->database->table('comments')->insert([
            'article_id' => $id,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ]);
    }


    /****************************************************************************
     *                          DATAGRID TREE SORTING                           *
     ****************************************************************************/

    /**
     * Find all items that have to be moved one position down
     * used when there are item IDs of new previous and new next item
     *
     * @param $itemId
     * @param $prevItemId
     * @param $newParentId
     *
     * @return void
     */
    public function moveAllItemsInSameNodeByCurrentMenuIndex($itemId, $prevItemId, $newParentId)
    {
        $currentItemMenuIndex = $this->getArticleById($itemId)->menuindex;
        $prevItemMenuIndex = $this->getArticleById($prevItemId)->menuindex;
        if ($currentItemMenuIndex < $prevItemMenuIndex) {
            $itemsToMove = $this->database->table($this->table)
                ->where('parent', $newParentId)
                ->where('menuindex > ?', $currentItemMenuIndex)
                ->where('menuindex <= ?', $prevItemMenuIndex)
                ->fetchAll();

            foreach ($itemsToMove as $t) {
                $this->updateArticleMenuIndex($t->id, $t->menuindex - 1);
            }
        } else {
            $itemsToMove = $this->database->table($this->table)
                ->where('parent', $newParentId)
                ->where('menuindex < ?', $currentItemMenuIndex)
                ->where('menuindex > ?', $prevItemMenuIndex)
                ->fetchAll();

            foreach ($itemsToMove as $t) {
                $this->updateArticleMenuIndex($t->id, $t->menuindex + 1);
            }
        }
    }

    /**
     * Find all items that have to be moved one position down
     * used when there is only item ID of new next item
     *
     * @param $itemId
     * @param $nextItemId
     *
     * @return void
     */
    public function moveAllNextItemsTillCurrentMenuIndex($itemId, $nextItemId)
    {
        $currentItemMenuIndex = $this->getArticleById($itemId)->menuindex;
        $nextItemMenuIndex = $this->getArticleById($nextItemId)->menuindex;
        $itemsToMove = $this->database->table($this->table)
            ->where('menuindex < ?', $currentItemMenuIndex)
            ->where('menuindex >= ?', $nextItemMenuIndex)
            ->fetchAll();


        foreach ($itemsToMove as $t) {
            $this->updateArticleMenuIndex($t->id, $t->menuindex + 1);
        }
    }

    /**
     * Find all items that have to be moved one position up
     * used when there is new parent ID
     *
     * @param $nextItemId
     * @param $newParentId
     *
     * @return void
     */
    public function moveAllNextItemsInNewParent($nextItemId, $newParentId)
    {
        $nextItemMenuIndex = $this->getArticleById($nextItemId)->menuindex;
        $itemsToMove = $this->database->table($this->table)
            ->where('parent', $newParentId)
            ->where('menuindex >= ?', $nextItemMenuIndex)
            ->fetchAll();

        foreach ($itemsToMove as $t) {
            $this->updateArticleMenuIndex($t->id, $t->menuindex + 1);
        }
    }

    /**
     * Find all items of new parent
     *
     * @param $itemId
     * @param $newParentId
     *
     * @return Nette\Database\Table\Selection
     */
    public function getAllNewNeighbours($itemId, $newParentId): Nette\Database\Table\Selection
    {
        return $this->database->table($this->table)
            ->where('id <>', $itemId)
            ->where('parent', $newParentId)
            ->order('menuindex ASC');
    }

    /**
     * Find all items of old parent
     *
     * @param $itemId
     * @param $oldParentId
     *
     * @return Nette\Database\Table\Selection
     */
    public function getAllPreviousNeighbours($itemId, $oldParentId): Nette\Database\Table\Selection
    {
        return $this->database->table($this->table)
            ->where('id <>', $itemId)
            ->where('parent', $oldParentId)
            ->order('menuindex ASC');
    }

    /**
     * Find all items of old parent
     *
     * @param $itemId
     *
     * @return Nette\Database\Table\Selection
     */
    public function getAllPreviousNeighboursWithoutParent($itemId): Nette\Database\Table\Selection
    {
        return $this->database->table($this->table)
            ->where('id <>', $itemId)
            ->where('parent', null)
            ->order('menuindex ASC');
    }

    /**
     * Recalculate menuIndexes
     *
     * @param array $sortParams
     * $sortParams['ietmId', 'prevItemId', 'nextItemId', 'oldParentId', 'newParentId'];
     *
     * @return void
     */
    public function updateMenuIndexesAfterSort($sortParams)
    {
        /*
         * $sortParams array have to have this order of variables:
         * itemId, prevItemId, nextItemId, oldParentId, newParentId
        */
        list($itemId, $prevItemId, $nextItemId, $oldParentId, $newParentId) = $sortParams;

        if ($oldParentId === $newParentId) { /* we are sorting in same parent node */
            if (null !== $prevItemId && '' !== $prevItemId) { /* we have prevItemId */
                $this->moveAllItemsInSameNodeByCurrentMenuIndex($itemId, $prevItemId, $newParentId);
            }
            if ( /* we have just nextItemId */
                (null === $prevItemId || '' === $prevItemId) &&
                (null !== $nextItemId && '' !== $nextItemId)
            ) {
                $this->moveAllNextItemsTillCurrentMenuIndex($itemId, $nextItemId);
            }
        } else { /* we are sorting from one parent node to other */
            /**
             * if we have just prevItemId
             * we need to update only current sorted article
             * it is done by calling updateArticleAfterSort() method
             */
            if (null !== $nextItemId && '' !== $nextItemId) {
                $this->moveAllNextItemsInNewParent($nextItemId, $newParentId);
            }

            /* recalculate items in old node */
            if (null !== $oldParentId && '' !== $oldParentId) {
                $previousNeighbours = $this->getAllPreviousNeighbours($itemId, $oldParentId);
                $i = 1;
                foreach ($previousNeighbours as $t) {
                    $this->updateArticleMenuIndex($t->id, $i);
                    ++$i;
                }
            } else {
                $previousNeighbours = $this->getAllPreviousNeighboursWithoutParent($itemId);
                $i = 1;
                foreach ($previousNeighbours as $t) {
                    $this->updateArticleMenuIndex($t->id, $i);
                    ++$i;
                }
            }
        }
    }

    /**
     * set new sorted item menuindex
     *
     * @param null $prevItemId
     * @param null $nextItemId
     *
     * @return int
     */
    public function setItemMenuIndex($prevItemId = null, $nextItemId = null) :int
    {
        $newMenuIndex = 1;

        if (null !== $prevItemId && $prevItemId !== '') {
            $prevItem = $this->getArticleById($prevItemId);
            $prevItemMenuIndex = $prevItem->menuindex;
            $newMenuIndex = $prevItemMenuIndex + 1;
        }

        if (null !== $nextItemId && $nextItemId !== '') {
            $nextItem = $this->getArticleById($nextItemId);
            $nextItemMenuIndex = $nextItem->menuindex;
            $newMenuIndex = $nextItemMenuIndex - 1;
        }

        return $newMenuIndex;
    }

    /**
     * get highest item menuindex in node
     *
     * @param null $parent
     *
     * @return int
     */
    public function getHighestMenuIndexInNode($parent = null) :int
    {
        $fromParent = null;
        if (null !== $parent && $parent !== '') {
            $fromParent = $parent;
        }

        $maxMenuindex = $this->database->table($this->table)
            ->where('parent', $fromParent)
            ->max('menuindex');

        if (null === $maxMenuindex || $maxMenuindex === '') {
            $maxMenuindex = 1;
        }

        return $maxMenuindex;
    }


    /**
     * @param $values
     *
     * @return mixed
     */
    public function setCreated($values)
    {
        if ((int)$values->created_at !== '') {
            $values->created_at = $values->created_at . ' ' . $values->created_at_time;
        } else {
            $values->created_at = '';
        }

        return $values;
    }

    /**
     * @param $values
     *
     * @return mixed
     */
    public function setPublished($values)
    {
        $date = date('Y-m-d H:i:s');
        if ((int)$values->published === 1) {
            $values->published_at = $date;
            if ($values->published_at !== '') {
                $values->published_at = $values->published_at . ' ' . $values->published_at_time;
            }
        } else {
            $values->published_at = '';
        }

        return $values;
    }

    /**
     * @param $values
     *
     * @return mixed
     */
    public function setDeleted($values)
    {
        $date = date('Y-m-d H:i:s');
        if ((int)$values->deleted === 1) {
            $values->deleted_at = $date;
            $values->published_at = '';
        } else {
            $values->deleted_at = '';
        }

        return $values;
    }

    /**
     * @param $dateTimeValue
     * @param $type (accept values 'created_at', 'published_at', 'updated_at')
     * @param int $published (optional)
     * @param int $deleted (optional)
     *
     * @return array
     */
    public function splitDateTime($dateTimeValue, $type, $published = 0, $deleted = 0): array
    {
        $dateTimeValues = [];
        list($date, $time) = explode(' ', $dateTimeValue);
        $strtotime = strtotime($dateTimeValue);
        if ($strtotime > 0 && $strtotime !== false) {
            $dateTimeValues[$type . '_date'] = $date;
            $dateTimeValues[$type . '_time'] = $time;
            if ($type === 'published_at' && ($published === 0 || $deleted === 1)) {
                $dateTimeValues[$type . '_date'] = '';
                $dateTimeValues[$type . '_time'] = '';
            }
        } else {
            $dateTimeValues[$type . '_date'] = '';
            $dateTimeValues[$type . '_time'] = '';
        }

        return $dateTimeValues;
    }
}
