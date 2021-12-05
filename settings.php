<?php
session_start();
include_once('src/auth.php');

//if there a post request 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) 
{     
    unset($_POST['submit']);
    //sanitaze all inputs 
    include_once('src/sanitaze.php');
    include_once("src/dbconnect.php");
    //profile img
    if(isset($_FILES["image"]))
    {   
        $target_dir = "profile/";
        $name=$_SESSION['user']['username'];
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $image = $target_dir . $name . "." . $imageFileType;


        if($imageFileType !== "jpg" && $imageFileType !== "png"  && $imageFileType !== "jpeg")
        {   

            $errors['image'] = "The image Format is Not Compatible!";
            $_SESSION['errors'] = $errors;
        }
        else
        {
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $image))
            {
            
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE users SET image = :image WHERE username = :username");
            $stmt->bindParam('image',$image);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['user']['image'] = $image;
            $_SESSION['msg'] = "Image uploaded successfully";
            $_SESSION['color'] = "green";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
            }
        }
        
    }

    //profile cover
    if(isset($_FILES["cover"]))
    {   
        $target_dir = "covers/";
        $name=$_SESSION['user']['username'];
        $target_file = $target_dir . basename($_FILES["cover"]["name"]);
        $coverFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $cover = $target_dir . $name . "." . $coverFileType;


        if($coverFileType !== "jpg" && $coverFileType !== "png"  && $coverFileType !== "jpeg")
        {   

            $errors['cover'] = "The Cover Image Format is Not Compatible!";
            $_SESSION['errors'] = $errors;
        }
        else
        {
            if(move_uploaded_file($_FILES["cover"]["tmp_name"], $cover))
            {
            
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE users SET cover = :cover WHERE username = :username");
            $stmt->bindParam('cover',$cover);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['user']['cover'] = $cover;
            $_SESSION['msg'] = "Cover Image Uploaded Successfully";
            $_SESSION['color'] = "green";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
            }
        }
        
    }

    //bg
    if(isset($_FILES["bg"]))
    {   
        $target_dir = "bg/";
        $name=$_SESSION['user']['username'];
        $target_file = $target_dir . basename($_FILES["bg"]["name"]);
        $bgFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $bg = $target_dir . $name . "." . $bgFileType;


        if($bgFileType !== "jpg" && $bgFileType !== "png"  && $bgFileType !== "jpeg")
        {   

            $errors['bg'] = "The bg Image Format is Not Compatible!";
            $_SESSION['errors'] = $errors;
        }
        else
        {
            if(move_uploaded_file($_FILES["bg"]["tmp_name"], $bg))
            {
            
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE users SET bakground = :bg WHERE username = :username");
            $stmt->bindParam('bg',$bg);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['user']['bakground'] = $bg;
            $_SESSION['msg'] = "Background Image Uploaded Successfully";
            $_SESSION['color'] = "green";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
            }
        }
        
    }

    //username
    if(isset($_POST["username"]))
    {   
        //validate username
        if ( strpos($_POST['username'], ' ') !== false ) 
        {
            $errors['username'] = "username shouldn t contain spaces!";
        }
        
        if(!isset($errors['username']))
        {
            // prepare and bind
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam('username',$_POST['username']);
            //execute
            $stmt->execute();

            //result 
            $result = $stmt->fetchAll();
            if(count($result)>0  )
            {   
                $errors['username'] = "this username is already in use!";
            }
        }

        if(!isset($errors['username']))
        {
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE users SET username = :new WHERE username = :username");
            $stmt->bindParam('new',$_POST['username']);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['user']['username'] = $_POST['username'];
            $_SESSION['msg'] = "Username Changed Successfully";
            $_SESSION['color'] = "green";
            
        }else{
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();

    }


    if(isset($_POST["description"]))
    {   
        //validate description
        if ( strlen($_POST['description']) <0 ) 
        {
            $errors['description'] = "description os required!";
        }
        
        if(!isset($errors['description']))
        {
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE users SET description = :description WHERE username = :username");
            $stmt->bindParam('description',$_POST['description']);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['user']['description'] = $_POST['description'];
            $_SESSION['msg'] = "Description Changed Successfully";
            $_SESSION['color'] = "green";
            
        }else{
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    if(isset($_POST["bio"]))
    {   
        //validate description
        if ( strlen($_POST['bio']) <0 ) 
        {
            $errors['bio'] = "bio os required!";
        }
        
        if(!isset($errors['bio']))
        {
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE users SET bio = :bio WHERE username = :username");
            $stmt->bindParam('bio',$_POST['bio']);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['user']['bio'] = $_POST['bio'];
            $_SESSION['msg'] = "Bio Changed Successfully";
            $_SESSION['color'] = "green";
            
        }else{
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    if(isset($_POST["facebook"]))
    {   
        //validate description
        if ( strlen($_POST['facebook']) <0 ) 
        {
            $errors['facebook'] = "facebook link is required!";
        }
        
        if(!isset($errors['facebook']))
        {
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE links SET facebook = :facebook WHERE username = :username");
            $stmt->bindParam('facebook',$_POST['facebook']);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['msg'] = "facebook link added Successfully";
            $_SESSION['color'] = "green";
            
        }else{
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    if(isset($_POST["twitter"]))
    {   
        //validate description
        if ( strlen($_POST['twitter']) <0 ) 
        {
            $errors['twitter'] = "twitter link is required!";
        }
        
        if(!isset($errors['twitter']))
        {
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE links SET twitter = :twitter WHERE username = :username");
            $stmt->bindParam('twitter',$_POST['twitter']);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['msg'] = "twitter link added Successfully";
            $_SESSION['color'] = "green";
            
        }else{
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }


    if(isset($_POST["instagram"]))
    {   
        //validate description
        if ( strlen($_POST['instagram']) <0 ) 
        {
            $errors['instagram'] = "instagram link is required!";
        }
        
        if(!isset($errors['instagram']))
        {
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE links SET instagram = :instagram WHERE username = :username");
            $stmt->bindParam('instagram',$_POST['instagram']);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['msg'] = "instagram link added Successfully";
            $_SESSION['color'] = "green";
            
        }else{
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    if(isset($_POST["tiktok"]))
    {   
        //validate description
        if ( strlen($_POST['tiktok']) <0 ) 
        {
            $errors['tiktok'] = "tiktok link is required!";
        }
        
        if(!isset($errors['tiktok']))
        {
            // prepare and bind
            $stmt = $conn->prepare(" UPDATE links SET tiktok = :tiktok WHERE username = :username");
            $stmt->bindParam('tiktok',$_POST['tiktok']);
            $stmt->bindParam('username',$_SESSION['user']['username']);
            $stmt->execute();
            $_SESSION['msg'] = "tiktok link added Successfully";
            $_SESSION['color'] = "green";
            
        }else{
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    //password
    if(isset($_POST["old"]))
    {   
        //validate password
        if(strlen($_POST['new'])<8 )
        {
            $errors['new'] = "the password should be more than 8 chars!";
        }


        if(!isset($errors['new']))
        {  
            $old = $_POST['old'];
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username limit 1");
            $stmt->bindParam('username',$_SESSION['user']['username']);
            //execute
            $stmt->execute();
    
            //result 
            $result = $stmt->fetchAll();
            if(count($result)>0  )
            {
                if( password_verify($old,$result[0]['password']))
                { echo 
                    $password = password_hash( $_POST['new'] , PASSWORD_DEFAULT ) ;
                    if(!isset($errors['password']))
                    {
                        // prepare and bind
                        $stmt = $conn->prepare(" UPDATE users SET password = :new WHERE username = :username");
                        $stmt->bindParam('new',$password);
                        $stmt->bindParam('username',$_SESSION['user']['username']);
                        $stmt->execute();
                        $_SESSION['user']['password'] = $password;
                        $_SESSION['msg'] = "Password Changed Successfully";
                        $_SESSION['color'] = "green";
                        
                    }
                    
                }else{
                    $errors['old'] = "The Password Is Incorrect!";
                }
            }
        }
        $_SESSION['errors'] = $errors;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
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
    <div class="w-full px-5 md:py-14 py-6">
        <div class="text-bold text-3xl ">
            Profile
        </div>

        <div>
            <!--profileimg-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="inline-block">Profile image:</span>

                    <label for="image" class="inline-block ml-4 text-white hover:bg-indigo-700 bg-indigo-600 cursor-pointer text-center my-2 w-28 rounded border p-2">Uplaod</label>
                    <input type="file" 
                    id="image" name="image" 
                    class="opacity-0 absolute " required
                    >

                    <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['image'])?print($_SESSION['errors']['image']) : null;
                        ?>
                    </div>
                </div>
            </form>

            <!--profile cover-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="inline-block">Profile cover:</span>

                    <label for="cover" class="inline-block ml-4 text-white hover:bg-indigo-700 bg-indigo-600 cursor-pointer text-center my-2 w-28 rounded border p-2">Uplaod</label>
                    <input type="file" 
                    id="cover" name="cover" 
                    class="opacity-0 absolute " required
                    >

                    <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['cover'])?print($_SESSION['errors']['cover']) : null;
                        ?>
                    </div>
                </div>
            </form>

            <!--background-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="inline-block">background:</span>

                    <label for="bg" class="inline-block ml-4 text-white hover:bg-indigo-700 bg-indigo-600 cursor-pointer text-center my-2 w-28 rounded border p-2">Uplaod</label>
                    <input type="file" 
                    id="bg" name="bg" 
                    class="opacity-0 absolute " required
                    >

                    <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['cover'])?print($_SESSION['errors']['cover']) : null;
                        ?>
                    </div>
                </div>
            </form>

            <!--username-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">change username:</span>
                    <input type="text" 
                    id="username" name="username" 
                    placeholder="username"
                    class="form-input" required
                    >
                    
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['username'])?print($_SESSION['errors']['username']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
            </form>

            <!--description-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">change description:</span>
                    <input type="text" 
                    id="description" name="description" 
                    placeholder="Someone who is..."
                    value="<?php echo $_SESSION['user']['description'] ?>"
                    class="form-input" required
                    >
                    
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['description'])?print($_SESSION['errors']['description']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
            </form>
            <!--bio-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">change bio:</span>
                    <textarea type="text" 
                    id="bio" name="bio" 
                    placeholder="Something..."
                    class="form-input" rows="4" required
                    ><?php echo $_SESSION['user']['bio'] ?></textarea>
                    
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['bio'])?print($_SESSION['errors']['bio']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
            </form>
            <!--facebook-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">facebook link:</span>
                    <input type="text" 
                    id="facebook" name="facebook" 
                    placeholder="facebook"
                    class="form-input" required
                    >
                    
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['facebook'])?print($_SESSION['errors']['facebook']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
            </form>

            <!--twitter-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">twitter link:</span>
                    <input type="text" 
                    id="twitter" name="twitter" 
                    placeholder="twitter"
                    class="form-input" required
                    >
                    
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['twitter'])?print($_SESSION['errors']['twitter']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
            </form>


            <!--instagram-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">instagram link:</span>
                    <input type="text" 
                    id="instagram" name="instagram" 
                    placeholder="instagram"
                    class="form-input" required
                    >
                    
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['instagram'])?print($_SESSION['errors']['instagram']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
            </form>


            <!--tiktok-->
            <form class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">tiktok link:</span>
                    <input type="text" 
                    id="tiktok" name="tiktok" 
                    placeholder="tiktok"
                    class="form-input" required
                    >
                    
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['tiktok'])?print($_SESSION['errors']['tiktok']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
            </form>
            <!--password-->
            <form onsubmit="return submitCheck();" class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl " action="settings.php" method="post" enctype="multipart/form-data">
                <div class='form-group my-3 '>
                    <span  class="block">change password:</span>
                    <span  class="block text-gray-500">old password</span>
                    <input type="password" 
                    id="old" name="old" 
                    placeholder="********"
                    class="form-input" required
                    >
                    <span  class="block text-gray-500">new password</span>
                    <input type="password" 
                    id="new" name="new" 
                    placeholder="********"
                    class="form-input" required
                    >
                    <span  class="block text-gray-500">confirm password</span>
                    <input type="password" 
                    id="confirm" 
                    placeholder="********"
                    class="form-input" required
                    >
                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['old'])?print($_SESSION['errors']['old']) : null;
                        ?>
                    </div>
                </div>
                <button name="submit" type="submit" class="text-white ml-4 px-4 py-2 text-center rounded bg-green-400 hover:bg-green-500">Save</button>
                <script>
                    function submitCheck(){
                        let password = document.getElementById('new').value ,
                        passwordConfirmation =   document.getElementById('confirm').value ;
                        if(password == passwordConfirmation)
                        {
                            return true;   
                        }
                        else
                        {   
                            event.preventDefault();
                            //show container
                            document.getElementById('errorContainer').classList.add('opacity-100');
                            document.getElementById('errorContainer').classList.remove('opacity-0');
                            setTimeout(()=>{
                                //hide container
                                document.getElementById('errorContainer').classList.add('opacity-0');
                                document.getElementById('errorContainer').classList.remove('opacity-100');
                            },3000)
                            return false;
                        }
                    };
                </script>
            </form>
        </div>
    </div>
    <?php
        include_once('components/error.php');
    ?>
</body>
</html>
<?php
    unset($_SESSION['errors']);

?>