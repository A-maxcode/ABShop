<?php
/**
 * ABShop Database Configuration
 * Pre-filled with your InfinityFree details
 */

define('DB_HOST', 'sql106.infinityfree.com');
define('DB_NAME', 'if0_42897329_abshop');
define('DB_USER', 'if0_42897329');
define('DB_PASS', 'GeloVives010126');   // ← Put your real MySQL password here
define('DB_CHARSET', 'utf8mb4');

/**
 * Create PDO connection
 */
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Database connection failed. Please check config/database.php (especially the password).');
        }
    }

    return $pdo;
}
