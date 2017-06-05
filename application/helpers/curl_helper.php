<?php

/**
* Curl wrapper for Yii
* @author hackerone
*/
class Curl{

    private $_ch;

    // config from config.php
    //config for curl
    public $options;

    public $header = array();

    // default config
    private $_config = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36'
        );

    private function _exec($url){
        LogUtil::info('Curl excute curl ' . $url, __METHOD__);
        $this->setOption(CURLOPT_URL, $url);
        $c = curl_exec($this->_ch);
        if(!curl_errno($this->_ch)) {
            curl_close($this->_ch);
            return $c;
        }
        else {
            throw new Exception(curl_error($this->_ch));
        }
        curl_close($this->_ch);
        return false;

    }

    public function get($url, $params = array())
    {
        $this->setOption(CURLOPT_HTTPGET, true);

        return $this->_exec($this->buildUrl($url, $params));
    }

    public function post($url, $data = array())
    {
        $this->setOption(CURLOPT_POST, 1);
        $this->setOption(CURLOPT_POSTFIELDS, $data);

        return $this->_exec($url);
    }

    public function put($url, $data, $params = array())
    {
        // write to memory/temp
        $f = fopen('php://temp', 'rw+');
        fwrite($f, $data);
        rewind($f);

        $this->setOption(CURLOPT_PUT, true);
        $this->setOption(CURLOPT_INFILE, $f);
        $this->setOption(CURLOPT_INFILESIZE, strlen($data));

        return $this->_exec($this->buildUrl($url, $params));
    }

    //when use Content-Type:application/x-www-form-urlencoded, need to use this function to format the params
    public function formatFormParams($data = array())
    {
        return http_build_query($data);
    }

    public function delete($url, $params = array(), $type = "")
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');

        if($type == "json") {
            $data = json_encode($params);
            $this->setOption(CURLOPT_POSTFIELDS, $data);
            $this->setOption(CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            return $this->_exec($url);
        }

        return $this->_exec($this->buildUrl($url, $params));
    }

    public function buildUrl($url, $data = array()){
        $parsed = parse_url($url);
        isset($parsed['query'])?parse_str($parsed['query'],$parsed['query']):$parsed['query']=array();
        $params = isset($parsed['query'])?array_merge($parsed['query'], $data):$data;
        $parsed['query'] = ($params)?'?'.http_build_query($params):'';
        if(!isset($parsed['path']))
            $parsed['path']='/';

        return $parsed['scheme'].'://'.$parsed['host'].$parsed['path'].$parsed['query'];
    }

    public function setOptions($options = array()){
        curl_setopt_array( $this->_ch , $options);

        return $this;
    }

    public function setOption($option, $value){
        curl_setopt($this->_ch, $option, $value);

        return $this;
    }

    public function setHeaders($header = array())
    {
        if($this->_isAssoc($header)){
            $out = array();
            foreach($header as $k => $v){
                $out[] = $k .' : '.$v;
            }
            $header = $out;
        }

        $this->setOption(CURLOPT_HTTPHEADER, $header);

        return $this;
    }

    //check is k-v format
    private function _isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function getError()
    {
        return curl_error($this->_ch);
    }

    public function getInfo()
    {
        return curl_getinfo($this->_ch);
    }

    public function __construct()
    {
        try{
            $this->_ch = curl_init();
            $options = is_array($this->options)? ($this->options + $this->_config):$this->_config;
            $this->setOptions($options);
        
        }catch(Exception $e){
            throw new Exception('Curl not installed');
        }
    }
}