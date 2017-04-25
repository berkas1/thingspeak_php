thingspeak_php is a library which provides a simple way to communicate with [thingspeak.com](https://thingspeak.com) service

Requires only *php-curl*

[![Build Status](https://travis-ci.org/berkas1/thingspeak_php.svg?branch=master)](https://travis-ci.org/berkas1/thingspeak_php)
 
### Installation
```composer require berkas1/thingspeak_php```


### Example of use

method *setResponseFormat()* can set up response format to JSON or XML
method *getResponse()* will return response retrieved from thingspeak.com (JSON or XML)

All kind of parameters ($params) should match those listed [here](https://www.mathworks.com/help/thingspeak/channels-and-charts.html).

```php

require __DIR__ . '/vendor/autoload.php';

$ts = new \berkas1\thingspeak_php\Api(channel_id, channel_api_key);
// you can set required response format, XML and JSON currently supported
$ts->setResponseFormat("xml");

// create a channel (you need to provide user api_key in $params)
$ts->createChannel($params)->getResponse();

// update a channel
$ts->updateChannel($params)->getResponse();

// get a feed
$ts->getFeed($params)->getResponse();

// get a field feed
$ts->getFieldFeed($fieldId, $params)->getResponse();

// list public channels
$ts->listPublicChannels($params)->getResponse();

// get channel status
$ts->getStatus()->getResponse();

```