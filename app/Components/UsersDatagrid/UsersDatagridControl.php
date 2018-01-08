<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 8. 11. 2017
 * Time: 11:41
 */

use \Ublaboo\DataGrid\DataGrid,
    \App\Models\UsersModel;

/**
 * Class ArticlesDatagridControl
 */
class UsersDatagridControl extends \Nette\Application\UI\Control
{
    /** @var UsersModel */
    public $usersModel;

    /** @var Nette\Database\Context */
    private $database;

    /** @var $table */
    public $table;

    /**
     * ArticlesDatagridControl constructor.
     *
     * @param \Nette\Database\Context $database
     * @param UsersModel $usersModel
     */
    public function __construct(
        Nette\Database\Context $database,
        UsersModel $usersModel
    )
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->database = $database;
        $this->usersModel = $usersModel;
        $this->table = 'users';
    }

    /**
     *
     */
    public function render()
    {
        $template = $this->template;
        $template->render(__DIR__ . '/UsersDatagridControl.latte');
    }

    /**
     * @param $name
     */
    public function createComponentUsersGrid($name)
    {
        $grid = new DataGrid($this, $name);

        $query = "
            SELECT *
            FROM {$this->table}
            ORDER BY id
            ";

        $dataSource = $this->database->query($query)->fetchAll();

        $grid->setDataSource($dataSource);
        $grid->setTemplateFile(__DIR__ . '/customUsersDatagridTemplate.latte');

        $grid->addColumnNumber('id', 'Id')
            //->setFormat(2)
            ->addAttributes(['width' => '8%'])
            //->setReplacement([1 => 'One', 5 => 'Five', 10 => 'Ten'])
            ->setAlign('left');

        $grid->addColumnLink('username', 'Uživatelské jméno', 'editUser!')
            ->setSortable();

        $grid->addColumnText('name', 'Jméno')
            ->setSortable();

        $grid->addColumnText('surname', 'Příjmení')
            ->setSortable();

        $grid->addColumnText('role', 'Role')
            ->setSortable();

        $grid->addColumnText('email', 'Email')
            ->setSortable();

        /**
         * Localization
         */
        $translator = new Ublaboo\DataGrid\Localization\SimpleTranslator([
            'ublaboo_datagrid.here' => 'zde',
            'ublaboo_datagrid.items' => 'Položky',
            'ublaboo_datagrid.all' => 'všechny',
            'ublaboo_datagrid.from' => 'z',
            'ublaboo_datagrid.previous' => 'Předchozí',
            'ublaboo_datagrid.next' => 'Další',
        ]);
        $grid->setTranslator($translator);
    }

    /**
     * @param $id
     *
     * @throws \Nette\Application\AbortException
     * @throws \Nette\Application\BadRequestException
     */
    public function handleEditUser($id)
    {
        $this->presenter->redrawControl('userForm');

        $user = null;
        if (null !== $id) {
            $user = $this->usersModel->getUserById($id);
        }
        if (!$user) {
            $this->presenter->error('Uživatel nebyl nalezen');
        }

        $this->template->user = $user;
        $this->presenter->redirect('editUser', ['id' => $id]);
    }
}