<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $role = "";
$username_err = $password_err = $role_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Check if role is selected
    if(empty(trim($_POST["role"]))){
        $role_err = "Please select a role.";
    } else{
        $role = trim($_POST["role"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($role_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, role FROM users WHERE username = ? AND role = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_role);
            
            // Set parameters
            $param_username = $username;
            $param_role = $role;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;
                            
                            // Update last login time
                            $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
                            if($update_stmt = mysqli_prepare($conn, $update_sql)){
                                mysqli_stmt_bind_param($update_stmt, "i", $id);
                                mysqli_stmt_execute($update_stmt);
                                mysqli_stmt_close($update_stmt);
                            }
                            
                            // Return success response
                            echo json_encode([
                                "success" => true,
                                "message" => "Login successful",
                                "user" => [
                                    "username" => $username,
                                    "role" => $role
                                ]
                            ]);
                        } else{
                            // Password is not valid
                            echo json_encode([
                                "success" => false,
                                "message" => "Invalid password."
                            ]);
                        }
                    }
                } else{
                    // Username doesn't exist
                    echo json_encode([
                        "success" => false,
                        "message" => "No account found with that username and role."
                    ]);
                }
            } else{
                echo json_encode([
                    "success" => false,
                    "message" => "Oops! Something went wrong. Please try again later."
                ]);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
        // Return validation errors
        echo json_encode([
            "success" => false,
            "message" => "Validation failed",
            "errors" => [
                "username" => $username_err,
                "password" => $password_err,
                "role" => $role_err
            ]
        ]);
    }
    
    // Close connection
    mysqli_close($conn);
}
?> 