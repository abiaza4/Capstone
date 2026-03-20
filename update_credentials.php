<?php
// Update credentials script - DELETE AFTER USE

$host = 'dpg-d6us0chj16oc738smev0-a.oregon-postgres.render.com';
$dbname = 'capstone_db_6lhk';
$user = 'capstone_db_6lhk_user';
$pass = 'B14b15upaQ61PihdBpPalShMDyFz7chg';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update admin credentials
    $adminPassword = password_hash('groupadmin001', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE admins SET password = ?, username = 'groupadmin001' WHERE id = 1");
    $stmt->execute([$adminPassword]);
    
    // Insert/update member user
    $memberPassword = password_hash('member001', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES ('Member', 'member@gmail.com', ?) ON CONFLICT (email) DO UPDATE SET password = ?");
    $stmt->execute([$memberPassword, $memberPassword]);
    
    echo "Credentials updated successfully!";
    echo "<br>Admin: groupadmin001 / groupadmin001";
    echo "<br>User: member@gmail.com / member001";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
