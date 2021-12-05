<?php
session_start();
//get data
include_once('src/auth.php'); 
include_once("src/dbconnect.php");
//if there a post request 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) 
{     
    unset($_POST['submit']);
    //sanitaze all inputs 
    include_once('src/sanitaze.php');
    //profile img

    if (!file_exists('stickers/'.$_SESSION['user']['username'])) {
        mkdir('stickers/'.$_SESSION['user']['username'], 0777, true);
    }

    if(isset($_FILES["sticker"]))
    {   function str_rand($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        
            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
        
            return $randomString;
        }
        
        $target_dir = 'stickers/'.$_SESSION['user']['username'].'/';
        $name= str_rand(12);
        $target_file = $target_dir . basename($_FILES["sticker"]["name"]);
        $stickerFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $sticker = $target_dir . $name . "." . $stickerFileType;


        if($stickerFileType !== "jpg" && $stickerFileType !== "png"  && $stickerFileType !== "jpeg")
        {   

            $errors['sticker'] = "The sticker Format is Not Compatible!";
            $_SESSION['errors'] = $errors;
        }
        else
        {
            if(move_uploaded_file($_FILES["sticker"]["tmp_name"], $sticker))
            {
            
            // prepare and bind
            $stmt = $conn->prepare(" INSERT INTO stickers(username,sticker) VALUES(:username,:sticker)");
            $stmt->bindParam('sticker',$sticker);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['msg'] = "Sticker uploaded successfully";
            $_SESSION['color'] = "green";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
            }
        }
        
    }
}
else{
    //get stickers
    // prepare and bind
    $stmt = $conn->prepare(" SELECT  sticker FROM stickers WHERE username =:username limit 30 ");
    $stmt->bindParam('username',$_SESSION['user']['username']);
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

    <div class="text-4xl pl-4 font-bold">Stickers</div>

    <!--sticker-->
    <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="stickers.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="inline-block">Added a Sticker:</span>

                    <label for="sticker" class="inline-block ml-4 text-white hover:bg-indigo-700 bg-indigo-600 cursor-pointer text-center my-2 w-28 rounded border p-2">Uplaod</label>
                    <input type="file" 
                    id="sticker" name="sticker" 
                    class="opacity-0 absolute " required
                    >

                    <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['sticker'])?print($_SESSION['errors']['sticker']) : null;
                        ?>
                    </div>
                </div>
            </form>

    <!--looad stickers-->
    <?php 
    foreach($stickers as $sticker)
    {
        echo '
        <div class="w-1/4 sm:w-2/12 md:w-1/12 inline-block px-5 ">
            <img class="w-full border shadow-lg" src="'. $sticker['sticker'].'" alt="sticker">
        </div>
        ';
    }
    ?>
<?php
include_once('components/error.php');
?>
</body>
</html>