<?php

require_once("includes/classes/Constants.php");

class Account {

    private $con;
    private $errors = array();

    public function __construct($con) {
        $this->con = $con;
    }

    public function register($firstName, $lastName, $username, $email, $email2,
                                $password, $password2) {
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateUsername($username);
        $this->validateEmail($email, $email2);
        $this->validatePassword($password, $password2);
    }

    /*
     * A first name will be valid if:
     * - it has between 2 and 25 characters
    */
    private function validateFirstName($firstName) {
        if(strlen($firstName) < 2 || strlen($firstName) > 25) {
            array_push($this->errors, Constants::$firstNameCharacters);
        }
    }

    /*
     * A last name will be valid if:
     * - it has between 2 and 25 characters
    */
    private function validateLastName($lastName) {
        if(strlen($lastName) < 2 || strlen($lastName) > 25) {
            array_push($this->errors, Constants::$lastNameCharacters);
        }
    }

    /*
     * An username will be valid if:
     * - it has between 2 and 25 characters
     * - it has not been used by some other user
    */
    private function validateUsername($username) {
        if(strlen($username) < 2 || strlen($username) > 25) {
            array_push($this->errors, Constants::$usernameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindValue(":username", $username);

        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errors, Constants::$usernameRepeated);
        }
    }

    /*
     * An email will be valid if:
     * - it matchs the email confirmation field (email2)
     * - it has a valid syntax
     * - it has not been used by some other user
    */
    private function validateEmail($email, $email2) {
        if($email != $email2) {
            array_push($this->errors, Constants::$emailsNotMatch);
            return;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errors, Constants::$emailNotValid);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:email");
        $query->bindValue(":email", $email);

        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errors, Constants::$emailRepeated);
        }
    }

    /*
     * A password will be valid if:
     * - it matchs the password confirmation field (password2)
     * - it has between 5 and 25 characters
    */
    private function validatePassword($password, $password2) {
        if($password != $password2) {
            array_push($this->errors, Constants::$passwordsNotMatch);
            return;
        }

        if(strlen($password) < 5 || strlen($password) > 25) {
            array_push($this->errors, Constants::$passwordCharacters);
        }
    }

    public function getError($error) {
        if(in_array($error, $this->errors)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }

}

?>
