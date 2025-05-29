<?php
require_once 'database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please enter both username and password']);
        exit;
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $username = $db->escape($username);
    $password = $db->escape($password);
    
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $db->query($query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        
        echo json_encode([
            'success' => true,
            'user' => [
                'username' => $user['username'],
                'role' => $user['role']
            ],
            'redirect' => $user['role'] === 'admin' ? '../pages/Admin.html' : '../pages/PAGE2.html'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
    
    $db->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 