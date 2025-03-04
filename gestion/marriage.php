<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../config.php";
require_once "../functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act_number = trim($_POST['act_number'] ?? '');
    if (empty($act_number)) {
        $act_number = generateActNumber($pdo);
    }
    $date_of_marriage = $_POST['date_of_marriage'] ?? '';
    $place_of_marriage = $_POST['place_of_marriage'] ?? '';
    $spouse1_id = $_POST['spouse1_id'] ?? '';
    $spouse2_id = $_POST['spouse2_id'] ?? '';
    $commune_id = $_POST['commune_id'] ?? '';
    $arrondissement_id = $_POST['arrondissement_id'] ?? '';

    $stmtMarriage = $pdo->prepare("INSERT INTO Marriage_Act (act_number, date_of_marriage, place_of_marriage, spouse1_id, spouse2_id, arrondissement_id) VALUES (?,?,?,?,?,?)");
    $stmtMarriage->execute([
         $act_number,
         $date_of_marriage,
         $place_of_marriage,
         $spouse1_id,
         $spouse2_id,
         $arrondissement_id
    ]);
    
    $message = "Acte de mariage enregistré avec succès. Numéro d'acte généré : " . $act_number;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Enregistrer un Mariage</title>
    <style>
         .field { margin-bottom: 10px; }
         label { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Enregistrer un Mariage</h2>
    <?php if(isset($message)): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="marriage.php">
        <div class="field">
            <label>Act Number (laissez vide pour génération auto) :</label>
            <input type="text" name="act_number" value="<?php echo htmlspecialchars($act_number ?? ''); ?>">
        </div>
        <div class="field">
            <label>Date of Marriage :</label>
            <input type="date" name="date_of_marriage" required>
        </div>
        <div class="field">
            <label>Place of Marriage :</label>
            <input type="text" name="place_of_marriage" required>
        </div>
        <div class="field">
            <label>Époux :</label>
            <select name="spouse1_id" required>
                <option value="">-- Sélectionner l'époux --</option>
                <?php
                $stmtSpouse1 = $pdo->query("SELECT id_person, first_name, last_name FROM Person WHERE sex = 'M' ORDER BY first_name ASC");
                while ($row = $stmtSpouse1->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['id_person']) . '">' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label>Épouse :</label>
            <select name="spouse2_id" required>
                <option value="">-- Sélectionner l'épouse --</option>
                <?php
                $stmtSpouse2 = $pdo->query("SELECT id_person, first_name, last_name FROM Person WHERE sex = 'F' ORDER BY first_name ASC");
                while ($row = $stmtSpouse2->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['id_person']) . '">' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label>Commune :</label>
            <select name="commune_id" required>
                <option value="">-- Sélectionner une commune --</option>
                <?php
                $stmtCommune = $pdo->query("SELECT id_commune, name FROM Commune ORDER BY name ASC");
                while ($row = $stmtCommune->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['id_commune']) . '">' . htmlspecialchars($row['name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label>Arrondissement :</label>
            <select name="arrondissement_id" required>
                <option value="">-- Sélectionner un arrondissement --</option>
                <?php
                $stmtArr = $pdo->query("SELECT id_arrondissement, name FROM Arrondissement ORDER BY name ASC");
                while ($row = $stmtArr->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['id_arrondissement']) . '">' . htmlspecialchars($row['name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="field">
            <input type="submit" value="Enregistrer">
        </div>
    </form>
    <br>
    <a href="../index.php">Retour au dashboard</a>
</body>
</html>
