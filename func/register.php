<?php
include 'config/config.php';

function registerUser(){
    global $DB_DSN;
    global $err;


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST['userActive'])){
            $_POST['userActive'] = "0";
        };

        if (!isset($_POST['userAdmin'])){
            $_POST['userAdmin'] = "0";
        };

        checkEmailExist(($_POST['userEmail']));
        try {

            $new_user = [
                "userEmail"             => $_POST['userEmail'],
                "userFirstName"         => $_POST['userFirstName'],
                "userLastName"          => $_POST['userLastName'],
                "userPassword"          => password_hash($_POST['userPassword'], PASSWORD_BCRYPT),
                "userPhone"             => $_POST['userPhone'],
                "userLevel"             => $_POST['userLevel'],
                "userAdmin"             => $_POST['userAdmin'],
                "userActive"            => $_POST['userActive']
            ];

            $NewUserSQL = "INSERT INTO users (email, firstName, lastName, password, phone, level, admin, active) VALUES (:userEmail, :userFirstName, :userLastName, :userPassword, :userPhone, :userLevel, :userAdmin, :userActive)";

            $statement = $DB_DSN->prepare($NewUserSQL);
            $statement->execute($new_user);
            // header('Location: ' . $_SERVER['PHP_SELF']);

            if ($statement) {
                $created = "statement";
                LoadDataRow('users', 'id', $_GET['id']);
                $message = "User created.";
                $messagetype = "success";
            }
        } catch (Exception $e) {
            $err =  $e->getMessage();
        }
    };
};

function checkEmailExist($userEmail){
    global $err;
    global $DB_LINK;
    global $DB_DSN;

    $checkExist = $DB_DSN->prepare("SELECT email FROM users WHERE email = :email");
    $checkExist->execute([
        'email' => $userEmail
    ]);
    $email = $checkExist->fetch(PDO::FETCH_ASSOC);

    if ((isset($email)) && !empty($email)){
        $err = "Email already used";
        return $err;
    }
};