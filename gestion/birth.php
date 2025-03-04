<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
require_once "../config.php";
require_once "../functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act_number       = trim($_POST['act_number'] ?? '');
    if (empty($act_number)) {
        $act_number = generateActNumber($pdo);
    }
    $date_declaration = $_POST['date_declaration'] ?? '';
    $date_of_birth    = $_POST['date_of_birth'] ?? '';
    $time_of_birth    = $_POST['time_of_birth'] ?? '';
    $place_of_birth   = $_POST['place_of_birth'] ?? '';
    $sex              = $_POST['sex'] ?? '';
    
    $child_first_name = $_POST['child_first_name'] ?? '';
    $child_last_name  = $_POST['child_last_name'] ?? '';
    
    $father_id        = $_POST['father_id'] ?? '';
    $mother_id        = $_POST['mother_id'] ?? '';
    
    $commune_id         = $_POST['commune_id'] ?? '';
    $arrondissement_id  = $_POST['arrondissement_id'] ?? '';
    
    $child_nationality = "Bénin";
    $child_address     = "";
    if (!empty($father_id)) {
        $stmtFatherAddr = $pdo->prepare("SELECT address FROM Person WHERE id_person = ?");
        $stmtFatherAddr->execute([$father_id]);
        $fatherData = $stmtFatherAddr->fetch(PDO::FETCH_ASSOC);
        if ($fatherData) {
            $child_address = $fatherData['address'];
        }
    }

    $stmtPerson = $pdo->prepare("INSERT INTO Person (first_name, last_name, date_of_birth, place_of_birth, sex, nationality, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtPerson->execute([
        $child_first_name,
        $child_last_name,
        $date_of_birth,
        $place_of_birth,
        $sex,
        $child_nationality,
        $child_address
    ]);
    $child_id = $pdo->lastInsertId();

    $stmtBirth = $pdo->prepare("INSERT INTO Birth_Act (act_number, date_declaration, date_of_birth, time_of_birth, place_of_birth, sex, child_id, father_id, mother_id, arrondissement_id) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmtBirth->execute([
        $act_number,
        $date_declaration,
        $date_of_birth,
        $time_of_birth,
        $place_of_birth,
        $sex,
        $child_id,
        $father_id,
        $mother_id,
        $arrondissement_id
    ]);
    
    $message = "Acte de naissance enregistré avec succès. Numéro d'acte généré : " . $act_number;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Enregistrer une Naissance</title>
    <style>
        .field { margin-bottom: 10px; }
        label { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Enregistrer une Naissance</h2>
    <?php if (isset($message)) : ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="birth.php">
        <div class="field">
            <label>Act Number (laissez vide pour génération auto) :</label>
            <input type="text" name="act_number" value="<?php echo htmlspecialchars($act_number ?? ''); ?>">
        </div>
        <div class="field">
            <label>Date Declaration :</label>
            <input type="date" name="date_declaration" required value="<?php echo htmlspecialchars($date_declaration ?? ''); ?>">
        </div>
        <div class="field">
            <label>Date of Birth :</label>
            <input type="date" name="date_of_birth" required value="<?php echo htmlspecialchars($date_of_birth ?? ''); ?>">
        </div>
        <div class="field">
            <label>Time of Birth :</label>
            <input type="time" name="time_of_birth" required value="<?php echo htmlspecialchars($time_of_birth ?? ''); ?>">
        </div>
        <div class="field">
            <label>Place of Birth :</label>
            <input type="text" name="place_of_birth" required value="<?php echo htmlspecialchars($place_of_birth ?? ''); ?>">
        </div>
        <div class="field">
            <label>Sex :</label>
            <select name="sex" required>
                <option value="M" <?php if(isset($sex) && $sex=='M') echo 'selected'; ?>>M</option>
                <option value="F" <?php if(isset($sex) && $sex=='F') echo 'selected'; ?>>F</option>
            </select>
        </div>
        <div class="field">
            <label>Prénom de l'enfant :</label>
            <input type="text" name="child_first_name" required value="<?php echo htmlspecialchars($child_first_name ?? ''); ?>">
        </div>
        <div class="field">
            <label>Nom de l'enfant :</label>
            <input type="text" name="child_last_name" required value="<?php echo htmlspecialchars($child_last_name ?? ''); ?>">
        </div>
        <div class="field">
            <label>Père :</label>
            <select name="father_id" required>
                <option value="">-- Sélectionner le père --</option>
                <?php
                $stmtFather = $pdo->query("SELECT id_person, first_name, last_name FROM Person WHERE sex = 'M' ORDER BY first_name ASC");
                while ($row = $stmtFather->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['id_person']) . '">' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label>Mère :</label>
            <select name="mother_id" required>
                <option value="">-- Sélectionner la mère --</option>
                <?php
                $stmtMother = $pdo->query("SELECT id_person, first_name, last_name FROM Person WHERE sex = 'F' ORDER BY first_name ASC");
                while ($row = $stmtMother->fetch(PDO::FETCH_ASSOC)) {
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
