<?php
require_once "config.php";
$term = $_GET['term'] ?? '';

$results = [];
if (!empty($term)) {
    $stmt = $pdo->prepare("SELECT id_person, first_name, last_name FROM Person WHERE first_name LIKE ? OR last_name LIKE ? LIMIT 10");
    $likeTerm = "%" . $term . "%";
    $stmt->execute([$likeTerm, $likeTerm]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recherche Parent</title>
</head>
<body>
    <h2>Recherche de Parent</h2>
    <form method="get" action="search_parent.php">
        <label>Nom ou prénom :</label>
        <input type="text" name="term" value="<?php echo htmlspecialchars($term); ?>" required>
        <input type="submit" value="Rechercher">
    </form>
    <?php if (!empty($results)): ?>
        <h3>Résultats :</h3>
        <form method="post" action="select_parent.php">
            <select name="parent_id">
                <?php foreach ($results as $r): ?>
                    <option value="<?php echo htmlspecialchars($r['id_person']); ?>">
                        <?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Sélectionner ce parent">
        </form>
    <?php endif; ?>
    <br>
    <a href="birth_form.php">Retour au formulaire de naissance</a>
</body>
</html>
