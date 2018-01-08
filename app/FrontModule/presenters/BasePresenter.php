<?php

namespace FrontModule;

use \Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter
{
    /** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;
}
