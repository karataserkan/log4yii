<?php
namespace karataserkan\log4yii;

use Yii;
use yii\base\Behavior;

class RequestBehavior extends Behavior
{
    private $_id;

    public function init()
    {
        parent::init();
        $this->setId();
    }

    private function setId()
    {
        $this->_id = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function getId()
    {
        return $this->_id;
    }
}
