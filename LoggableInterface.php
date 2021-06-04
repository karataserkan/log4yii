<?php
namespace karataserkan\log4yii;

interface LoggableInterface
{
    public function getLogMessage($process, $message = '');
}
