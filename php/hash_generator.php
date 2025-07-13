<?php
// php/hash_generator.php
// This script generates a Bcrypt hash for a given password.
// You can run this script in your browser (e.g., http://localhost/employee_app/php/hash_generator.php)
// to get the hashed password to insert into your database.

// Define the password you want to hash.
// For the 'manager' user, the password is 'password123'.
$password_to_hash = 'Admin';

// Generate the hash using Bcrypt algorithm.
// PASSWORD_BCRYPT is a strong, recommended hashing algorithm.
// The 'cost' parameter (default is 10) determines how much work is needed to calculate the hash,
// which helps to slow down brute-force attacks.
$hashed_password = password_hash($password_to_hash, PASSWORD_BCRYPT);

// Print the generated hash.
// You will copy this output and paste it into your SQL INSERT statement.
echo "Original Password: " . htmlspecialchars($password_to_hash) . "<br>";
echo "Generated Hash: " . htmlspecialchars($hashed_password) . "<br>";
echo "<br>Copy the 'Generated Hash' value and use it in your SQL INSERT statement.";

?>