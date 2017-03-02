<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hcare_users".
 *
 * @property integer $id
 * @property integer $user_type_id
 * @property string $fname
 * @property string $lname
 * @property string $email
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $profile_image
 * @property string $gender
 * @property string $mobile
 * @property string $landline
 * @property string $address
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property integer $authorized
 * @property integer $confirmation_status
 * @property string $dob
 * @property integer $user_role_id
 *
 * @property UserRole $userRole
 * @property UserType $userType
 */
class HcareUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hcare_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_type_id', 'status', 'authorized', 'confirmation_status', 'user_role_id'], 'integer'],
            [['fname', 'lname', 'email', 'auth_key', 'password_hash', 'profile_image', 'mobile', 'address'], 'required'],
            [['gender', 'address'], 'string'],
            [['created_at', 'updated_at', 'dob'], 'safe'],
            [['fname', 'lname', 'email', 'username', 'password_hash', 'password_reset_token', 'profile_image'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['mobile'], 'string', 'max' => 20],
            [['landline'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_type_id' => 'User Type ID',
            'fname' => 'Fname',
            'lname' => 'Lname',
            'email' => 'Email',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'profile_image' => 'Profile Image',
            'gender' => 'Gender',
            'mobile' => 'Mobile',
            'landline' => 'Landline',
            'address' => 'Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'authorized' => 'Authorized',
            'confirmation_status' => 'Confirmation Status',
            'dob' => 'Dob',
            'user_role_id' => 'User Role ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRole()
    {
        return $this->hasOne(UserRole::className(), ['id' => 'user_role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserType()
    {
        return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
    }
}
