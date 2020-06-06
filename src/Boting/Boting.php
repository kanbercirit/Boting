<?php
namespace Boting;

use AsyncRequest\Request;
use AsyncRequest\Response;
use AsyncRequest\AsyncRequest;
use AsyncRequest\IRequest;

use Exception;

class Boting {
    private $Base = "https://api.telegram.org/bot";
    public $Token = "";
    private $asyncRequest;
    public $asyncMethod;
    private $LatUpdate;
    private $sonuc;
    private $sonucm;
    public $httpSonuc;
    public $offset;

    public function __construct($token) {
        $this->LatUpdate = 0;
        $this->UpdatesReceived = [];

        $this->Token = $token;
        $this->sonucm = "";
        $this->sonuc = [];
        $this->offset = -1;
        $this->asyncRequest = new AsyncRequest();
        $this->asyncMethod = new AsyncRequest();          
    }

    public function getUpdates() {
        $this->sonuc = [];
        $this->asyncRequest->enqueue(new Request($this->Base . $this->Token . '/getUpdates?timeout=10&offset=-2'), function (Response $response) {$this->sonuc = $response->getBody();});
        $this->asyncRequest->run(); 
        if ($this->httpSonuc == "409") {
            echo "\nInvalid token\n";
            die();
        }

        $sonuc = array_reverse(json_decode($this->sonuc, true));
        if ($this->LatUpdate == $sonuc["result"][count($sonuc) - 1]["update_id"]) {
            return;
        }
        $Sonucc[0] = $sonuc["result"][0];
        for ($i = 0; $i < count($sonuc); $i++) {
            $ilk = $sonuc["result"][0];
            $son = $sonuc["result"][$i];
            $this->LatUpdate = $son["update_id"];
            if (($i != 0) and ($ilk["update_id"] != $son["update_id"]) and ($ilk["message"]["from"]["id"] != $son["message"]["from"]["id"])) $Sonucc[$i] = $sonuc["result"][$i];
        }
        return $Sonucc;
    }

    public function __call($method, $args) {
        $msonuc = function (Response $response) {
            $this->sonucm = $response->getBody();
        };

        $Istek = new Request($this->Base . $this->Token . "/" . $method);

        $args = $args[0];
        $Istek->setOption(CURLOPT_POST, true);
        $Istek->setOption(CURLOPT_POSTFIELDS, http_build_query($args));

        $this->asyncMethod->enqueue($Istek, $msonuc);
    }
}
