<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%customers}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $chatId
 * @property integer $activeScenario
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

}
