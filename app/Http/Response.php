<?php 

namespace App\Http;

use App\Http\Request;

class Response {
    
    public $status;
    public $route;
    public $code;
    public $url;
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->code = $this->getCode();
        $this->status = $this->getStatus();
        $this->url = $this->request->getUrl();
    }

    public function getCode() 
    {
        $currentCode = http_response_code();
        return $currentCode ? $currentCode : 200;
    }

    public function getStatus()
    {
        $statuses = [
            200 => 'OK',
            301 => 'Moved Permanently',
            302 => 'Found',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
        ];

        return $statuses[$this->code] ?? 'Unknown Status';
    }

    public function defineMessageByUrl($url, $errors = []) {
        $fileMap = [
            '/errors' => [
                'code' => 500,
                'message' => !empty($errors) ? implode(', ', $errors) : 'An internal server error occurred. Please try again later.',
                'errors' => $errors,
            ],
            '/standard' => [
                'code' => 503,
                'message' => 'The service is currently unavailable. Please check back later.',
            ],
            '/not-found' => [
                'code' => 404,
                'message' => 'The requested URL was not found on this server. Please verify the URL and try again.',
            ],
        ];
    
        return $fileMap[$url];
    }

    public function setStatusCode(int $code) 
    {
        $this->code = $code;
        $this->status = $this->getStatus();
        return http_response_code($code);
    }

    public function redirect($url, int $code = 200, array $dialog = []) : void
    {
        $this->setStatusCode($code);
        header("Location: $url");
        exit();
    }

    public function redirectToError(int $code) 
    {
        $this->setStatusCode($code);
        header("Location: /errors");
        exit();
    }

    public function reloadPage() 
    {
        $this->redirect($this->url);
    }

    public function handlePost($data) 
    {
        if ($data) {
            $this->setStatusCode(201);
            echo "Recurso criado com sucesso!";
        } else {
            $this->setStatusCode(400);
            echo "Erro ao processar a requisição.";
        }
    }
 
    public function handlePut($data) 
    {
        if ($data) {
            $this->setStatusCode(200);
            echo "Recurso atualizado com sucesso!";
        } else {
            $this->setStatusCode(400);
            echo "Erro ao atualizar o recurso.";
        }
    }
 
    public function handleDelete($resourceId) 
    {
        if ($resourceId) {
            $this->setStatusCode(204);
            echo "Recurso excluído com sucesso!";
        } else {
            $this->setStatusCode(404);
            echo "Recurso não encontrado.";
        }
    }
}

?>
