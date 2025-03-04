<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Mairie de Cotonou</title>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($user['first_name']); ?> !</h1>
    <nav>
        <ul>
            <li><a href="gestion/birth.php">Enregistrer une naissance</a></li>
            <li><a href="gestion/marriage.php">Enregistrer un mariage</a></li>
            <li><a href="gestion/death.php">Enregistrer un décès</a></li>
            <li><a href="gestion/consult.php">Consulter les actes</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</body>
</html>
