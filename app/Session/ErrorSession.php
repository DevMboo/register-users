<?php 

namespace App\Session;

class ErrorSession {
    
    public static function setErrors(array $errors)
    {
        $_SESSION['errors'] = $errors;
    }

    public static function getErrors()
    {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        return $errors;
    }
}
