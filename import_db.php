<?php
// Import script - DELETE THIS FILE AFTER USE

$host = 'dpg-d6us0chj16oc738smev0-a.oregon-postgres.render.com';
$dbname = 'capstone_db_6lhk';
$user = 'capstone_db_6lhk_user';
$pass = 'B14b15upaQ61PihdBpPalShMDyFz7chg';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = file_get_contents('database_postgresql.sql');
    
    $conn->exec($sql);
    
    echo "Database imported successfully!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
