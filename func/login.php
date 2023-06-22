<?php
include 'config/config.php';

function login(){
    global $DB_LINK;
    global $DB_DSN;
    global $err;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $userEmail = $_POST["userEmail"];
        $userPassword = $_POST["userPassword"];
        

        if (empty(trim($userEmail))){
            $err = "Email empty";
        } elseif (empty(trim($userPassword))){
            $err = "Password empty";
        } else {
            $userEmail = trim($userEmail);
            $userPassword = trim($userPassword);
        }

        $sql = "SELECT id, email, password FROM users WHERE email = ?";

            if($stmt = mysqli_prepare($DB_LINK, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_user);
                
                // Set parameters
                $param_user = $userEmail;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Store result
                    mysqli_stmt_store_result($stmt);
                    
                    // Check if username exists, if yes then verify password
                    if(mysqli_stmt_num_rows($stmt) == 1){                    
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $id, $userEmail, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($userPassword, $hashed_password)){

                                // Password is correct, so start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["userEmail"] = $userEmail;

                                $userData = LoadDataRow('users', 'id', $id);

                                if (isset($userData['active']) && $userData['active'] === '0') {
                                    session_unset();
                                    session_destroy();
                                    $err = "Votre compte est désactivé";
                                    header("Location: login.php?error=".urlencode($err));
                                    exit();
                                }

                                $_SESSION["userFirstName"] = $userData['firstName'];
                                $_SESSION["userLastName"] = $userData['lastName'];
                                $_SESSION["UserLevel"] = $userData['UserLevel'];
                                $_SESSION["UserPhone"] = $userData['UserPhone'];
                                $_SESSION["UserAdmin"] = $userData['UserAdmin'];

                                // Set CSRF Token
                                $token = md5(uniqid(mt_rand(), true));
                                $_SESSION["csrf"] = $token;
                                UpdateUserData($id, $token);

                                // Redirect user to welcome page
                                header("location: index.php");
                            } else{
                                // Password is not valid, display a generic error message
                                $err = "Invalid username or password.";
                            }
                        }
                    } else{
                        // Username doesn't exist, display a generic error message
                        $err = "Invalid username or password.";
                    }
                } else{
                    $err = "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            };
    };
};