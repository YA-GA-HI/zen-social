
<?php
session_start();
//get data
include_once('src/dbconnect.php'); 
//SELECT COUNT(ProductID) AS NumberOfProducts FROM Products WHERE ProductID = 0;
$error = false;

if(!isset($_GET['user']))
{

    if(isset($_SESSION['user']))
    {
        $user = $_SESSION['user'];
        //get links
        $stmt = $conn->prepare("SELECT twitter,facebook,instagram,tiktok FROM links WHERE username = :username limit 1");
        $stmt->bindParam('username', $user['username']);
        //execute
        $stmt->execute();
        $fetch = $stmt->fetchAll();
        if(count($fetch)>0)
        {
            foreach($fetch[0] as $index=> $link)
            {
                $user[$index] = $link;
            }
            
        }
//,  
        //get followers data
        $stmt = $conn->prepare("SELECT  COUNT(following) AS following FROM followers WHERE  following = :username");
        $stmt->bindParam('username', $username);
        //execute
        $stmt->execute();
        $fetch = $stmt->fetchAll();
        if(count($fetch)>0)
        {
            $user["following"] = $fetch[0]['following'];
            
        }else{
            $user["following"] = 0;
        }

        $stmt = $conn->prepare("SELECT  COUNT(follower) AS follower FROM followers WHERE  follower = :username");
        $stmt->bindParam('username', $username);
        //execute
        $stmt->execute();
        $fetch = $stmt->fetchAll();
        if(count($fetch)>0)
        {
            $user["followers"] = $fetch[0]['follower'];
            
        }else{
            $user["followers"] = 0;
        }
    }
    else{
        header('Location: login.php');
        die();
    }
}
else{
    $username = $_GET['user'];
    // prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username limit 1");
    $stmt->bindParam('username', $username);
    //execute
    $stmt->execute();
    $fetch = $stmt->fetchAll();
    if(count($fetch)>0)
    {
        $user = $fetch[0];
    }
    else{
        $error = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zen-doctor </title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@554&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/zen-doctor.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/animate.css">
</head>
<body class="bg-indigo-50 pb-16 md:pb-0 md:pl-16">
    <!--sidebar-->
    <?php
    include_once('components/sidebar.php');
    ?>
        
    <!-- component -->
    <div class="w-full h-screen flex justify-center items-center my-40">
    <div class="container my-20 mx-auto max-w-sm rounded-lg overflow-hidden shadow-lg  bg-white">
        <div class="relative z-10" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0 calc(100% - 5vw));">
        <?php
            if($user['cover'] !== null)
            {
                echo '<img id="img" class="w-full" src="'.$user['cover'].'" alt="'.$user['username'].' Profile cover" />';
            }
            else{
                echo '<div class="w-full h-60 md:h-80  bg-black"> </div>';
            }
        ?>
        
        <div class="text-center absolute w-full" style="bottom: 8rem">
            <p class="text-white tracking-wide uppercase text-lg font-bold">
                <?php echo  $user['first_name'] . ' ' . $user['second_name']  ; ?></p>
            <p class="text-gray-400 text-sm">@<?php echo  $user['username']; ?></p>
        </div>
        </div>
        <div class="relative flex justify-center items-center flex-row px-6 z-50 -mt-10">
            <div class="flex justify-center">
            <?php
            if($user['image'] !== null)
            {
                echo '<div class="rounded-full mx-auto absolute overflow-hidden -top-20 w-32 h-32 shadow-2xl border-4 border-white">
                <img id="img" class="w-full" src="'.$user['image'].'" alt="'.$user['username'].' Profile image" />
                </div>';
            }
            else{
                echo '<div class="rounded-full mx-auto absolute -top-20 w-32 h-32 shadow-2xl border-4 border-white flex items-center justify-center text-2xl md:text-4xl bg-indigo-500 flex-shrink-0">
                            '.substr($user['username'],0,1) .'
                            </div>';
            }
        ?>
            </div>
        </div>
        <div class="pt-6 mt-10 pb-8 text-gray-600 text-center">
        <p class="font-bold"><?php echo $user['description']; ?></p>
        <p class="text-sm"><?php echo $user['bio']; ?></p>
        </div>

        <div class="pb-7 uppercase text-center tracking-wide flex justify-around">

        <div class="followers">
            <p class="text-gray-400 text-sm">Followers</p>
            <p class="text-lg font-semibold text-blue-300"><?php echo $user['followers']; ?></p>
        </div>
        <div class="following">
            <p class="text-gray-400 text-sm">Following</p>
            <p class="text-lg font-semibold text-blue-300"><?php echo $user['following']; ?></p>
        </div>
        </div>
        <div class="w-full h-12 relative">
        <button class="rounded-md left-1/2  h-full transform -translate-x-1/2 absolute bg-indigo-700 px-4 text-white  ">follow</button>
        </div>
        <div class="flex justify-center my-5">
            
        <?php
        if(isset($user['facebook']))
        {
            echo '<a href="'.$user['facebook'].'" class="bg font-bold text-sm text-blue-800 w-full text-center py-3 hover:bg-blue-800 hover:text-white hover:shadow-lg">Facebook</a>';
        }
            
        ?>
        <?php
        if(isset($user['twitter']))
        {    
        echo '<a href="'.$user['twitter'].'" class="bg font-bold text-sm text-blue-400 w-full text-center py-3 hover:bg-blue-400 hover:text-white hover:shadow-lg">Twitter</a>';
        }
        ?>
        <?php
        if(isset($user['instagram']))
        {
            echo '<a href="'.$user['instagram'].'" class="bg font-bold text-sm text-yellow-500 w-full text-center py-3 hover:bg-yellow-500 hover:text-white hover:shadow-lg">Instagram</a>';
        }
        ?>
        <?php
        if(isset($user['tiktok']))
        {
            echo '<a href="'.$user['tiktok'].'" class="bg font-bold text-sm text-pink-700 w-full text-center py-3  hover:bg-pink-700  hover:text-white hover:shadow-lg">TikTok</a>';
        }
        ?>
            </div>

        <div class="w-full">

            <div class="mt-5 w-full">
                <a href="#" class="border-t-2 border-b-2 border-gray-100 font-medium text-gray-600 py-4 px-4 w-full block hover:bg-gray-100 transition duration-150">
                    <img src="https://pantazisoft.com/img/avatar-2.jpeg" alt="" class="rounded-full h-6 shadow-md inline-block mr-2">
                    <?php echo  $user['first_name'] . ' ' . $user['second_name']  ; ?>
                        <span class="text-gray-400 text-sm">@<?php echo  $user['username']  ; ?></span>
                </a>
        <h3 class="font-bold text-gray-600 text-left px-4 py-1">description</h3>
                <a href="#" class="border-t-2 pl-8 border-b-2 border-gray-100 font-medium text-gray-600 py-4 pr-4 w-full block hover:bg-gray-100 transition duration-150">

                <?php echo  $user['description']  ; ?>
                </a>

                <h3 class="font-bold text-gray-600 text-left px-4 py-1">Other</h3>

                        <a href="#" class="border-t-2 border-gray-100 font-medium text-red-400 py-4 px-4 w-full block hover:bg-gray-100 transition duration-150">

                                Signaler

                        </a>

            </div>
        </div>
    </div>

    </div>
</body>
</html>