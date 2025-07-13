<?php
// ភ្ជាប់ទៅមូលដ្ឋានទិន្នន័យរបស់អ្នក (Connection to your database)
// ប្រើ PDO ឬ MySQLi ជាមួយ prepared statements ដើម្បីសុវត្ថិភាព
$servername = "localhost";
$username_db = "root"; // ឈ្មោះអ្នកប្រើប្រាស់សម្រាប់ database
$password_db = "";     // ពាក្យសម្ងាត់សម្រាប់ database
$dbname = "employye_db"; // ឈ្មោះ database របស់អ្នក

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
    // កំណត់ attribute PDO error mode ទៅជា exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // សម្រាប់ពិនិត្យមើលការតភ្ជាប់
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit(); // បញ្ឈប់ script ប្រសិនបើការតភ្ជាប់បរាជ័យ
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $plaintext_password = $_POST['password']; // ពាក្យសម្ងាត់ដែលបានបញ្ចូលដោយអ្នកប្រើប្រាស់

    // ១. Hash ពាក្យសម្ងាត់
    // PASSWORD_DEFAULT គឺជា algorithm ល្អបំផុតសម្រាប់ប្រើប្រាស់។
    // PHP នឹងជ្រើសរើស algorithm ដែលមានសុវត្ថិភាពបំផុត និងធ្វើការ salting ដោយស្វ័យប្រវត្តិ។
    $hashed_password = password_hash($plaintext_password, PASSWORD_DEFAULT);

    // ២. រក្សាទុក hashed password ទៅក្នុងមូលដ្ឋានទិន្នន័យ
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password_hash', $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>