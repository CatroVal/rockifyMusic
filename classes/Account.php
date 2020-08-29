<?php

class Account {

    private $conn;
    private $errorArray;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->errorArray = array();
    }

    public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
        //Validacion de datos del formulario de registro
        $this->validateUsername($un);
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if(empty($this->errorArray)) {
            //Si no hay errores en la validacion, insertar datos en la tabla users de la BBDD
            return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
        } else {
            return false;
        }
    }

    public function getError($error) {
        if(!in_array($error, $this->errorArray)) {
            $error = "";
        }

        return "<span class='errorMessage'>$error</span>";
    }

    private function  insertUserDetails($un, $fn, $ln, $em, $pw) {
        $encryptedPw = md5($pw);
        $date = date("Y-m-d");
        $profilePic = "assets/images/users-avatars/avatar-icon-red.png";

        $result = mysqli_query($this->conn, "INSERT INTO users VALUES( null, '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

        return $result;
    }

    private function validateUsername($un) {
        if(strlen($un) < 5 || strlen($un) > 20) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }
        //Comprobar que el username no existe
        $checkUsernameQuery = mysqli_query($this->conn, "SELECT username FROM users WHERE username='$un'");

        if(mysqli_num_rows($checkUsernameQuery) != 0 ) {
            array_push($this->errorArray, Constants::$usernameTaken);
            return;
        }
    }

    private function validateFirstName($fn) {
        if(strlen($fn) < 2 || strlen($fn) > 20) {
            array_push($this->errorArray, Constants::$firstnameCharacters);
            return;
        }
    }

    private function validateLastName($ln) {
        if(strlen($ln) < 2 || strlen($ln) > 20) {
            array_push($this->errorArray, Constants::$lastnameCharacters);
            return;
        }
    }

    private function validateEmails($em, $em2) {
        if($em != $em2) {
            array_push($this->errorArray, Constants::$emailsNotMatch);
            return;
        }

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        //Comprobar que el email no existe
        $checkEmailQuery = mysqli_query($this->conn, "SELECT email FROM users WHERE email='$em'");

        if(mysqli_num_rows($checkEmailQuery) != 0 ) {
            array_push($this->errorArray, Constants::$emailTaken);
            return;
        }
    }

    private function validatePasswords($pw, $pw2) {
        if($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsNotMatch);
            return;
        }

        if(strlen($pw) < 6 || strlen($pw) > 20) {
            array_push($this->errorArray, Constants::$passwordCharacters);
            return;
        }
        if(preg_match("/[^A-Za-z0-9]/", $pw)) {
            array_push($this->errorArray, Constants::$passwordAlphanumeric);
        }
    }

    public function login($un, $pw) {
        $pw = md5($pw);

        $query = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

        if(mysqli_num_rows($query) == 1 ) {
            return true;
        } else {
            array_push($this->errorArray, Constants::$loginInvalid);
            return;
        }
    }
}
