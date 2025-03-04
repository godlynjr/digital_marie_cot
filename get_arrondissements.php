<?php
require_once "config.php";

if (isset($_GET['commune_id']) && !empty($_GET['commune_id'])) {
    $commune_id = intval($_GET['commune_id']);
    $stmt = $pdo->prepare("SELECT id_arrondissement, name FROM Arrondissement WHERE commune_id = ? ORDER BY name ASC");
    $stmt->execute([$commune_id]);
    $arrondissements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($arrondissements);
} else {
    echo json_encode([]);
}
?>
