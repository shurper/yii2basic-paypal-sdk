<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('paymentSuccess')){ ?>

        <div class="alert alert-success">
           <h2>
               <?= $model->userName ?>, thank you for your payment number: <?= $model->id ?>!
           </h2>
        </div>

    <?php } elseif (Yii::$app->session->hasFlash('paymentError')){ ?>

        <div class="alert alert-error">
            <h2>
                <?= $model->userName ?>, your payment number <?= $model->id ?> canceled!
            </h2>

        </div>

    <?php } else{ ?>

        <h3>
            You can pay here :)(
        </h3>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'payment-form']); ?>

                <?= $form->field($model, 'subject')->textInput([
                    'autofocus' => true,
                    'value' => 'cup of coffee'
                ]) ?>

                <?= $form->field($model, 'amount')->textInput([
                    'value' => '20'
                ]) ?>

                <?= $form->field($model, 'currancy')->dropDownList([
                    'USD' => 'USD',
                    'RUB' => 'RUB',
                    'EUR' => 'EUR'
                ]); ?>


                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php }; ?>
</div>
