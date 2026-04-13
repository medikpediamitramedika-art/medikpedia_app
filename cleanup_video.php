<?php

// Delete the failed video record so user can upload fresh
$db = new PDO('sqlite:database/database.sqlite');

try {
    // Delete the failed video (ID 7 with no file)
    $stmt = $db->prepare("DELETE FROM news WHERE id = 7 AND tipe = 'video' AND file = 'news/1775651202_69d6498220475.mp4'");
    $stmt->execute();
    
    echo "✅ Deleted failed video record (ID 7)\n";
    echo "   You can now upload a new video\n\n";
    
    // Show remaining videos
    $result = $db->query("SELECT id, judul, tipe, file FROM news ORDER BY id DESC");
    $videos = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Current news records:\n";
    foreach ($videos as $v) {
        $hasFile = ($v['file'] ? '✅' : '❌');
        echo "$hasFile ID {$v['id']}: {$v['judul']} ({$v['tipe']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
