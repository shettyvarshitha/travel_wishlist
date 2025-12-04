<?php
// Travel Wishlist - PHP + JSON (No SQL)

$file = 'travel.json';

// Ensure file exists
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$data = json_decode(file_get_contents($file), true);

// Add destination
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $data[] = [
        'place' => $_POST['place'],
        'notes' => $_POST['notes'],
        'visited' => false
    ];
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;
}

// Delete destination
if (isset($_GET['delete'])) {
    array_splice($data, $_GET['delete'], 1);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;
}

// Mark visited/unvisited
if (isset($_GET['toggle'])) {
    $index = $_GET['toggle'];
    $data[$index]['visited'] = !$data[$index]['visited'];
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Travel Wishlist</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>🌍 My Travel Wishlist</h1>

    <form method="POST" class="add-form">
        <input type="hidden" name="action" value="add">
        <input type="text" name="place" placeholder="Place name (e.g., Paris, Tokyo)" required>
        <textarea name="notes" placeholder="Notes or reason to visit..." rows="2"></textarea>
        <button type="submit">Add Destination</button>
    </form>

    <div class="list">
        <?php if (empty($data)): ?>
            <p class="empty">No destinations added yet ✈️</p>
        <?php else: ?>
            <?php foreach ($data as $i => $item): ?>
                <div class="card <?= $item['visited'] ? 'visited' : '' ?>">
                    <h2><?= htmlspecialchars($item['place']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($item['notes'])) ?></p>
                    <p>Status: <strong><?= $item['visited'] ? 'Visited ✅' : 'Not yet 🌎' ?></strong></p>
                    <div class="buttons">
                        <a href="?toggle=<?= $i ?>" class="btn visit-btn"><?= $item['visited'] ? 'Mark Unvisited' : 'Mark Visited' ?></a>
                        <a href="?delete=<?= $i ?>" class="btn delete-btn">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
