<?php
namespace karataserkan\log4yii;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\log\Target;

class LogTarget extends Target
{
    public $microtime = true;

    public function export()
    {
        $messages = array_map([$this, 'prepareMessage'], $this->messages);
        foreach ($messages as $message) {
            $this->sendLog($message);
        }
    }

    public function init()
    {
        if (!method_exists(Yii::$app->request, 'getId')) {
            Yii::$app->request->attachBehavior('log4yii', RequestBehavior::class);
        }
    }

    public function sendLog($message)
    {
    }

    public function prepareMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $user_id = '-';
        if (Yii::$app->get('user', false)) {
            $user_id = (!Yii::$app->user || Yii::$app->user->isGuest) ? '-' : Yii::$app->user->identity->id;
        }

        $result = [
            'application' => Yii::$app->name,
            'category'    => $category,
            'log_level'   => Logger::getLevelName($level),
            'user_id'     => $user_id,
            'timestamp'   => $timestamp * 1000,
            'ip_src_addr' => Yii::$app->request->getUserIp(),
            'request_id'  => Yii::$app->request->getId(),
            'route'       => Yii::$app->request->pathInfo,
        ];

        if (isset($message[4])) {
            $result['trace'] = $message[4];
        }

        if ($text instanceof LogMessage) {
            $result = ArrayHelper::merge($result, ArrayHelper::toArray($text));
        } else {
            if (!is_string($text)) {
                if ($text instanceof \Throwable || $text instanceof \Exception) {
                    $text = (string) $text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
            $result['message'] = $text;
        }
        $message = Json::encode($result);
        return $message."\n";
    }

    /**
     * Processes the given log messages.
     * This method will filter the given messages with [[levels]] and [[categories]].
     * And if requested, it will also export the filtering result to specific medium (e.g. email).
     * @param array $messages log messages to be processed. See [[Logger::messages]] for the structure
     * of each message.
     * @param bool $final whether this method is called at the end of the current application
     */
    public function collect($messages, $final)
    {
        $this->messages = array_merge($this->messages, static::filterMessages($messages, $this->getLevels(), $this->categories, $this->except));
        $count          = count($this->messages);
        if ($count > 0 && ($final || $this->exportInterval > 0 && $count >= $this->exportInterval)) {
            if (($context = $this->getContextMessages()) !== '') {
                foreach ($context as $key => $message) {
                    $this->messages[] = [$message, Logger::LEVEL_INFO, $key, YII_BEGIN_TIME, [], 0];
                }
            }
            // set exportInterval to 0 to avoid triggering export again while exporting
            $oldExportInterval    = $this->exportInterval;
            $this->exportInterval = 0;
            $this->export();
            $this->exportInterval = $oldExportInterval;
            $this->messages       = [];
        }
    }

    /**
     * Generates the context information to be logged.
     * The default implementation will dump user information, system variables, etc.
     * @return string the context information. If an empty string, it means no context information.
     */
    protected function getContextMessages()
    {
        $context = ArrayHelper::filter($GLOBALS, $this->logVars);
        foreach ($this->maskVars as $var) {
            if (ArrayHelper::getValue($context, $var) !== null) {
                ArrayHelper::setValue($context, $var, '***');
            }
        }
        $result = [];
        foreach ($context as $key => $value) {
            if (!empty($value) && $value != '' && $value != '[]') {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
