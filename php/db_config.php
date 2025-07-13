    <?php
    

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root'); // Your MySQL username
    define('DB_PASSWORD', '');     // Your MySQL password (empty by default for WampServer)
    define('DB_NAME', 'employee_db'); // The database name you created

    // Attempt to connect to MySQL database
    try {
        $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
    }
    ?>