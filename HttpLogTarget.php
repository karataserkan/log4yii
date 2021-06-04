<?php
namespace karataserkan\log4yii;

use Yii;
use yii\httpclient\Client;

class HttpLogTarget extends LogTarget
{
    private $_client;
    public $baseUrl;

    public function sendLog($message)
    {
        $client = $this->getClient();
        if (!$client) {
            return;
        }
        try {
            $client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setMethod('POST')
                ->addHeaders(['content-type' => 'application/json'])
                ->setContent($message)
                ->setOptions(['timeout' => 1])
                ->send();
        } catch (\Exception $e) {
        }
    }

    public function getClient()
    {
        if (!$this->baseUrl) {
            return false;
        }
        if ($this->_client) {
            return $this->_client;
        }
        $this->_client = new Client(['baseUrl' => $this->baseUrl]);
        return $this->_client;
    }
}
