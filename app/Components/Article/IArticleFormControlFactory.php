<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 26. 10. 2017
 * Time: 15:33
 */


interface IArticleFormControlFactory
{
    /** @return \ArticleFormControl */
    public function create($id);
}