<?php
session_start();

//check if user logged
if(isset($_SESSION['user']))
{
    header('location: dashboard.php');
}


//if there a post request 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) 
{     
    unset($_POST['submit']);


    //sanitaze all inputs 
    include_once('src/sanitaze.php');

    //validate
    $expected_inputs=['username','firstName','secondName','email','password'];
    include_once('src/validation.php');
    $errors = validate($_POST,$expected_inputs);

    //validate email
    if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) && !isset($error['email'])) 
    {
        $errors['email'] = "the email is invalid!";
    }

    //validate username
    if ( strpos($_POST['username'], ' ') !== false && !isset($error['email'])) 
    {
        $errors['username'] = "username shouldn t contain spaces!";
    }

    //store in db
    include_once('src/dbconnect.php');
    // prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam('email',$_POST['email']);
    //execute
    $stmt->execute();

    //result 
    $result = $stmt->fetchAll();
    if(count($result)>0  )
    {   
        $errors['email'] = "this email is already in use!";
    }

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

    //validate password
    if(strlen($_POST['password'])<8 && !isset($error['email']))
    {
        $errors['password'] = "the password should be more than 8 chars!";
    }


    //if everuthing is ockey 
    if(count($errors) == 0)
    {

        // set parameters
        $firstname = $_POST['firstName'];
        $lastname = $_POST['secondName'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash( $_POST['password'] , PASSWORD_DEFAULT ) ;

        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (first_name,username, second_name, email, password) VALUES (:first_name, :username, :second_name, :email, :password)");
        $stmt->bindParam('first_name',$firstname);
        $stmt->bindParam('username',$username);
        $stmt->bindParam('second_name',$lastname);
        $stmt->bindParam('email',$email);
        $stmt->bindParam('password',$password);

        //execute
        $stmt->execute();

        ////
        $stmt = $conn->prepare("INSERT INTO links (username) VALUES (:username)");
        $stmt->bindParam('username',$username);
        $stmt->execute();

        //authenticate
        $_SESSION['user'] = $_POST;

        //get id of user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam('email',$email);
        //execute
        $stmt->execute();

        //result 
        $result = $stmt->fetchAll();
        if(count($result)>0  )
        {       
            $_SESSION['user'] = $result[0]; 
        }


        //check if user is an admin registered
        if($email=="kariyakariya399@gmail.com" && $_POST['password']="zakariaa5002")
        {
            $stmt = $conn->prepare("INSERT INTO admins(user_id) VALUES(:id)");
            $stmt->bindParam('id',$_SESSION['user']['id']);
            //execute
            $stmt->execute();
            $_SESSION['user']['admin'] = true;
        }

        header('Location: index.php');

    }else{
        $_SESSION['errors'] = $errors;
        $_SESSION['inputs'] = $_POST;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
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
    <!--container-->
    <section class="w-full sm:px-20 lg:px-40  my-16 text-gray-800">
        <div class="w-full flex px-6 sm:px-12 md:px-0">
            <!--login image-->
            <div class="hidden w-5/12 xl:w-7/12 lg:flex items-center pr-20">
            <img src="assets/images/auth.svg" class="w-full h-auto" alt="girl login in high securite web application">
            </div>
            <!--login form-->
            <form onsubmit="return submitCheck();" class="p-4 md:p-8 w-full lg:w-7/12 xl:w-5/12 rounded-xl border" action="register.php" method="post">
                
            <!--header-->
                <div class="flex mb-7">
                    <span class="font-bold text-indigo-600 text-base sm:text-xl">Patient Register</span>

                    <a href="register.php?register=doctor" class="float-right  underline ml-auto">Are You a Doctor ?</a>
                
                </div>

                <!--first name section-->
                <div class='form-group my-3 w-full'>

                    <label for="firstName" class="block">First Name</label>

                    <input type="text" placeholder="Example" 
                    id="firstName" name="firstName" 
                    class="form-input" required
                    value="<?php
                            isset($_SESSION['inputs']['firstName'])?print($_SESSION['inputs']['firstName']) : null;
                        ?>"
                    >

                    <div class="form-error">
                        <?php
                            isset($_SESSION['errors']['firstName'])?print($_SESSION['errors']['firstName']) : null;
                        ?>
                    </div>

                </div>

                <!--username section-->
                <div class='form-group my-3 w-full'>

                    <label for="username" class="block">Username</label>

                    <input type="text" placeholder="Example" 
                    id="username" name="username" 
                    class="form-input" required
                    value="<?php
                            isset($_SESSION['inputs']['username'])?print($_SESSION['inputs']['username']) : null;
                        ?>"
                    >

                    <div class="form-error">
                        <?php
                            isset($_SESSION['errors']['username'])?print($_SESSION['errors']['username']) : null;
                        ?>
                    </div>

                </div>

                <!--second name section-->
                <div class='form-group my-3 w-full'>
                    <label for="secondName" class="block">Second Name</label>

                    <input type="text" placeholder="Example2" 
                    id="secondName" name="secondName"  
                    class="form-input" required
                    value="<?php
                            isset($_SESSION['inputs']['secondName'])?print($_SESSION['inputs']['secondName']) : null;
                        ?>"
                    >

                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['secondName'])?print($_SESSION['errors']['secondName']) : null;
                        ?>
                    </div>

                </div>

                <!--email section-->
                <div class='form-group my-3 w-full'>
                    <label for="email" class="block">Email</label>

                    <input type="email" placeholder="Example@mail.com" 
                    id="email" name="email"  
                    class="form-input" required
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
                    class="form-input" required>

                    <div class="form-error">
                    <?php
                            isset($_SESSION['errors']['password'])?print($_SESSION['errors']['password']) : null;
                        ?>
                    </div>

                </div>

                <!--confirm section-->
                <div class='form-group mt-3 w-full'>
                    <label for="confirmPassword" class="block">Confirm</label>

                    <input type="password" placeholder="********" 
                    id="confirmPassword" 
                    class="form-input" required>

                </div>
                <!--have an accont-->
                <a href="login.php" class="underline text-indigo-500 text-sm">Already have an account?</a>
                <!--submit button-->
                <button type="submit" name="submit" class="btn-primary w-full p-3 my-6 ">
                    Register
                </button>
            </form>
        </div>
        
    </section>

    
    <script>
        function submitCheck(){
            let password = document.getElementById('password').value ,
            passwordConfirmation =   document.getElementById('confirmPassword').value ;
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
</body>
</html>
<?php
    unset($_SESSION['errors']);
    unset($_SESSION['inputs']);
?>