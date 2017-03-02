<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\web\UploadedFile;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
   /* public $repassword;
    public $fname;
    public $lname;
    public $address;
    public $mobile;
    public $landline;
    public $profile_image;
    public $utid;
    public $file;*/
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           /* ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255], */

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

          /*  ['password', 'required'],
            ['repassword', 'required'],
            array('repassword', 'compare', 'compareAttribute'=>'password'),
			//array('password', 'compare', 'compareAttribute'=>'repassword', 'on'=>'updatebuyer', 'message'=>"Password must be repeated exactly."),
            ['password', 'string', 'min' => 6],
            ['fname', 'required'],
            ['mobile', 'required'],
            ['address', 'required'],
            ['utid', 'required'],
            ['lname', 'required'],
            //['landline', 'required'],
			[['file'], 'file'],
            [['profile_image'], 'file', 'extensions'=>'jpg, gif, png, jpeg'],  */        
        ];
    }
    
    /**
     * @inheritdoc
     */
 /*   public function attributeLabels()
    {
        return [
            'password_hash' => 'Password',
            'repassword' => 'Retype Password',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'username' => 'User Name', 
            'name' => 'Name',       
        ];
    } */

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
           // $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
    
  /*  public function usersignup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->fname = $this->fname;
            $user->lname = $this->lname;
            $user->address = $this->address;
            $user->landline = $this->landline;
            $user->mobile = $this->mobile;
            $user->profile_image = $this->profile_image;
            $user->utid = $this->utid;
            $user->generateAuthKey();
                 
            if ($user->save()) {				
                return $user;
            }
        return null;
	}
    } */
}
