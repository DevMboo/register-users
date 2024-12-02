<?php 

namespace App\Http;

class Request {

    protected $url = "";

    protected $method = "";

    protected $uri = "";

    protected $params = [];

    public function __construct() 
    {
        $this->url = $this->getUrl();
        $this->uri = $this->getUri();
        $this->method = $this->getMethod();
        $this->params = $this->getUrlParams();
    }

    private function getCurrentUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS'])) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];

        return $protocol . $host . $requestUri;
    }

    public function getUrl()
    {
        $appUrl = getenv('APP_URL');

        $currentUrl = $this->getCurrentUrl();

        if (strpos($currentUrl, $appUrl) === 0) {
            return $currentUrl;
        }

        return http_response_code(500);
    }

    public function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUrlParams()
    {
        $segments = explode('/', trim($this->uri, '/'));
        
        return array_slice($segments, 1);
    }

    public function getParams()
    {
        return $this->params;
    }
}