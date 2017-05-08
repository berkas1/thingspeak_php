<?php

//require './vendor/autoload.php';
include './src/Api.php';

use berkas1\thingspeak_php\Api;
use PHPUnit\Framework\TestCase;


class ApiTest extends \PHPUnit\Framework\TestCase
{
    protected $ts;

    protected function setUp() {
        $this->ts = new Api("9");

    }

    public function testResponseFormat() {
        $this->assertSame("json", $this->ts->getResponseFormat());
        $this->ts->setResponseFormat("xml");
        $this->assertSame("xml", $this->ts->getResponseFormat());
    }

    public function testApiKey() {
        $this->assertSame(null, $this->ts->getApiKey());
        $this->ts->setApiKey("asdf24#");
        $this->assertSame("asdf24#", $this->ts->getApiKey());
        $this->ts->setApiKey(null);

    }

    public function testGetServerUrl()
    {
        $this->assertSame("https://api.thingspeak.com/", $this->ts->getServerUrl());
    }

    public function testGetResponseFormat()
    {
        $this->assertSame("json", $this->ts->getResponseFormat());
    }


}