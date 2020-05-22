<?php 

class Account {

    private $con;
    private $errorArray = array();
    
    public function __construct($con) {
        $this->con = $con;
    }

    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUsername($un);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if (empty($this->errorArray)) {
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        } else {
            return false;
        }
    }

    public function login($username, $password) {
        $password = hash('sha512', $password);
        $query = $this->con->prepare("SELECT username FROM users WHERE username=:username AND password=:password");

        $query->bindParam(":username", $username);
        $query->bindParam(":password", $password);

        $query->execute();

        if ($query->rowCount() != 0) {
            return true;
        } else {
            array_push($this->errorArray, Constants::$loginFail);
            return false;
        }

    }

    public function insertUserDetails($fn, $ln, $un, $em, $pw) {

        $pw = hash("sha512", $pw);
        $profilePic = "assets/images/profilePictures/default.png";
        
        $query = $this->con->prepare("INSERT into users(firstName, lastName, username, email, password, profilePic)
                                            values (:firstName, :lastName, :username, :email, :password, :profilePic)");

        $query->bindParam(":firstName", $fn);
        $query->bindParam(":lastName", $ln);
        $query->bindParam(":username", $un);
        $query->bindParam(":email", $em);
        $query->bindParam(":password", $pw);
        $query->bindParam(":profilePic", $profilePic);

        return $query->execute();
    }

    private function validateFirstName($fn) {
        // Validate length of first name
        if (strlen($fn) > 25 || strlen($fn) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
        }
    }

    private function validateLastName($ln) {
        // Validate length of last name
        if (strlen($ln) > 25 || strlen($ln) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }


     private function validateUsername($un) {
         // Validate length of username
        if (strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        // Try to get a username with the current username to check if exists
        $query = $this->con->prepare("SELECT username FROM users WHERE username=:un");
        $query->bindParam(":un", $un);
        $query->execute();

        // If row is different than 0 then username exists
        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameExists);
        }
    }

    private function validateEmails($em, $em2) {

        // Check emails match validation
        if ($em != $em2) {
            array_push($this->errorArray, Constants::$emailsDoNotMatch);
            return;
        }
        
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$invalidEmail);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:em");
        $query->bindParam(":em", $em);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailExists);
        }
    }

    private function validatePasswords($pw, $pw2) {
        // Check passwords match validation
        if ($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDoNotMatch);
            return;
        }
        
        // Compare the password to a regular expression pattern 
        if (preg_match("/[^A-Za-z0-9]/", $pw)) {
            array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
            return;
        }

        if (strlen($pw) > 30 || strlen($pw) < 5) {
            array_push($this->errorArray, Constants::$passwordLength);
        }
    }

    public function getError($error) {
        if(in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }

}

?>