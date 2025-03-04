<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../config.php";
require_once "../functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act_number    = trim($_POST['act_number'] ?? '');
    if (empty($act_number)) {
        $act_number = generateActNumber($pdo);
    }
    $date_of_death = $_POST['date_of_death'] ?? '';
    $time_of_death = $_POST['time_of_death'] ?? '';
    $place_of_death = $_POST['place_of_death'] ?? '';
    $cause_of_death = $_POST['cause_of_death'] ?? '';
    $deceased_id   = $_POST['deceased_id'] ?? '';
    $commune_id    = $_POST['commune_id'] ?? '';
    $arrondissement_id = $_POST['arrondissement_id'] ?? '';

    $stmtDeath = $pdo->prepare("INSERT INTO Death_Act (act_number, date_of_death, time_of_death, place_of_death, cause_of_death, deceased_id, arrondissement_id) VALUES (?,?,?,?,?,?,?)");
    $stmtDeath->execute([
         $act_number,
         $date_of_death,
         $time_of_death,
         $place_of_death,
         $cause_of_death,
         $deceased_id,
         $arrondissement_id
    ]);

    $message = "Acte de décès enregistré avec succès. Numéro d'acte généré : " . $act_number;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Enregistrer un Décès</title>
    <style>
        .field { margin-bottom: 10px; }
        label { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Enregistrer un Décès</h2>
    <?php if(isset($message)): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="death.php">
        <div class="field">
            <label>Act Number (laissez vide pour génération auto) :</label>
            <input type="text" name="act_number" value="<?php echo htmlspecialchars($act_number ?? ''); ?>">
        </div>
        <div class="field">
            <label>Date of Death :</label>
            <input type="date" name="date_of_death" required>
        </div>
        <div class="field">
            <label>Time of Death :</label>
            <input type="time" name="time_of_death" required>
        </div>
        <div class="field">
            <label>Place of Death :</label>
            <input type="text" name="place_of_death" required>
        </div>
        <div class="field">
            <label>Cause of Death :</label>
            <input type="text" name="cause_of_death" required>
        </div>
        <div class="field">
            <label>Défunt :</label>
            <select name="deceased_id" required>
                <option value="">-- Sélectionner le défunt --</option>
                <?php
                $stmtDeceased = $pdo->query("SELECT id_person, first_name, last_name FROM Person ORDER BY first_name ASC");
                while ($row = $stmtDeceased->fetch(PDO::FETCH_ASSOC)) {
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
