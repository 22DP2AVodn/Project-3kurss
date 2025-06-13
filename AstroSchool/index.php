<?php
session_start();
$lang = $_SESSION['lang'] ?? 'en';
include "lang/$lang.php";
?>

<?php
include 'db.php';
include 'header.php';

// Fetch RSS News
function getRSSItems($rssUrl, $limit = 10) {
    $items = [];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $rssUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'AstroSchool RSS Reader');
    $data = curl_exec($ch);
    curl_close($ch);

    if ($data === false || empty($data)) return [];

    $feed = @simplexml_load_string($data);
    if ($feed === false || !isset($feed->channel->item)) return [];

    $count = 0;
    foreach ($feed->channel->item as $item) {
        if ($count++ >= $limit) break;
        $image = '';

        if (isset($item->enclosure['url'])) {
            $image = (string)$item->enclosure['url'];
        } elseif (isset($item->children('media', true)->thumbnail)) {
            $image = (string)$item->children('media', true)->thumbnail->attributes()->url;
        }

        $items[] = [
            'title' => (string)$item->title,
            'link'  => (string)$item->link,
            'desc'  => strip_tags((string)$item->description),
            'image' => $image
        ];
    }

    return $items;
}

$news = getRSSItems('https://rss.nytimes.com/services/xml/rss/nyt/Science.xml');
?>

<main class="container mt-4">
    <h1 class="text-center mb-5">Latest Posts</h1>
    <div class="row">
        <?php
        $result = $conn->query("SELECT id, title, content FROM Posts ORDER BY created_at DESC LIMIT 3");
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card h-100 bg-dark text-light border-info">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
            echo '<p class="card-text">' . substr(strip_tags($row['content']), 0, 120) . '...</p>';
            echo '<a href="post.php?id=' . $row['id'] . '" class="btn btn-info">Read More</a>';
            echo '</div></div></div>';
        }
        ?>
    </div>

    <?php if (!empty($news)): ?>
        <h2 class="text-center mt-5">Latest Astronomy News</h2>
        <div class="news-scroll-wrapper">
            <div class="news-scroll" id="newsScroll">
                <?php foreach ($news as $item): ?>
                    <a href="<?= htmlspecialchars($item['link']) ?>" target="_blank" class="text-decoration-none">
                        <div class="card h-100 bg-dark text-light border-info">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="News Image">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($item['desc'], 0, 100)) ?>...</p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>

<script>
  document.querySelectorAll('.lang-option').forEach(option => {
    option.addEventListener('click', function(e) {
      e.preventDefault();
      const selectedLang = this.dataset.lang;

      // Optional: Show selected language in button
      document.getElementById('languageDropdown').innerHTML = '🌐 ' + selectedLang.toUpperCase();

      // Send to backend or set cookie
      fetch(`set_language.php?lang=${selectedLang}`).then(() => {
        location.reload(); // Refresh to apply language
      });
    });
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const scrollContainer = document.getElementById('newsScroll');
  let scrollSpeed = 0.5;
  let paused = false;

  // Auto-scroll function
  function autoScroll() {
    if (!paused) {
      scrollContainer.scrollLeft += scrollSpeed;
      if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth) {
        scrollContainer.scrollLeft = 0;
      }
    }
    requestAnimationFrame(autoScroll);
  }

  // Allow scroll with mouse wheel
  scrollContainer.addEventListener('wheel', (e) => {
    e.preventDefault();
    scrollContainer.scrollLeft += e.deltaY;
  });

  scrollContainer.addEventListener('mouseenter', () => paused = true);
  scrollContainer.addEventListener('mouseleave', () => paused = false);

  requestAnimationFrame(autoScroll);
});
</script>

