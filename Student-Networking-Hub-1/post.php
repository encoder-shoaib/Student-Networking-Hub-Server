<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_networking_hub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle new post submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["post_image"])) {
    $username = $_POST['username'];
    $content = $_POST['content'];
    
    // Upload the image
    $image_name = $_FILES["post_image"]["name"];
    $image_tmp_name = $_FILES["post_image"]["tmp_name"];
    $image_folder = "uploads/" . basename($image_name);

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        // Insert post into database
        $stmt = $conn->prepare("INSERT INTO posts (username, profile_picture, content, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $profile_picture, $content, $image_folder);
        $profile_picture = "path_to_default_profile_picture";
        $stmt->execute();
        $stmt->close();
                // Redirect to the index page after successful post submission
                header("Location: index.php");
                exit();

    }
}

// Fetch posts
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<?php $conn->close(); ?>
