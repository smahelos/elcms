<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 25. 10. 2017
 * Time: 12:36
 */

use Nette\Application\UI\Control;

/**
 * Class BreadcrumbsControl
 */
class BreadcrumbsControl extends Control {

    /**
     * @var array
     */
    protected $links = array();

    /**
     *
     */
    public function render() {
        $template = $this->template;
        $template->links = $this->links;
        //$template->currentUrl = $this->getPresenter()->getRequest()->getPresenterName();
        $template->render(__DIR__ . '/BreadcrumbsControl.latte');
    }

    /**
     * @param $text
     * @param $link
     * @param null $title
     */
    public function addLink($text, $link, $title = null) {
        $this->links[] = array(
            'text' => $text,
            'link'  => $link,
            'title' => $title
        );
    }

    /**
     * @param $index
     */
    public function removeLink($index) {
        unset($this->links[$index]);
    }

    /**
     *
     */
    public function removeAllLinks() {
        $this->links = [];
    }

}