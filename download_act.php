<?php

require_once "config.php";
require_once "fpdf/fpdf.php";

$act_type = $_GET['act_type'] ?? '';
$act_id   = $_GET['act_id'] ?? '';

if (empty($act_type) || empty($act_id)) {
    die("Type d'acte ou ID d'acte non spécifié.");
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

if ($act_type === 'birth') {
    $stmt = $pdo->prepare("
        SELECT 
            b.*,
            c.first_name AS child_first_name,
            c.last_name AS child_last_name,
            fp.first_name AS father_first_name,
            fp.last_name AS father_last_name,
            fp.profession AS father_profession,
            mp.first_name AS mother_first_name,
            mp.last_name AS mother_last_name,
            mp.profession AS mother_profession
        FROM Birth_Act b
        JOIN Person c ON b.child_id = c.id_person
        LEFT JOIN Person fp ON b.father_id = fp.id_person
        LEFT JOIN Person mp ON b.mother_id = mp.id_person
        WHERE b.id_birth_act = ?
    ");
    $stmt->execute([$act_id]);
    $act = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$act) {
        die("Acte de naissance non trouvé.");
    }
    
    $pdf->Cell(0,10, "Acte de Naissance - " . $act['act_number'], 0, 1, "C");
    $pdf->SetFont('Arial','',12);
    $pdf->Ln(5);
    $pdf->Cell(60,10, "Date Declaration: " . $act['date_declaration'], 0, 1);
    $pdf->Cell(60,10, "Date of Birth: " . $act['date_of_birth'], 0, 1);
    $pdf->Cell(60,10, "Time of Birth: " . $act['time_of_birth'], 0, 1);
    $pdf->Cell(60,10, "Place of Birth: " . $act['place_of_birth'], 0, 1);
    $pdf->Cell(60,10, "Sex: " . $act['sex'], 0, 1);
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10, "Informations sur l'enfant", 0, 1);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(60,10, "Nom: " . $act['child_first_name'] . " " . $act['child_last_name'], 0, 1);
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10, "Informations sur le pere", 0, 1);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(60,10, "Nom: " . ($act['father_first_name'] ?? "N/A") . " " . ($act['father_last_name'] ?? "N/A"), 0, 1);
    $pdf->Cell(60,10, "Profession: " . ($act['father_profession'] ?? "N/A"), 0, 1);
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10, "Informations sur la mere", 0, 1);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(60,10, "Nom: " . ($act['mother_first_name'] ?? "N/A") . " " . ($act['mother_last_name'] ?? "N/A"), 0, 1);
    $pdf->Cell(60,10, "Profession: " . ($act['mother_profession'] ?? "N/A"), 0, 1);
    
    $filename = $act['act_number'] . "_birth.pdf";
    
} elseif ($act_type === 'marriage') {
    $stmt = $pdo->prepare("
        SELECT 
            m.*,
            p1.first_name AS spouse1_first_name,
            p1.last_name AS spouse1_last_name,
            p2.first_name AS spouse2_first_name,
            p2.last_name AS spouse2_last_name
        FROM Marriage_Act m
        JOIN Person p1 ON m.spouse1_id = p1.id_person
        JOIN Person p2 ON m.spouse2_id = p2.id_person
        WHERE m.id_marriage_act = ?
    ");
    $stmt->execute([$act_id]);
    $act = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$act) {
        die("Acte de mariage non trouvé.");
    }
    
    $pdf->Cell(0,10, "Acte de Mariage - " . $act['act_number'], 0, 1, "C");
    $pdf->SetFont('Arial','',12);
    $pdf->Ln(5);
    $pdf->Cell(60,10, "Date of Marriage: " . $act['date_of_marriage'], 0, 1);
    $pdf->Cell(60,10, "Place of Marriage: " . $act['place_of_marriage'], 0, 1);
    $pdf->Cell(60,10, "Époux: " . $act['spouse1_first_name'] . " " . $act['spouse1_last_name'], 0, 1);
    $pdf->Cell(60,10, "Épouse: " . $act['spouse2_first_name'] . " " . $act['spouse2_last_name'], 0, 1);
    
    $filename = $act['act_number'] . "_marriage.pdf";
    
} elseif ($act_type === 'death') {
    $stmt = $pdo->prepare("
        SELECT 
            d.*,
            p.first_name AS deceased_first_name,
            p.last_name AS deceased_last_name
        FROM Death_Act d
        JOIN Person p ON d.deceased_id = p.id_person
        WHERE d.id_death_act = ?
    ");
    $stmt->execute([$act_id]);
    $act = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$act) {
        die("Acte de déces non trouvé.");
    }
    
    $pdf->Cell(0,10, "Acte de Déces - " . $act['act_number'], 0, 1, "C");
    $pdf->SetFont('Arial','',12);
    $pdf->Ln(5);
    $pdf->Cell(60,10, "Date of Death: " . $act['date_of_death'], 0, 1);
    $pdf->Cell(60,10, "Time of Death: " . $act['time_of_death'], 0, 1);
    $pdf->Cell(60,10, "Place of Death: " . $act['place_of_death'], 0, 1);
    $pdf->Cell(60,10, "Cause of Death: " . $act['cause_of_death'], 0, 1);
    $pdf->Cell(60,10, "Défunt: " . $act['deceased_first_name'] . " " . $act['deceased_last_name'], 0, 1);
    
    $filename = $act['act_number'] . "_death.pdf";
    
} else {
    die("Type d'acte invalide.");
}

$pdf->Output('D', $filename);
?>
