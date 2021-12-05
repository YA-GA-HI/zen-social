<?php
session_start();

//check if user logged
if(isset($_SESSION['user']))
{
    header('location: index.php');
}


//if there a post request 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) 
{
    unset($_POST['submit']);
    //sanitaze all inputs 
    include_once('src/sanitaze.php');

    //validate inputs 
    $expected_inputs=['email','password'];
    include_once('src/validation.php');
    $errors = validate($_POST,$expected_inputs);

    //validate email
    if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) && !isset($error['email'])) 
    {
        $errors['email'] = "the email is invalid!";
    }

    //validate password
    if(strlen($_POST['password'])<8 && !isset($error['email']))
    {
        $errors['password'] = "the password should be more than 8 chars!";
    }


    //if everuthing is ockey 
    if(count($errors) == 0)
    {
        //store in db
        include_once('src/dbconnect.php');

                // set parameters
                $email = $_POST['email'];
                $password = $_POST['password'];

        // prepare and bind
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam('email',$email);
        //execute
        $stmt->execute();

        //result 
        $result = $stmt->fetchAll();
        if(count($result)>0  )
        {   if( password_verify($password,$result[0]['password']))
            {
                //login msg
                $_SESSION['msg']="You Logged In Successfully";
                $_SESSION['color']="green";
                
                //authenticate
                $_SESSION['user'] = $result[0] ;

                //check if admin
                $stmt = $conn->prepare("SELECT * FROM admins WHERE user_id = :id");
                $stmt->bindParam('id',$_SESSION['user']['id']);
                //execute
                $stmt->execute();

                //result 
                $result = $stmt->fetchAll();
                if(count($result)>0  )
                {   //admin
                    $_SESSION['user']['permission'] = "a";
                }

                header('Location: index.php');
                
            }else{
                $errors['password'] = "there is an error in your credentiels!";
            }
        }else{
            $errors['email'] = "this email is not registred yet!";
        }

    }
        $_SESSION['errors'] = $errors;
        $_SESSION['inputs'] = $_POST;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
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
    <link rel="stylesheet" href="assets/css/animate.css">
</head>
<body>


    <!--error cotainer-->
    <div id="errorContainer" class="bg-red-500 duration-500 fixed z-10 left-1/2 top-9 transform opacity-0  -translate-x-1/2 p-2 text-white rounded-md">
        There Is An Error In Your Password Verification
    </div>
    <!--container-->
    <section class="w-full sm:px-20 lg:px-60  my-16 text-gray-800">
        <div class="w-full flex px-6 sm:px-12 md:px-0">
            <!--login image-->
            <div class="hidden w-6/12  lg:flex items-center pr-20">
            <img src="assets/images/login.svg" class="w-full h-auto" alt="girl login in high securite web application">
            </div>
            <!--login form-->
            <form  class="p-4 md:p-8 w-full lg:w-6/12  rounded-xl border" action="login.php" method="post">
                
            <!--header-->
                <div class="flex mb-7">
                    <span class="font-bold text-green-500 text-base sm:text-xl">User Register</span>

                    <span class="float-right ml-auto">Welcome</span>
                
                </div>

                <!--email section-->
                <div class='form-group my-3 w-full'>
                    <label for="email" class="block">Email</label>

                    <input type="email" placeholder="Example@mail.com" 
                    id="email" name="email"  
                    class="focus:ring-green-500 form-input" required
                    value="
                        <?php
                            isset($_SESSION['inputs']['email'])?print($_SESSION['inputs']['email']) : null;
                        ?>"
                    >

                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['email'])?print($_SESSION['errors']['email']) : null;
                        ?>
                    </div>

                </div>

                <!--password section-->
                <div class='form-group my-3 w-full'>
                    <label for="passowrd" class="block">Password</label>

                    <input type="password" placeholder="********" 
                    id="password" name="password" 
                    class="focus:ring-green-500 form-input" required>

                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['password'])?print($_SESSION['errors']['password']) : null;
                        ?>
                    </div>

                </div>

                <!--have an accont-->
                <a href="#" class="underline text-green-400 text-sm">Forgot Password?</a>
                <!--submit button-->
                <button type="submit" name="submit" class="text-white w-full p-3 my-6 text-center rounded-md bg-green-500 hover:bg-green-600">
                    Login
                </button>
            </form>
        </div>
        
    </section>
</body>
</html>
<?php

//logout msg
include_once('components/error.php');

unset($_SESSION['errors']);
unset($_SESSION['inputs']);
?>