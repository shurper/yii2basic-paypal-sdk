<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Payments;


class PaymentProcessing extends Model
{
    public $success;
    public $id;
    public $userName;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['success', 'id'], 'required'],
            ['success', 'number', 'min' => 0, 'max' => 1],
            ['id', 'string', 'max' => 32],
        ];
    }

    public function success()
    {
        if ($this->validate() && $this->success && $paymentDetails = $this->processPaymentOnSuccess($this->id)) {

                $this->userName = $paymentDetails->user_name;// for example

                return true;

        }

        return false;

    }

    public function cancel()
    {
        if ($this->validate() && !$this->success && $paymentDetails = $this->processPaymentOnCancel($this->id)) {

            $this->userName = $paymentDetails->user_name;// for example

            return true;

        }

        return false;

    }
    
    private function processPaymentOnSuccess($paymentId)
    {
        $payment = new Payments();

        $paymentDetails = $payment->find()
            ->where(['payment_id' => $paymentId])
            ->one();

        if($paymentDetails){

            $paymentDetails->status = 1;
            $paymentDetails->save();

            return $paymentDetails;
        }

        return false;
        
    }

    private function processPaymentOnCancel($paymentId)
    {
        $payment = new Payments();

        $paymentDetails = $payment->find()
            ->where(['payment_id' => $paymentId])
            ->one();

        if($paymentDetails){

            return $paymentDetails;
        }

        return false;

    }
}
