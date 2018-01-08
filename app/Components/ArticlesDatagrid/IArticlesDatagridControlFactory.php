<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 8. 11. 2017
 * Time: 11:43
 */

interface IArticlesDatagridControlFactory
{
    /**
     * @param bool $treeView
     *
     * @return \ArticlesDatagridControl
     */
    public function create($treeView): \ArticlesDatagridControl;
}