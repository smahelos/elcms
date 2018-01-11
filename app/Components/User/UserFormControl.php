<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 5. 12. 2017
 * Time: 16:54
 */

use \Nette\Application\UI\Form,
    \Nette\Application\UI\Control,
    \App\Models\ImageStorageModel,
    \App\Models\UsersModel;

class UserFormControl extends Control
{
    /** @var $id */
    private $id;

    /** @var UserModel */
    public $usersModel;

    /** @var ImageStorageModel @inject */
    public $imageStorageModel;


    /**
     * UserFormControl constructor.
     *
     * @param $id
     * @param UsersModel $usersModel
     * @param ImageStorageModel $imageStorageModel
     */
    public function __construct(
        $id,
        UsersModel $usersModel,
        ImageStorageModel $imageStorageModel
    )
    {
        parent::__construct(); // pokud je konstruktor předka bez parametrů

        $this->id = $id;
        $this->usersModel = $usersModel;
        $this->imageStorageModel = $imageStorageModel;
    }

    /**
     * @return Form
     */
    public function createComponentForm(): \Nette\Application\UI\Form
    {
        $user = null;
        if (null !== $this->id) {
            $user = $this->usersModel->getUserById($this->id);
        }

        $form = new Form;

        $form->addText('name', 'Jméno');
            //->setDefaultValue($defaultUserValues['name']);

        $form->addText('surname', 'Příjmení');
            //->setDefaultValue($defaultUserValues['surname']);

        $form->addText('username', 'Uživatelské Jméno');
            //->setDefaultValue($defaultUserValues['username']);

        $form->addText('role', 'Role');
            //->setDefaultValue($defaultUserValues['role']);

        $form->addText('email', 'E-mail: *', 35)
            //->setDefaultValue($defaultUserValues['email'])
            ->setEmptyValue('@')
            ->addRule(Form::FILLED, 'Vyplňte Váš email')
            ->addCondition(Form::FILLED)
            ->addRule(Form::EMAIL, 'Neplatná emailová adresa');


        $form->addCheckbox('newPassword', 'Nové heslo?');
        //->setDefaultValue($defaultUserValues['role']);

        $form->addPassword('password', 'Heslo: *', 20)
            ->addConditionOn($form['newPassword'], Form::EQUAL, true)
                //->setOption('description', 'Alespoň 6 znaků')
                ->addRule(Form::FILLED, 'Vyplňte Vaše heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků.', 6);

        $form->addPassword('password2', 'Heslo znovu: *', 20)
            ->addConditionOn($form['password'], Form::FILLED)
            ->addRule(Form::FILLED, 'Heslo znovu')
            ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);

        $form->addText('phone', 'Telefon');
            //->setDefaultValue($defaultUserValues['phone']);

        $form->addText('mobilephone', 'Mobilní telefon');
            //->setDefaultValue($defaultUserValues['mobilephone']);

        $form->addText('fax', 'Fax');
            //->setDefaultValue($defaultUserValues['fax']);

        $form->addUpload('photo', 'Fotografie')
            ->setRequired(false) // nepovinný
            //->setDefaultValue($defaultUserValues['photo'])
            ->addRule(Form::IMAGE, 'Avatar musí být JPEG, PNG nebo GIF.');

        $form->addText('website', 'Web');
            //->setDefaultValue($defaultUserValues['website']);

        $form->addText('city', 'Město');
            //->setDefaultValue($defaultUserValues['city']);

        $form->addText('address', 'Adresa');
            //->setDefaultValue($defaultUserValues['address']);

        $form->addText('zip', 'PSČ');
            //->setDefaultValue($defaultUserValues['zip']);

        $form->addText('state', 'Stát');
            //->setDefaultValue($defaultUserValues['state']);

        $form->addHidden('userId')
            ->setDefaultValue($this->id);

        if (null !== $user) {
            $form->setDefaults([
                'name' => $user['name'],
                'surname' => $user['surname'],
                'username' => $user['username'],
                'role' => $user['role'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'mobilephone' => $user['mobilephone'],
                'fax' => $user['fax'],
                'photo' => $user['photo'],
                'website' => $user['website'],
                'city' => $user['city'],
                'address' => $user['address'],
                'zip' => $user['zip'],
                'state' => $user['state'],
                'lastlogin' => $user['lastlogin']
            ]);
        }

        $form->addSubmit('submit', 'Uložit');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    /**
     * @param Form $form
     *
     * @throws \Nette\Application\AbortException
     * @throws \Nette\Utils\UnknownImageFileException
     * @throws \Nette\NotSupportedException
     * @throws \Nette\IOException
     */
    public function processForm(Form $form)
    {
        $values = $form->getValues();
        if (isset($_GET['userForm-id'])) {
            $id = $_GET['userForm-id'];
        } elseif ($this->getParameter('id')) {
            $id = $this->getParameter('id');
        } else {
            $httpRequest = $this->presenter->getHttpRequest();
            $id = $httpRequest->getPost('userId');
        }

        $file = $values['photo'];
        if (null !== $id) {
            if (null !== $file) {
                $newFileName = $this->imageStorageModel->getNewFileName($file, 'users');
                $this->imageStorageModel->upload($values['photo'], $newFileName, 'users/', 'thumbs/');
                $values['photo'] = $newFileName;
            } else {
                $values['photo'] = '';
            }
            $this->usersModel->updateUser($id, $values);
        } else {
            if (null !== $file) {
                $newFileName = $this->imageStorageModel->getNewFileName($file, 'users');
                $this->imageStorageModel->upload($values['photo'], $newFileName, 'users/', 'thumbs/');
                $values['photo'] = $newFileName;
            } else {
                $values['photo'] = '';
            }
            $this->usersModel->insertUser($values);
        }

        $this->presenter->flashMessage('Uživatel byl úspěšně uložen.', 'success');
        $this->presenter->redrawControl('flashes');
        $this->presenter->redirect('default');
    }

    /*
     *
     */
    public function render()
    {
        $user = null;
        if (null !== $this->id) {
            $user = $this->usersModel->getUserById($this->id);
        }

        $template = $this->template;
        $template->imageUrl = $this->presenter->uploadsImagesDir . 'users/thumbs/' . $user['photo'];
        $template->render(__DIR__ . '/UserFormControl.latte');
    }

}