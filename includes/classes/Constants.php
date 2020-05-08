<?php 

class Constants {
    public static $firstNameCharacters = "Your first name must be between 2 and 25 characters";
    public static $lastNameCharacters = "Your last name must be between 2 and 25 characters";

    public static $usernameCharacters = "Your username must be between 5 and 25 characters";
    public static $usernameExists = "This username already exists";

    public static $emailsDoNotMatch = "The emails do not match";
    public static $invalidEmail = "Please enter a valid email address";
    public static $emailExists = "This email already exists";

    public static $passwordsDoNotMatch = "The passwords do not match";
    public static $passwordNotAlphanumeric = "Your password must contain only letters and numbers";
    public static $passwordLength = "Your password must be between 5 and 30 characters";

    public static $loginFail = "Wrong username or password, try again";
    
}
?>