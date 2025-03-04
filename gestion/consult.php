<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config.php";

$type = $_GET['type'] ?? 'birth';
$acts = [];

switch ($type) {
    case 'birth':
        $stmt = $pdo->prepare("
            SELECT 
                b.id_birth_act,
                b.act_number,
                b.date_declaration,
                b.date_of_birth,
                b.time_of_birth,
                p.first_name AS child_first_name,
                p.last_name AS child_last_name,
                a.name AS arrondissement_name
            FROM Birth_Act b
            JOIN Person p ON b.child_id = p.id_person
            JOIN Arrondissement a ON b.arrondissement_id = a.id_arrondissement
            ORDER BY b.date_declaration DESC
        ");
        $stmt->execute();
        $acts = $stmt->fetchAll();
        break;
    case 'marriage':
        $stmt = $pdo->prepare("
            SELECT 
                m.id_marriage_act,
                m.act_number,
                m.date_of_marriage,
                p1.first_name AS spouse1_first_name,
                p1.last_name AS spouse1_last_name,
                p2.first_name AS spouse2_first_name,
                p2.last_name AS spouse2_last_name,
                a.name AS arrondissement_name
            FROM Marriage_Act m
            JOIN Person p1 ON m.spouse1_id = p1.id_person
            JOIN Person p2 ON m.spouse2_id = p2.id_person
            JOIN Arrondissement a ON m.arrondissement_id = a.id_arrondissement
            ORDER BY m.date_of_marriage DESC
        ");
        $stmt->execute();
        $acts = $stmt->fetchAll();
        break;
    case 'death':
        $stmt = $pdo->prepare("
            SELECT 
                d.id_death_act,
                d.act_number,
                d.date_of_death,
                d.time_of_death,
                p.first_name AS deceased_first_name,
                p.last_name AS deceased_last_name,
                a.name AS arrondissement_name
            FROM Death_Act d
            JOIN Person p ON d.deceased_id = p.id_person
            JOIN Arrondissement a ON d.arrondissement_id = a.id_arrondissement
            ORDER BY d.date_of_death DESC
        ");
        $stmt->execute();
        $acts = $stmt->fetchAll();
        break;
    default:
        header("Location: ?type=birth");
        exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Consulter les actes</title>
    <style>
        table { border-collapse: collapse; width: 90%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <h2>Consulter les actes</h2>
    <nav>
        <ul>
            <li><a href="?type=birth">Naissances</a></li>
            <li><a href="?type=marriage">Mariages</a></li>
            <li><a href="?type=death">Décès</a></li>
        </ul>
    </nav>
    <hr>
    <h3>Liste des actes de type : <?php echo htmlspecialchars(ucfirst($type)); ?></h3>
    <table>
        <thead>
            <tr>
                <?php if ($type == 'birth'): ?>
                    <th>ID</th>
                    <th>Numéro d'acte</th>
                    <th>Date Déclaration</th>
                    <th>Date de Naissance</th>
                    <th>Heure de Naissance</th>
                    <th>Nom de l'enfant</th>
                    <th>Arrondissement</th>
                <?php elseif ($type == 'marriage'): ?>
                    <th>ID</th>
                    <th>Numéro d'acte</th>
                    <th>Date de Mariage</th>
                    <th>Nom de l'époux</th>
                    <th>Nom de l'épouse</th>
                    <th>Arrondissement</th>
                <?php elseif ($type == 'death'): ?>
                    <th>ID</th>
                    <th>Numéro d'acte</th>
                    <th>Date de Décès</th>
                    <th>Heure de Décès</th>
                    <th>Nom du défunt</th>
                    <th>Arrondissement</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (count($acts) > 0): ?>
                <?php foreach ($acts as $act): ?>
                    <?php if ($type == 'birth'): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($act['id_birth_act']); ?></td>
                            <td>
                                <!-- Lien pour télécharger l'acte de naissance -->
                                <a href="../download_act.php?act_type=birth&act_id=<?php echo urlencode($act['id_birth_act']); ?>">
                                    <?php echo htmlspecialchars($act['act_number']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($act['date_declaration']); ?></td>
                            <td><?php echo htmlspecialchars($act['date_of_birth']); ?></td>
                            <td><?php echo htmlspecialchars($act['time_of_birth']); ?></td>
                            <td><?php echo htmlspecialchars($act['child_first_name'] . " " . $act['child_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($act['arrondissement_name']); ?></td>
                        </tr>
                    <?php elseif ($type == 'marriage'): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($act['id_marriage_act']); ?></td>
                            <td>
                                <!-- Lien pour télécharger l'acte de mariage -->
                                <a href="../download_act.php?act_type=marriage&act_id=<?php echo urlencode($act['id_marriage_act']); ?>">
                                    <?php echo htmlspecialchars($act['act_number']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($act['date_of_marriage']); ?></td>
                            <td><?php echo htmlspecialchars($act['spouse1_first_name'] . " " . $act['spouse1_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($act['spouse2_first_name'] . " " . $act['spouse2_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($act['arrondissement_name']); ?></td>
                        </tr>
                    <?php elseif ($type == 'death'): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($act['id_death_act']); ?></td>
                            <td>
                                <!-- Lien pour télécharger l'acte de décès -->
                                <a href="../download_act.php?act_type=death&act_id=<?php echo urlencode($act['id_death_act']); ?>">
                                    <?php echo htmlspecialchars($act['act_number']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($act['date_of_death']); ?></td>
                            <td><?php echo htmlspecialchars($act['time_of_death']); ?></td>
                            <td><?php echo htmlspecialchars($act['deceased_first_name'] . " " . $act['deceased_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($act['arrondissement_name']); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Aucun acte trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <a href="../index.php">Retour au dashboard</a>
</body>
</html>
