<?php

declare(strict_types=1);

namespace App\Request;

class Request
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    protected static $instance = null;

    public $params = [];

    public $requestMethod = '';

    protected $input = [];

    private function __construct()
    {
        $this->requestMethod = strtoupper((string)$_SERVER['REQUEST_METHOD']);

        if (in_array($this->getRequestMethod(), [self::METHOD_PUT, self::METHOD_PATCH, self::METHOD_DELETE])) {
            $input = file_get_contents('php://input');
            $this->input = [];
            if (function_exists('mb_parse_str')) {
                mb_parse_str($input, $this->input);
            } else {
                parse_str($input, $this->input);
            }
        }
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getFiles()
    {
        return $_FILES;
    }

    public function getFile(string $fileIndex, $tempFolder = '/tmp'): ?string {
        $files = $this->getFiles() ?: [];
        $file = isset($files[$fileIndex]) ? $files[$fileIndex] : null;
        if ($file === null || $file['error'] !== 0) {
            return null;
        }
        $result = $tempFolder . '/' . $file['name'];
        if (\copy($file['tmp_name'], $result)) {
            return $result;
        }
        return null;
    }

    public function get($key = null)
    {
        $key = (string)$key;

        $value = null;

        if (isset($this->params[$key])) {
            $value = $this->params[$key];
        } else if ($this->isSetPost($key)) {
            $value = $this->getPost($key);
        } else if ($this->isSetGet($key)) {
            $value = $this->getGet($key);
        }

        return $value;
    }

    public function getGet($key = null, $default = null)
    {
        if (null === $key && $this->isGet()) {
            return $_GET;
        }

        $key = (string)$key;

        return $this->isSetGet($key) ? $_GET[$key] : $default;
    }

    public function getPost($key = null, $default = null)
    {
        if (null === $key && $this->isPost()) {
            return (array)$_POST;
        }

        $key = (string)$key;

        return $this->isSetPost($key) ? $_POST[$key] : $default;
    }

    public function getRequest($key = null)
    {
        if (null === $key) {
            return $_REQUEST;
        }

        $key = (string)$key;

        return $this->isSetRequest($key) ? $_REQUEST[$key] : null;
    }

    public function isSetGet($key)
    {
        if ($key === null) {
            return false;
        }

        if ($this->isGet() && isset($_GET[$key]) && ($_GET[$key] !== null)) {
            return true;
        }

        return false;
    }

    public function isSetPost($key)
    {
        if ($key === null) {
            return false;
        }

        if ($this->isPost() && isset($_POST[$key]) && ($_POST[$key] !== null)) {
            return true;
        }

        return false;
    }

    public function isSetRequest($key)
    {
        if ($key === null) {
            return false;
        }

        if ($this->isRequest() && ($_REQUEST[$key] !== null)) {
            return true;
        }

        return false;
    }

    public function isGet()
    {
        if ($_GET !== null && is_array($_GET) && !empty($_GET)) {
            return true;
        }

        return false;
    }

    public function isPost()
    {
        if ($_POST !== null && is_array($_POST) && !empty($_POST)) {
            return true;
        }

        return false;
    }

    public function isRequest()
    {
        if ($_REQUEST !== null && is_array($_REQUEST) && !empty($_REQUEST)) {
            return true;
        }

        return false;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function isPostRequest()
    {
        return $this->requestMethod === self::METHOD_POST;
    }

    public function isGetRequest()
    {
        return $this->requestMethod === self::METHOD_GET;
    }

    public function getBody(): string
    {
        return (string)file_get_contents('php://input');
    }

    public function getParsedJsonBody(): array
    {
        $body = $this->getBody();
        if (empty($body)) {
            return [];
        }

        $data = json_decode($body, true);
        if (json_last_error()) {
            trigger_error(json_last_error_msg());
            return [];
        }

        return $data;
    }


    public function getUploadedFiles(): array
    {
        $uploadedFiles = $this->getFiles();
        $parsedFiles = [];

        foreach ($uploadedFiles as $input => $files) {
            $fileCount = count($files['name']);
            $fileAttributes = array_keys($files);
            for ($i = 0; $i < $fileCount; $i++) {
                foreach ($fileAttributes as $key) {
                    $parsedFiles[$input][$i][$key] = $files[$key][$i];
                }
            }
        }

        return $parsedFiles;
    }
}
