<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Priorité session id_user, sinon celui passé dans fetch
$user_id = $_SESSION['id_user'] ?? ($data['user_id'] ?? null);

if (!$user_id) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

if (!$data || !isset($data['screen']) || !isset($data['os'])) {
    echo json_encode(['error' => 'Données manquantes']);
    exit;
}

$screen = $data['screen'];
$os = $data['os'];
$login_time = date('Y-m-d H:i:s');

try {
    $bdd = new PDO('mysql:host=localhost;dbname=boulangerie;charset=utf8', 'user', 'pass');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO logins (user_id, login_time, screen_resolution, operating_system) VALUES (?, ?, ?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$user_id, $login_time, $screen, $os]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
