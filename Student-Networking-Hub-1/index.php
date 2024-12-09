<?php
session_start();
include('db.php');  // Ensure you have a db.php that connects to your database

// Assuming you already fetched user data
// Example user data fetching process
// This assumes you have already fetched user data into the $user variable
$user_id = $_SESSION['user_id'];  // Assuming user is logged in and user_id is stored in session
$query = "SELECT * FROM users WHERE id = $user_id";  // Example query to get user data
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// If user is not found, redirect to login
if (!$user) {
    header('Location: login.html');
    exit();
}

$email = $user['email'];  // User email fetched from database
// Generate the Gravatar URL using the MD5 hash of the user's email address
$gravatar_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=200&d=mp";
 
// for post code 
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen">

    <!-- Navbar -->
    <nav class="bg-gray-800 p-4 fixed w-full z-10 sticky top-0">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img class="w-10 mr-3" src="./src/graduation-cap.png" alt="Graduation Cap">
                <a href="#" class="text-white text-lg font-bold hidden lg:block">Student Networking Hub</a>
            </div>
            <div class="hidden md:flex">
                <a href="./index.php" class="text-gray-300 hover:text-white px-4">Home</a>
                <a href="#" class="text-gray-300 hover:text-white px-4">About</a>
                <a href="#" class="text-gray-300 hover:text-white px-4">Services</a>
                <a href="#" class="text-gray-300 hover:text-white px-4">Contact</a>
            </div>
            <div class="relative md:ml-4 flex gap-4">
                <input type="text" class="bg-white text-gray-900 px-4 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search">
                <div class="text-white">
                    <a href="./login.html" class="hover:underline">Log Out</a>
                </div>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Newsfeed -->
    <div class="md:container mx-auto">
        <div class="flex items-center justify-center p-4 mb-8 bg-white">
            <img src="<?php echo $gravatar_url; ?>" alt="Profile Picture" class="w-14 h-14 rounded-full border-4 border-white">
            <div class="flex flex-grow">
                <input type="text" class="bg-gray-200 text-gray-900 rounded-full px-4 py-1 me-3 flex-grow" placeholder="Share your thoughts...">
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full">Share</button>
            </div>
        </div>
        
    <!-- Header -->
    <section class="lg:px-16 px-5 lg:px-36 lg:flex justify-center pt-18 mb-28">
        <div class="flex gap-8 items-start justify-center">
            <div class="hidden lg:block">
                <div class="card w-[270px] bg-white shadow-xl">
                    <div class="relative h-40 overflow-hidden" id="profile-page">
                        <img class="object-cover w-full h-full brightness-50" src="./src/profile-banner.webp"
                            alt="Shoes">

                        <div class="absolute bottom-0 left-0 w-full flex justify-center">
                            <img src="<?php echo $gravatar_url; ?>" alt="Profile Picture" class="w-28 h-28 rounded-full border-4 border-white">
                        </div>
                    </div>
                    <div class="profile-desc text-center pb-12 pt-5">
                    <h2 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($user['username']); ?></h2>
                        <p class="text-gray-600 mt-2 p-3">Any one can join with us if you want. Connect with us on
                            social
                            media!</p>
                    </div>
                </div>

                <div class="card p-6 my-8 bg-white shadow-xl">
                    <h4 class="text-xl font-bold pb-4">Page you may like</h4>
                    <div>
                        <ul>
                            <div class="flex items-center justify-between mb-4">
                                <div class="block">
                                    <a href="#">
                                        <figure>
                                            <img src="./src/profile-1.webp" alt="profile picture" class="rounded-full w-9 h-9">
                                        </figure>
                                    </a>
                                </div>
                            
                                <div class="ml-4 block">
                                    <h3><a href="#" class="text-blue-500">Travel The World</a></h3>
                                    <p><a href="#" class="text-gray-500">adventure</a></p>
                                </div>
                            
                                <div class="block">
                                    <button class="relative w-6 h-6">
                                        <img src="./src/heart-color.webp" alt="" class="w-full h-full absolute inset-0">
                                        <img src="./src/heart.webp" alt="" class="w-full h-full absolute inset-0 opacity-0 hover:opacity-100">
                                    </button>
                                </div>
                                
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="block">
                                    <a href="#">
                                        <figure>
                                            <img src="./src/profile-35x35-4.webp" alt="profile picture" class="rounded-full w-9 h-9">
                                        </figure>
                                    </a>
                                </div>
                            
                                <div class="ml-4 block">
                                    <h3><a href="#" class="text-blue-500">Travel The World</a></h3>
                                    <p><a href="#" class="text-gray-500">adventure</a></p>
                                </div>
                            
                                <div class="block">
                                    <button class="relative w-6 h-6">
                                        <img src="./src/heart-color.webp" alt="" class="w-full h-full absolute inset-0">
                                        <img src="./src/heart.webp" alt="" class="w-full h-full absolute inset-0 opacity-0 hover:opacity-100">
                                    </button>
                                </div>
                                
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="block">
                                    <a href="#">
                                        <figure>
                                            <img src="./src/profile-35x35-7 (1).webp" alt="profile picture" class="rounded-full w-9 h-9">
                                        </figure>
                                    </a>
                                </div>
                            
                                <div class="ml-4 block">
                                    <h3><a href="#" class="text-blue-500">Travel The World</a></h3>
                                    <p><a href="#" class="text-gray-500">adventure</a></p>
                                </div>
                            
                                <div class="block">
                                    <button class="relative w-6 h-6">
                                        <img src="./src/heart-color.webp" alt="" class="w-full h-full absolute inset-0">
                                        <img src="./src/heart.webp" alt="" class="w-full h-full absolute inset-0 opacity-0 hover:opacity-100">
                                    </button>
                                </div>
                                
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="block">
                                    <a href="#">
                                        <figure>
                                            <img src="./src/profile-35x35-9.webp" alt="profile picture" class="rounded-full w-9 h-9">
                                        </figure>
                                    </a>
                                </div>
                            
                                <div class="ml-4 block">
                                    <h3><a href="#" class="text-blue-500">Travel The World</a></h3>
                                    <p><a href="#" class="text-gray-500">adventure</a></p>
                                </div>
                            
                                <div class="block">
                                    <button class="relative w-6 h-6">
                                        <img src="./src/heart-color.webp" alt="" class="w-full h-full absolute inset-0">
                                        <img src="./src/heart.webp" alt="" class="w-full h-full absolute inset-0 opacity-0 hover:opacity-100">
                                    </button>
                                </div>
                                
                            </div>

                            
                            
                        </ul>
                    </div>
                </div>
                
                
                

            </div>

            <!-- newsfeed -->
            <div>
                <div class="flex items-center justify-center p-4 mb-8 bg-white">
                    <img src="<?php echo $gravatar_url; ?>" alt="Profile Picture" class="w-14 h-14 rounded-full border-4 border-white">
                    <div class="flex flex-grow">
                        <input type="text" class="bg-gray-200 text-gray-900 rounded-full px-4 py-1 me-3  flex-grow"
                            placeholder="Share your thoughts...">
                        <button
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full" id='button-post'>Post</button>
                    </div>
                </div>


<!--*********************************************** -->




        <!-- Display Posts -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card bg-white shadow-xl flex flex-col p-6 mb-6 rounded-lg">
                <div class="flex items-center mb-4">
                <img src="<?php echo $gravatar_url; ?>" alt="Profile Picture" class="w-14 h-14 rounded-full border-4 border-white">
                    <div>
                        <h2 class="font-semibold text-lg"><?php echo $row['username']; ?></h2>
                        <p class="text-sm text-gray-500"><?php echo $row['created_at']; ?></p>
                    </div>
                </div>
                <div class="card-body flex-1 py-5">
                    <p class="text-gray-700"><?php echo $row['content']; ?></p>
                </div>
                <figure>
                    <img src="<?php echo $row['image']; ?>" alt="Post Image"
                        class="w-full h-auto rounded-xl object-cover mt-4 shadow-md">
                </figure>
                <!-- Add your dynamic likes/comments functionality -->
                <div class="flex items-center justify-between mt-6">
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 transition duration-150">
                            <img class="w-6" src="./src/heart.png" alt="Like">
                            <span>24</span>
                        </button>
                        <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 transition duration-150">
                            <img class="w-6" src="./src/comment.png" alt="Comment">
                            <span>16</span>
                        </button>
                    </div>
                    <div>
                        <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 transition duration-150">
                            <img class="w-6" src="./src/share.png" alt="Share">
                            <span>20</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
                <div class="card lg:w-[700px] bg-white shadow-xl flex flex-col p-6 my-8">
                    <div class="flex items-center">
                        <img src="./src/profile-small-1.webp" alt="Profile Picture"
                            class="w-10 h-10 rounded-full mr-4">
                        <div>
                            <h2 class="font-semibold">John Doe</h2>
                            <p class="text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="card-body flex-1 py-5">
                        <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default
                            model text, and a search for 'lorem ipsum' will uncover many web sites still in their
                            infancy.</p>
                    </div>
                    <figure>
                        <img src="./src/job.jpg" alt="Shoes"
                            class="w-full h-full rounded-xl object-cover">
                    </figure>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center space-x-4">
                            <button class="flex items-center space-x-2">
                                <img class="w-6" src="./src/heart.png" alt="">

                                <span class="text-gray-500">24</span>
                            </button>
                            <button class="flex items-center space-x-2">
                                <img class="w-6" src="./src/comment.png" alt="">
                                <span class="text-gray-500">16</span>
                            </button>
                        </div>
                        <div>
                            <button class="flex items-center space-x-2">
                                <img class="w-6" src="./src/share.png" alt="">
                                <span class="text-gray-500">20</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>


            <div class="hidden lg:block">
                <div class="card lg:w-[270px] w-80 bg-white shadow-xl flex flex-col">
                    <h1 class="text-xl p-3 ps-5 font-bold">Recent Notifications</h1>
                    <div class="px-2">
                        <div class="flex items-center p-4">
                            <img src="./src/profile-35x35-9.webp" alt="Profile Picture"
                                class="w-10 h-10 rounded-full mr-4">
                            <div>
                                <h2 class="font-semibold">Any one can join with us if you want</h2>
                                <p class="text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4">
                            <img src="./src/profile-35x35-8.webp" alt="Profile Picture"
                                class="w-10 h-10 rounded-full mr-4">
                            <div>
                                <h2 class="font-semibold">Any one can join with us if you want</h2>
                                <p class="text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4">
                            <img src="./src/profile-35x35-7.webp" alt="Profile Picture"
                                class="w-10 h-10 rounded-full mr-4">
                            <div>
                                <h2 class="font-semibold">Any one can join with us if you want</h2>
                                <p class="text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4">
                            <img src="./src/profile-35x35-6.webp" alt="Profile Picture"
                                class="w-10 h-10 rounded-full mr-4">
                            <div>
                                <h2 class="font-semibold">Any one can join with us if you want</h2>
                                <p class="text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4">
                            <img src="./src/profile-35x35-4.webp" alt="Profile Picture"
                                class="w-10 h-10 rounded-full mr-4">
                            <div>
                                <h2 class="font-semibold">Any one can join with us if you want</h2>
                                <p class="text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white lg:w-[270px]  shadow-lg p-6 my-8">
                    <h4 class="text-lg font-bold mb-4">Advertizement</h4> 
                    <div class="mt-4">
                        <a href="#" class="block">
                            <img src="./src/add-2.jpg" alt="advertisement" class="w-full h-auto rounded">
                        </a>
                    </div>
                </div>
                

            </div>
        </div>

    </div>
    </section>
        <!-- Additional Posts (Repeat the above structure for more posts) -->

<section id='post-section' class="fixed top-0 left-0 w-full bg-gray-100 text-gray-900 font-sans leading-relaxed hidden z-50">
    <div class="container mx-auto p-6">
        <!-- Post Form -->
        <div class="bg-white shadow-lg rounded-lg p-8 mb-8">
            <form action="post.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <input type="text" name="username" placeholder="Your Name" required
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <textarea name="content" placeholder="What's on your mind?" required
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <input type="file" name="post_image" accept="image/*" required
                        class="w-full text-gray-500 file:border-gray-300 file:bg-gray-100 file:p-3 file:rounded-lg">
                </div>
                <button type="submit"
                    class="w-full py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-600 transition duration-200">Post</button>
            </form>
        </div>
    </div>
</section>

<script src="js/home.js"></script>
</body>
</html>



