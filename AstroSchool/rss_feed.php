<?php
$rss = simplexml_load_file("https://www.nasa.gov/rss/dyn/breaking_news.rss");
if ($rss === false) {
    echo "<p>Failed to load news feed.</p>";
} else {
    echo "<div style='max-width: 800px; color: #ddd;'>";
    $count = 0;
    foreach ($rss->channel->item as $item) {
        echo "<article style='margin-bottom: 20px;'>";
        echo "<h4><a style='color: #aaccff;' href='" . htmlspecialchars($item->link) . "' target='_blank'>" . htmlspecialchars($item->title) . "</a></h4>";
        echo "<p>" . html_entity_decode(strip_tags($item->description)) . "</p>";
        echo "</article>";
        echo "<hr>";
        if (++$count >= 5) break;
    }
    echo "</div>";
}
?>
