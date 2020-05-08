<?php 
class FormSanitizer {

    public static function sanitizeFormString($inputText) {
        // strip_tags is a built-in function that returns the string argument without HTML tags or PHP code
        // This is useful to prevent the user from injecting malicious code 
        $inputText = strip_tags($inputText);

        // Remove whitespaces from the beginning and the end of the string
        $inputText = trim($inputText);
        
        $inputText = strtolower($inputText);
        $arrayName = explode(" ", $inputText);
        
        for ($i = 0; $i < count($arrayName) ; $i++) {
                $arrayName[$i] =  ucfirst($arrayName[$i]);
        }
        $inputText = implode(" ", $arrayName);
        
        return $inputText;
    }

    public static function sanitizeFormUsername($username) {
        $username = strip_tags($username);
        $username = trim($username);
        return $username;
    }

    public static function sanitizeFormPassword($password) {
        $password = strip_tags($password);
        return $password;
    }

    public static function sanitizeFormEmail($email) {
        $email = strip_tags($email);
        $email = str_replace(" ", "", $email);
        return $email;
    }
}

?>