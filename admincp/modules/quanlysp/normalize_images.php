<?php
/**
 * Normalize image filenames in upload folder and update DB `sanpham.hinhanh` accordingly.
 * Usage (dry-run): open this file in browser.
 * To perform changes: add ?run=1 to URL.
 * IMPORTANT: Backup files + DB before running with run=1.
 */
include('../../config/config.php');

function slugify($text){
    $text = trim($text);
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    $text = preg_replace('~[^A-Za-z0-9._-]+~', '_', $text);
    $text = preg_replace('~_+~','_', $text);
    $text = trim($text, '_');
    if ($text === '') return 'file';
    return $text;
}

$uploadDir = __DIR__ . '/upload/';
$webPrefix = 'modules/quanlysp/upload/';

$run = isset($_GET['run']) && $_GET['run']=='1';

if (!is_dir($uploadDir)) {
    echo "Upload dir not found: $uploadDir"; exit;
}

$files = array_values(array_diff(scandir($uploadDir), array('.', '..')));

echo '<h2>Normalize images — ' . ($run? 'EXECUTE' : 'DRY RUN') . '</h2>';
echo '<p>Backup your DB and files before running with <strong>?run=1</strong>.</p>';

$changes = [];
foreach ($files as $file) {
    $orig = $file;
    $base = pathinfo($file, PATHINFO_FILENAME);
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $slug = slugify($base);
    $new = $slug . ($ext ? '.' . strtolower($ext) : '');

    // avoid identical
    if ($new === $orig) {
        // still attempt to ensure DB references are consistent
        $changes[] = [ 'old'=>$orig, 'new'=>$new, 'moved'=>false, 'db_rows'=>0 ];
        continue;
    }

    // if target exists, add suffix
    $counter = 1;
    $candidate = $new;
    while (is_file($uploadDir . $candidate) && $candidate !== $orig) {
        $candidate = $slug . '-' . $counter . ($ext ? '.' . strtolower($ext) : '');
        $counter++;
    }
    $new = $candidate;

    // find DB rows referencing this filename
    $safeOrig = mysqli_real_escape_string($mysqli, $orig);
    $res = mysqli_query($mysqli, "SELECT id_sanpham, hinhanh FROM sanpham WHERE hinhanh = '$safeOrig'");
    $db_updates = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $db_updates[] = $r['id_sanpham'];
    }

    $moved = false;
    if ($run) {
        // perform rename on filesystem
        $ok = @rename($uploadDir . $orig, $uploadDir . $new);
        $moved = (bool)$ok;
        // update DB rows
        if ($moved && !empty($db_updates)) {
            $newEsc = mysqli_real_escape_string($mysqli, $new);
            $origEsc = mysqli_real_escape_string($mysqli, $orig);
            mysqli_query($mysqli, "UPDATE sanpham SET hinhanh = '$newEsc' WHERE hinhanh = '$origEsc'");
        }
    }

    $changes[] = [ 'old'=>$orig, 'new'=>$new, 'moved'=>$moved, 'db_rows'=>count($db_updates), 'ids'=>$db_updates ];
}

// render results
echo '<table border="1" cellpadding="6" cellspacing="0">';
echo '<tr><th>Old filename</th><th>New filename</th><th>Migrated file</th><th>DB rows affected</th><th>IDs</th></tr>';
foreach ($changes as $c) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($c['old']) . '</td>';
    echo '<td>' . htmlspecialchars($c['new']) . '</td>';
    echo '<td>' . ($c['moved'] ? 'yes' : 'no') . '</td>';
    echo '<td style="text-align:center">' . intval($c['db_rows']) . '</td>';
    echo '<td>' . (!empty($c['ids']) ? implode(',', $c['ids']) : '') . '</td>';
    echo '</tr>';
}
echo '</table>';

echo '<p>Total files scanned: ' . count($files) . '</p>';
if (!$run) {
    echo '<p>Dry-run complete. To apply changes, re-open this URL with <code>?run=1</code>.</p>';
} else {
    echo '<p>Execution completed. Verify files and DB. If you have issues, restore backups.</p>';
}

?>
