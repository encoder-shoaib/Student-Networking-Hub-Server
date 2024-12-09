<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, age, location, phone, university, education_duration, skills FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    echo "<script>alert('User not found. Please log in again.'); window.location.href='login.html';</script>";
    exit();
}

// Assuming you already fetched user data
$email = $user['email']; // User email fetched from database

// Generate the Gravatar URL using the MD5 hash of the user's email address
$gravatar_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=200&d=mp";


// Optional: Check if the Gravatar image exists
$headers = get_headers($gravatar_url);
if (strpos($headers[0], '200') === false) {
    // Fallback image if Gravatar doesn't exist
    $gravatar_url = "path/to/default/image.jpg"; // Fallback image
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Main Container -->
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden mt-10">
        <!-- Banner Section -->
        <div class="relative">
            <img src="./src/profile-banner.webp" alt="Banner Image" class="w-full h-48 object-cover">
            <div class="absolute -bottom-14 left-6 z-10">
                <!-- Display Gravatar profile picture -->
                <img src="<?php echo $gravatar_url; ?>" alt="Profile Picture" class="w-28 h-28 rounded-full border-4 border-white">
            </div>
        </div>

        <!-- Profile Details -->
        <div class="pt-16 px-6 pb-6">
            <h2 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($user['username']); ?> 221-15-4955</h2>
            <p class="text-gray-600 mt-2"><strong>Location:</strong> <?php echo htmlspecialchars($user['location']); ?></p>
            <p class="text-gray-600"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p class="text-gray-600"><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        </div>

        <!-- Education Section -->
        <div class="border-t px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-700">Education</h3>
            <p class="text-gray-600 mt-2"><strong>University:</strong> <?php echo htmlspecialchars($user['university']); ?></p>
            <p class="text-gray-600"><strong>Duration:</strong> <?php echo htmlspecialchars($user['education_duration']); ?></p>
        </div>

        <!-- Skills Section -->
        <div class="border-t px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-700">Skills</h3>
            <p class="text-gray-600 mt-2"><strong>Skills List:</strong> <?php echo htmlspecialchars($user['skills']); ?></p>
        </div>
    </div>
</body>
</html>