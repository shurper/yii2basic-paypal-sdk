<?php
/**
 * File PayPayPal.php.
 *
 * @author avgarea
 */

namespace app\components;

use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\base\Component;

use app\models\Payments;

use PayPal\Api\Address;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\FundingInstrument;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;



class PayPayPal extends Component
{
    public $clientId;
    public $clientSecret;

    private function getApiContext()
    {
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );
        return $apiContext;
    }

    public function expressCheckout($cost, $currancy, $subject)
    {

        $orderId = $this->getOrderNumber();

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item = new Item();
        $item->setName($subject)
            ->setCurrency($currancy)
            ->setQuantity(1)
            ->setPrice($cost);


        $itemList = new ItemList();
        $itemList->setItems(array($item));

        $amount = new Amount();
        $amount->setCurrency($currancy)
            ->setTotal($cost);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setInvoiceNumber($orderId);

        $baseUrl = Yii::$app->request->hostInfo;
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($baseUrl."/site/processing?success=1&id=".$orderId)
            ->setCancelUrl($baseUrl."/site/processing?success=0&id=".$orderId);

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {

            $payment->create($this->getApicontext());//create payment

            $this->rememberPayment($orderId);//remember details for a potential payment

            $approvalUrl = $payment->getApprovalLink();//get a link to paypal

            $this->goToPaypal($approvalUrl);// exit from yii app and go to paypal for paying


        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            //TODO: payment error processing here (app)

            return false;
            //echo $ex->getCode(); // Prints the Error Code (debug)
            //echo $ex->getData(); // Prints the detailed error message (debug)
            //die($ex);
        } catch (Exception $ex) {
            
            //TODO: payment error processing here (app)
            return false;
            //die($ex);
        }

        return false;
    }

    private function getOrderNumber()
    {
        return uniqid();//For example
    }

    private function rememberPayment($orderId)
    {
        $customerId = Yii::$app->user->identity->getId();
        $customerName = Yii::$app->user->identity->getUserName();

        $payment = new Payments();
        $payment->payment_id = $orderId;
        $payment->user_id = $customerId;
        $payment->user_name = $customerName;//For a simple solution only
        $payment->save();

        return true;
    }

    private function goToPaypal($approvalUrl)
    {
        header("Location: ".$approvalUrl);
        exit;
    }
}