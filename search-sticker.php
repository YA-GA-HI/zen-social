<?php
session_start();
//get data
include_once('src/dbconnect.php'); 
//load stickers
if(isset($_GET['user']))
{
    $stmt = $conn->prepare(" SELECT  sticker,username FROM stickers WHERE username=:username limit 50 ");
    $stmt->bindParam('username',$_GET['user']);
    $stmt->execute();
    $stickers = $stmt->fetchAll(); 
}
else
{
$stmt = $conn->prepare(" SELECT  sticker,username FROM stickers limit 50 ");;
$stmt->execute();
$stickers = $stmt->fetchAll(); 
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

    <!--search form-->
    <form actione="search-sticker.php" method="GET" class="flex send-form-width w-full  flex-row items-center h-16  bg-white px-4" >
        <div class="relative w-full">
            <input
            type="text" id="user"
            name="user"
            placeholder="Search..."
            class="flex w-full border  rounded-l-xl focus:outline-none focus:border-indigo-300 pl-4 h-10"
            />
            
        </div>
        <button
            class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600  h-10 rounded-r-xl text-white px-4 py-1 flex-shrink-0"
            >
            <span>Search</span>
        </button>
    </form>

    <div class="px-2 my-10">

    <!--looad stickers-->
    <?php 
    foreach($stickers as $index=> $sticker)
    {
        echo '
        <button onblur="hideEl('.$index.')" onfocus="showEl('.$index.')" class="w-1/4 sm:w-2/12 md:w-1/12 inline-block px-5 ">
            <img class="w-full border shadow-lg" src="'. $sticker['sticker'].'" alt="sticker">
            <div class="absolute rounded-md z-20 border-indigo-700 border hidden element left-1/2 w-11/12 md:w-2/12 p-3 pb-20 bg-white  top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <img class="w-1/3 mx-auto border border-indigo-700 rounded" src="'. $sticker['sticker'].'" alt="sticker">
                <div class="mx-auto text-center text-indigo-700 mt-5">'. $sticker['username'].'</div>
                <div onclick="window.location = \'add-sticker.php?author='.$sticker['username'].'&sticker='. $sticker['sticker'].'\'" 
                class="left-1/2 absolute top-1/2 transform -translate-x-1/2 rounded mt-7  text-center px-2 py-1 text-white bg-indigo-700 "> Favorite </div>
            </div>
        </button>
        ';
    }
    if(count($stickers)<1)
    {
        echo '
        <div class="mx-auto my-20 text-center font-bold text-3xl ">
            No result found!
        </div>
        ';
    }
    ?>
    <div id="filter" class="opacity-60 hidden bg-black w-screen h-screen fixed top-0 left-0">
        
    </div>

    </div>
    <script>
        function hideEl(index)
        {
            document.getElementById('filter').classList.add('hidden');
            document.getElementsByClassName('element')[index].classList.add('hidden');
        }
        function showEl(index)
        {
            document.getElementById('filter').classList.remove('hidden');
            document.getElementsByClassName('element')[index].classList.remove('hidden');
        }
    </script>
</body>
</html>