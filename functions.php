<?php
function generateActNumber(PDO $pdo) {
    $prefix = "NAISS";
    
    $stmt = $pdo->prepare("SELECT act_number FROM Birth_Act WHERE act_number LIKE ? ORDER BY act_number DESC LIMIT 1");
    $stmt->execute([$prefix . '%']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && !empty($result['act_number'])) {
        $lastActNumber = $result['act_number'];
        $numericPart = intval(str_replace($prefix, "", $lastActNumber));
        $newNumber = $numericPart + 1;
    } else {
        $newNumber = 1;
    }
    
    $newActNumber = $prefix . str_pad($newNumber, 3, "0", STR_PAD_LEFT);
    return $newActNumber;
}
?>