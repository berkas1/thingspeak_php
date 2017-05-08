<?php

//include './src/Api.php';

use berkas1\thingspeak_php\Api;
use PHPUnit\Framework\TestCase;


class ApiFunctionalTest extends \PHPUnit\Framework\TestCase
{

    protected $ts;

    protected function setUp() {
        $this->ts = new Api("9");

    }

    public function testGetFeed() {
        $requiredString = '{"channel":{"id":9,"name":"my_house",';
        $string = $this->ts->getFeed(array('results' => 1,))->getResponse();
        $this->assertSame($requiredString, substr($string, 0, 37));
    }

}