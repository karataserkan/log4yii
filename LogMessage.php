<?php
namespace karataserkan\log4yii;

use Yii;
use yii\helpers\Json;

class LogMessage extends \yii\base\BaseObject
{
    public $loggedUser;
    public $modul;
    public $process;
    public $object;

    public function init()
    {
        if (!$this->loggedUser) {
            $this->loggedUser = Yii::$app->user->isGuest ? 'Misafir' : Yii::$app->user->identity->email;
        }
    }
}
