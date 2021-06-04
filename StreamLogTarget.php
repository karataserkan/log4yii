<?php
namespace karataserkan\log4yii;

use yii\base\InvalidConfigException;
use yii\log\LogRuntimeException;

class StreamLogTarget extends LogTarget
{
    public $url;
    protected $fileProcess;
    protected $openedFileProcess = false;

    public function __destruct()
    {
        if ($this->openedFileProcess) {
            @fclose($this->fileProcess);
        }
    }

    public function init()
    {
        if (empty($this->fileProcess) && empty($this->url)) {
            throw new InvalidConfigException("Either 'url' or 'fp' mus be set.");
        }
    }

    public function setFp($value)
    {
        if (!is_resource($value)) {
            throw new InvalidConfigException("Invalid resource.");
        }
        $metadata = stream_get_meta_data($value);
        if (!in_array($metadata['mode'], ['w', 'wb', 'a', 'ab'])) {
            throw new InvalidConfigException("Resource is not writeable.");
        }
        $this->fileProcess = $value;
    }

    public function getFileProcess()
    {
        if ($this->fileProcess === null) {
            $this->fileProcess = @fopen($this->url, 'w');
            if ($this->fileProcess === false) {
                throw new InvalidConfigException("Unable to open '{$this->url}' for writing.");
            }
            $this->openedFileProcess = true;
        }
        return $this->fileProcess;
    }

    public function closeFileProcess()
    {
        if ($this->openedFileProcess && $this->fileProcess !== null) {
            @fclose($this->fileProcess);
            $this->fileProcess       = null;
            $this->openedFileProcess = false;
        }
    }

    public function sendLog($message)
    {
        $fileProcess = $this->getFileProcess();
        if (@fwrite($fileProcess, $message) === false) {
            $error = error_get_last();
            throw new LogRuntimeException("Unable to export log!: {$error['message']}");
        }
    }
}
