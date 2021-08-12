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
        $this->setLoggedUser();
    }

    public function setLoggedUser()
    {
        if ($this->loggedUser) {
            return $this->loggedUser;
        }

        if (!Yii::$app->user) {
            $this->loggedUser = 'Misafir';
            return $this->loggedUser;
        }

        if (Yii::$app->user->isGuest) {
            $this->loggedUser = 'Misafir';
            return $this->loggedUser;
        }

        $model = Yii::$app->user->identity;
        if ($model->hasAttribute('email')) {
            $this->loggedUser = $model->email;
            return $this->loggedUser;
        }

        $this->loggedUser = $model->id;
        return $this->loggedUser;
    }
}
