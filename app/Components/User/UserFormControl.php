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



//    public function injectImageStorageModel(ImageStorageModel $imageStorageModel)
//    {
//        $this->imageStorageModel = $imageStorageModel;
//        $this->imageStoragePath = $this->imageStorageModel->getImagesDir();
//    }

    /**
     * @return Form
     */
    public function createComponentForm(): \Nette\Application\UI\Form
    {
//        $defaultUserValues = [];
        $user = null;
        if (null !== $this->id) {
            $user = $this->usersModel->getUserById($this->id);
        }

//        if (null !== $user) {
//            $defaultUserValues['name'] = $user['name'];
//            $defaultUserValues['surname'] = $user['surname'];
//            $defaultUserValues['username'] = $user['username'];
//            $defaultUserValues['role'] = $user['role'];
//            $defaultUserValues['email'] = $user['email'];
//            $defaultUserValues['phone'] = $user['phone'];
//            $defaultUserValues['mobilephone'] = $user['mobilephone'];
//            $defaultUserValues['fax'] = $user['fax'];
//            $defaultUserValues['photo'] = $user['photo'];
//            $defaultUserValues['website'] = $user['website'];
//            $defaultUserValues['city'] = $user['city'];
//            $defaultUserValues['address'] = $user['address'];
//            $defaultUserValues['zip'] = $user['zip'];
//            $defaultUserValues['state'] = $user['state'];
//            $defaultUserValues['lastlogin'] = $user['lastlogin'];
//        } else {
//            $defaultUserValues['name'] = '';
//            $defaultUserValues['surname'] = '';
//            $defaultUserValues['username'] = '';
//            $defaultUserValues['role'] = '';
//            $defaultUserValues['email'] = '';
//            $defaultUserValues['phone'] = '';
//            $defaultUserValues['mobilephone'] = '';
//            $defaultUserValues['fax'] = '';
//            $defaultUserValues['photo'] = '';
//            $defaultUserValues['website'] = '';
//            $defaultUserValues['city'] = '';
//            $defaultUserValues['address'] = '';
//            $defaultUserValues['zip'] = '';
//            $defaultUserValues['state'] = '';
//            $defaultUserValues['lastlogin'] = '';
//        }

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
            $newFileName = $this->imageStorageModel->getNewFileName($file, 'users');
            $this->imageStorageModel->upload($values['photo'], $newFileName, 'users/', 'thumbs/');
//            //check if file is image and if it was uploaded
//            if($file->isImage() && $file->isOk()) {
//                //get file extension
//                $file_ext = strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), '.', -1)));
//                //move main file if we have unique filename
//                $file_name = '';
//                while (true) {
//                    //random filename, can use \Nette\Strings::random()
//                    $file_name = uniqid(random_int(0,20), TRUE) . $file_ext;
//                    if (!file_exists($this->uploadsDir . '/users/' . $file_name)) {
//                        $file->move($this->uploadsDir . '/users/' . $file_name);
//                        break;
//                    }
//                }
//                //create thumb
//                $image = \Nette\Utils\Image::fromFile($this->uploadsDir . '/users/'. $file_name);
//                if ($image->getWidth() > $image->getHeight()) {
//                    $image->resize(140, NULL);
//                } else {
//                    $image->resize(NULL, 140);
//                }
//                $image->sharpen();
//                //create thumbs folder if it not exists
//                if (!is_dir($this->uploadsDir . '/users/thumbs/')) {
//                    \Nette\Utils\FileSystem::createDir($this->uploadsDir . '/users/thumbs/');
//                }
//                //save thumb
//                $image->save($this->uploadsDir . '/users/thumbs/'. $file_name);
//
//                $values['photo'] = $file_name;
//            }
            $values['photo'] = $newFileName;
            $this->usersModel->updateUser($id, $values);
        } else {
            $newFileName = $this->imageStorageModel->getNewFileName($file, 'users');
            $this->imageStorageModel->upload($values['photo'], $newFileName, 'users/', 'thumbs/');
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