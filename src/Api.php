<?php
//File Api.php
//Date 27/03/17


namespace berkas1\thingspeak_php;


class Api {

    const THINGSPEAK_URL = "https://api.thingspeak.com/";

    protected $apikey;
    protected $channel_id;
    protected $response_format;
    protected $response_code;
    protected $response;


    public function __construct($channel_id = null, $apikey = null, $type = "json") {
        $this->channel_id = $channel_id;
        $this->apikey = $apikey;
        $this->response_format = $type;

        return $this;

    }


    /**
     * Create new channel.
     * For parameters please see https://www.mathworks.com/help/thingspeak/create-a-channel.html
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function createChannel($params = array()) {
        if (!array_key_exists("api_key", $params)) {
            throw new \Exception("No user api_key provided. Please see https://www.mathworks.com/help/thingspeak/create-a-channel.html");
        }

        $uri = 'channels';

        $this->makePostRequest($uri, $params);

        return $this;
    }


    /**
     * Update a channel.
     * For parameters please see https://www.mathworks.com/help/thingspeak/update-a-channel.html
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function updateChannel($params = array()) {
        if (!array_key_exists("api_key", $params)) {
            throw new \Exception("No user api_key provided. Please see https://www.mathworks.com/help/thingspeak/create-a-channel.html");
        }

        $uri = 'channels/' . $this->channel_id;

        $this->makePutRequest($uri, $params);

        return $this;
    }

/*
    public function viewChannel() {
        $this->makeGetRequest("channels/" . $this->channel_id);
        return $this;
    }
*/

    /**
     * Get a channel feed.
     * For parameters please see https://www.mathworks.com/help/thingspeak/get-a-channel-feed.html
     * @param array $params
     * @return $this
     */
    public function getFeed($params = array()) {
        $uri = 'channels/' . $this->channel_id . '/feeds.' . $this->response_format .
           '?api_key=' . $this->apikey  ;

        $uri .= $this->parseParameters($params);

        $this->makeGetRequest($uri);

        return $this;
    }

    /**
     * Get field feed.
     * For parameters please see https://www.mathworks.com/help/thingspeak/get-channel-field-feed.html
     * @param number $fieldId Field ID to be retrieved
     * @param array $params
     * @return $this
     */
    public function getFieldFeed($fieldId, $params = array()) {
        $uri = 'channels/' . $this->channel_id . '/fields/' . $fieldId . '.' . $this->response_format .
          '?api_key=' . $this->apikey  ;

        $uri .= $this->parseParameters($params);

        $this->makeGetRequest($uri);

        return $this;
    }


    /**
     * Update a channel feed.
     * For parameters please see https://www.mathworks.com/help/thingspeak/update-channel-feed.html
     * @param array $params
     * @return $this
     */
    public function updateChannelFeed($params = array()) {
        $uri = 'update';

        $params['api_key'] = $this->apikey;

        $this->makePostRequest($uri, $params);

        return $this;
    }


    /**
     * List public channels.
     * For parameters please see https://www.mathworks.com/help/thingspeak/list-public-channels.html
     * @param array $params
     * @return $this
     */
    public function listPublicChannels($params = array()) {
        $uri = 'channels/' . '/public.' . $this->response_format .
          '?api_key=' . $this->apikey  ;

        if (array_key_exists("page", $params) && is_numeric(['page'])) {
            $uri .= '&page=' . $params['page'];
        }
        if (array_key_exists("tag", $params)) {
            $uri .= '&tag=' . $params['tag'];
        }

        if (array_key_exists("username", $params)) {
            $uri .= '&username=' . $params['username'];
        }

        $this->makeGetRequest($uri);

        return $this;
    }


    /**
     * Get channel status.
     * @return $this
     */
    public function getStatus() {
        $uri = 'channels/' . $this->channel_id . '/status.' . $this->response_format . '?api_key=' . $this->apikey;

        $this->makeGetRequest($uri);

        return $this;
    }


    /**
     * *Will provide check of entered parameters in the future*
     * @param array $params
     * @return string
     */
    protected function parseParameters($params) {
        $uri = "";
        if (array_key_exists("days", $params)) {
            $uri .= '&days=' . $params['days'];
        }

        if (array_key_exists("results", $params)) {
            $uri .= '&results=' . $params['results'];
        }

        if (array_key_exists("min", $params)) {
            $uri .= '&min=' . $params['min'];
        }

        if (array_key_exists("max", $params)) {
            $uri .= '&max=' . $params['max'];
        }

        if (array_key_exists("metadata", $params)) {
            if ($params['metadata'] == true || $params['metadata'] == false) {
                $uri .= '&metadata=' . $params['metadata'];
            }
        }

        if (array_key_exists("round", $params)) {
            $uri .= '&round=' . $params['round'];
        }

        if(array_key_exists("start", $params)) {
            $uri .= '&start=' . $params['start'];
        }

        if (array_key_exists("end", $params)) {
            $uri .= '&end=' . $params['end'];
        }

        if (array_key_exists("timezone", $params)) {
            $uri .= '&timezone=' . $params['timezone'];
        }

        if (array_key_exists("location", $params)) {
            $uri .= '&location=' . $params['location'];
        }

        if (array_key_exists("median", $params)) {
            $uri .= '&median=' . $params['median'];
        }



        return $uri;
    }


    /**
     * Make HTTP GET request.
     * @param string $uri
     */
    protected function makeGetRequest($uri) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => $this::THINGSPEAK_URL . $uri,
          CURLOPT_USERAGENT => '',
        ));
        $this->response = curl_exec($curl);
        $this->response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

    }

    /**
     * Make HTTP POST request.
     * @param string $uri
     * @param array $params
     */
    protected function makePostRequest($uri, $params) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$this::THINGSPEAK_URL . $uri);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,
                  http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($curl);
        $this->response = $output;
        $this->response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close ($curl);
    }


    /**
     * Make HTTP PUT request.
     * @param string $uri
     * @param array $params
     */
    protected function makePutRequest($uri, $params) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$this::THINGSPEAK_URL . $uri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS,
          http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($curl);
        $this->response = $output;
        $this->response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close ($curl);
    }

    /**
     * @return mixed
     */
    public function getApikey() {
        return $this->apikey;
    }

    /**
     * @param mixed $apikey
     */
    public function setApikey($apikey) {
        $this->apikey = $apikey;
    }

    /**
     * @return mixed
     */
    public function getChannelId() {
        return $this->channel_id;
    }

    /**
     * @param mixed $channel_id
     */
    public function setChannelId($channel_id) {
        $this->channel_id = $channel_id;
    }

    /**
     * @return mixed
     */
    public function getResponseFormat() {
        return $this->response_format;
    }

    /**
     * @param mixed $response_format
     */
    public function setResponseFormat($response_format) {
        $this->response_format = $response_format;
    }

    /**
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response) {
        $this->response = $response;
    }


    /**
     * @return mixed
     */
    public function getResponseCode() {
        return $this->response_code;
    }



}