<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * 
 */
class PayForm extends Model
{
    public $amount;
    public $currancy;
    public $subject;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['amount', 'currancy', 'subject'], 'required'],
            ['amount', 'number', 'min' => 1, 'max' => 9999999],
            ['currancy', 'string', 'max' => 3],
            ['subject', 'string', 'max' => 500],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }
    

    public function pay()
    {
        if ($this->validate()) {

            if(!Yii::$app->paypal->expressCheckout($this->amount, $this->currancy, $this->subject)){

                //TODO: payment error processing here (view)

                return false;

            };

            return true;
        }

        return false;
    }
}
