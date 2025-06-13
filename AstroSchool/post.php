<?php
include 'header.php';
include 'db.php';

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id > 0) {
    // Single Post View
    $stmt = $conn->prepare("SELECT title, content, image FROM Posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($title, $content, $image);
    $postFound = $stmt->fetch();
    $stmt->close();

    // Check admin
    $isAdmin = false;
    if (!empty($_SESSION['user_id'])) {
        $r = $conn->prepare("SELECT role FROM Users WHERE id = ?");
        $r->bind_param("i", $_SESSION['user_id']);
        $r->execute();
        $r->bind_result($role);
        if ($r->fetch() && $role === 'admin') {
            $isAdmin = true;
            $_SESSION['is_admin'] = true;
        }
        $r->close();
    }

    echo '<main class="container mt-4">';
    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<a href="post.php" class="btn btn-outline-light">← Back to Posts</a>';
    if ($isAdmin) {
        echo '<form method="POST" action="delete_post.php" onsubmit="return confirm(\'Are you sure you want to delete this post?\');" class="mb-0">';
        echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
        echo '<button type="submit" class="btn btn-danger">🗑️ Delete Post</button>';
        echo '</form>';
    }
    echo '</div>';

    echo '<div class="bg-secondary p-4 rounded">';
    if ($postFound) {
        echo '<h2>' . htmlspecialchars($title) . '</h2>';
        if (!empty($image)) {
            echo '<img src="' . htmlspecialchars($image) . '" alt="Post Image" class="img-fluid rounded mb-3">';
        }
        echo '<div class="mt-3">' . $content . '</div>';
    } else {
        echo '<p>Post not found.</p>';
    }
    echo '</div>';

    // Comments
    echo '<hr><h4>Comments</h4>';
    $commentQuery = $conn->prepare("
        SELECT c.id, u.username, c.content, c.created_at
        FROM Comments c
        JOIN Users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at DESC
    ");
    $commentQuery->bind_param("i", $post_id);
    $commentQuery->execute();
    $commentQuery->bind_result($comment_id, $username, $comment, $timestamp);

    while ($commentQuery->fetch()) {
        echo "<div class='border rounded p-2 mb-2 d-flex justify-content-between align-items-start'>";
        echo "<div>";
        echo "<strong>" . htmlspecialchars($username) . "</strong> ";
        echo "<em>($timestamp)</em><br>";
        echo nl2br(htmlspecialchars($comment));
        echo "</div>";

        if ($isAdmin) {
            echo "<form method='POST' action='delete_comment.php' onsubmit='return confirm(\"Delete this comment?\");' class='ms-3'>";
            echo "<input type='hidden' name='comment_id' value='$comment_id'>";
            echo "<input type='hidden' name='post_id' value='$post_id'>";
            echo "<button class='btn btn-sm btn-danger'>🗑️</button>";
            echo "</form>";
        }

        echo "</div>";
    }
    $commentQuery->close();

    // Add a comment
    if (!empty($_SESSION['user_id'])) {
        echo '<form method="POST" action="comment.php" class="mt-4">';
        echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
        echo '<div class="mb-3">';
        echo '<label for="text" class="form-label">Leave a Comment:</label>';
        echo '<textarea name="text" id="text" class="form-control" required></textarea>';
        echo '</div>';
        echo '<button type="submit" class="btn btn-light">Submit</button>';
        echo '</form>';
    } else {
        echo '<p><a href="login.php" class="text-light">Log in</a> to leave a comment.</p>';
    }

    echo '</main>';
} else {
    // Post Archive with Search & Filter
    $search = $_GET['search'] ?? '';
    $theme = $_GET['theme'] ?? '';
    $sort = $_GET['sort'] ?? 'desc';

    $searchValue = htmlspecialchars($search);
    $selectedTheme = $theme;
    $selectedSort = $sort;

    $query = "SELECT * FROM Posts WHERE 1=1";
    if (!empty($search)) {
        $safeSearch = $conn->real_escape_string($search);
        $query .= " AND (title LIKE '%$safeSearch%' OR content LIKE '%$safeSearch%')";
    }
    if (!empty($theme)) {
        $safeTheme = $conn->real_escape_string($theme);
        $query .= " AND theme LIKE '%$safeTheme%'";
    }
    $query .= " ORDER BY created_at " . ($sort === 'asc' ? 'ASC' : 'DESC');
    $result = $conn->query($query);

    echo '<main class="container mt-4">';
    echo '<h1 class="mb-4 text-center">All Posts</h1>';

    echo '
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <form method="get" class="d-flex flex-wrap justify-content-between align-items-center gap-2 w-100">

        <!-- Search Section -->
        <div class="d-flex flex-grow-1 gap-2" style="min-width: 250px;">
            <input type="text" name="search" 
                   placeholder="Search posts..." 
                   value="' . $searchValue . '" 
                   class="form-control form-control-sm" 
                   style="min-width: 300px; max-width: 350px; flex-grow: 1;">
            <button type="submit" class="btn btn-sm btn-outline-info" style="width: 100px;">Search</button>
        </div>

        <!-- Filter Section -->
        <div class="d-flex gap-2">
            <select name="theme" class="form-select form-select-sm" style="width: 120px;">
                <option value="">All Themes</option>
                <option value="Astronomy"' . ($selectedTheme === 'Astronomy' ? ' selected' : '') . '>Astronomy</option>
                <option value="Astrophotography"' . ($selectedTheme === 'Astrophotography' ? ' selected' : '') . '>Astrophotography</option>
            </select>

            <select name="sort" class="form-select form-select-sm" style="width: 140px;">
                <option value="desc"' . ($selectedSort === 'desc' ? ' selected' : '') . '>Newest</option>
                <option value="asc"' . ($selectedSort === 'asc' ? ' selected' : '') . '>Oldest</option>
            </select>

            <button type="submit" class="btn btn-sm btn-outline-info px-3">Apply Filters</button>
        </div>

    </form>
    </div>
    ';

    if ($result->num_rows > 0) {
        echo '<div class="row">';
        while ($post = $result->fetch_assoc()) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card h-100 bg-dark text-light border-info">';
            if (!empty($post['image'])) {
                echo '<img src="' . htmlspecialchars($post['image']) . '" class="card-img-top" alt="Post Image">';
            }
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($post['title']) . '</h5>';
            echo '<p class="card-text">' . substr(strip_tags($post['content']), 0, 120) . '...</p>';
            echo '<a href="post.php?id=' . $post['id'] . '" class="btn btn-info">Read More</a>';
            echo '</div></div></div>';
        }
        echo '</div>';
    } else {
        echo '<p class="text-muted">No posts found.</p>';
    }

    echo '</main>';
}
?>
