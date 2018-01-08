<?php

namespace App\Models;

use Nette;


/**
 * Class CommentsManagerModel
 * @package App\Models
 */
class CommentsManagerModel
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    /**
     * CommentsManagerModel constructor.
     *
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    /**
     * @param int $limit
     *
     * @return Nette\Database\Table\Selection
     */
    public function getPublicComments($limit = 0): Nette\Database\Table\Selection
    {
        return $this->database->table('comments')
            ->where('created_at < ', new \DateTime)
            ->order('created_at DESC')
            ->limit($limit);
    }

    /**
     * @param $articleId
     *
     * @return Nette\Database\Table\Selection
     */
    public function getPublicCommentsByArticleId($articleId): Nette\Database\Table\Selection
    {
        return $this->database->table('comments')
            ->where('article_id', $articleId)
            ->order('created_at');
    }

    /**
     * @param $articleId
     * @param $values
     */
    public function insertComment($articleId, $values)
    {
        $this->database->table('comments')->insert([
            'article_id' => $articleId,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ]);
    }

    /**
     * @param $articleId
     */
    public function deleteComments($articleId)
    {
        $this->database->table('comments')
            ->where('article_id', $articleId)
            ->delete();
    }

    /**
     *
     */
    public function removeDeletedArticleComments()
    {
        $this->database->table('comments')
            ->where('deleted', 1)
            ->delete();
    }

    /**
     * @param $commentId
     */
    public function deleteSingleComment($commentId)
    {
        $this->database->table('comments')
            ->where('id', $commentId)
            ->delete();
    }
}
