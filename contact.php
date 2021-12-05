<?php
session_start();

//if there a post request 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) 
{     
    unset($_POST['submit']);
    //sanitaze all inputs 
    include_once('src/sanitaze.php');


    //validate
    if(isset($_SESSION['user']))
    {
        $expected_inputs=['phone','message'];
        include_once('src/validation.php');
        $errors = validate($_POST,$expected_inputs);
        
    }
    else{
        include_once('src/validation.php');
        $errors = validate($_POST,$expected_inputs);
        $expected_inputs=['firstName','secondName','email','phone','message'];
        //validate email
        if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) && !isset($errors['email'])) 
        {
            $errors['email'] = "the email is invalid!";
        }
    }
    

    

    //validate phone number
    if (!preg_match('/^[0-9]{10}+$/', $_POST['phone']) && !isset($errors['phone'])) 
    {
        $errors['phone'] = "the mobile number is invalid!";
    }


    //if everuthing is ockey 
    if(count($errors) == 0)
    {
        //store in db
        include_once('src/dbconnect.php');
        // set parameters
        $firstName = $_POST['firstName'];
        $secondName = $_POST['secondName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];

        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO contacts (first_name, second_name, email, phone, message) VALUES (:first_name, :second_name, :email, :phone, :message)");
        $stmt->bindParam('first_name',$firstName);
        $stmt->bindParam('second_name',$secondName);
        $stmt->bindParam('email',$email);
        $stmt->bindParam('phone',$phone);
        $stmt->bindParam('message',$message);


        //execute
        $stmt->execute();

        $_SESSION['msg'] = "The Message Has Been Sent Successfully";
        $_SESSION['color'] = "green";
    }else{
        $_SESSION['errors'] = $errors;
        $_SESSION['inputs'] = $_POST;

        $_SESSION['msg'] = "The Is An Error!";
        $_SESSION['color'] = "red";
    }

    //redirect to pervios page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
}else{
    $oparations = ['+','-'];
    $oparation = $oparations[rand(0 , count($oparations)-1) ];
    $firstNum= rand(1,11);
    $secondNum= rand(1,11);
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
<body class="bg-indigo-50  pb-16 md:pb-0 md:pl-16">
    <!--sidebar-->
    <?php
    include_once('components/sidebar.php');
    ?>


    <!--container-->
    <section class="w-full md:p-16 md:px-4  p-4 my-14 " >
        <form action="contact.php" method="POST" onsubmit="return botValidation();" class="w-full lg:w-2/3 rounded-md p-4 mx-auto text-left shadow-md bg-white text-gray-800">
            <!--contact title-->
            <div class="text-gray-900 font-semibold text-lg md:text-xl my-1">
                Contact Us
            </div>

            <!--contact little description-->
            <div class="text-gray-400  text-sm md:text-base mb-6">
                Let Us Know What You Think :)
            </div>

            <?php if(!isset($_SESSION['user']))
            {
                echo '
                <!--section one-->
            <div class="w-full block md:flex">
                <!--first name section-->
                <div class="form-group my-1 px-2 w-full md:w-1/2">

                    <input type="text" placeholder="First Name" 
                    id="firstName" name="firstName"  
                    class="form-input" required
                    value="';
            isset($_SESSION['inputs']['firstName'])?print($_SESSION['inputs']['firstName']) : null;
                echo '"
                    >

                    <div class="form-error">
                    ';
            isset($_SESSION['errors']['firstName'])?print($_SESSION['errors']['firstName']) : null;                   
            echo '
                    </div>

                </div>
                <!--second name section-->
            <div class="form-group my-1 px-2 w-full md:w-1/2">

                <input type="text" placeholder="Second Name" 
                id="secondName" name="secondName"  
                class="form-input" required
                value="';
            isset($_SESSION['inputs']['secondName'])?print($_SESSION['inputs']['secondName']) : null ;
                echo'"
                >

                <div class="form-error">
                ';
            isset($_SESSION['errors']['secondName'])?print($_SESSION['errors']['secondName']) : null;           
                echo '
                </div>

            </div>


            </div>
            ';
            }?>
            

        <!--section two-->
        <div class="w-full block md:flex">
            
            <?php if(!isset($_SESSION['user']))
            {
                echo '
                <!--email section-->
            <div class="form-group my-1 px-2 w-full md:w-1/2">

                <input type="email" placeholder="Email" 
                id="email" name="email"  
                class="form-input" required
                value="
                    ';
            isset($_SESSION['inputs']['email'])?print($_SESSION['inputs']['email']) : null;                    
            echo '"
                >

                <div class="form-error">
                ';
            isset($_SESSION['errors']['email'])?print($_SESSION['errors']['email']) : null;       
                        echo '
                </div>

            </div>
                ';
            }
                ?>
            <!--Phone section-->
            <div class='form-group my-1 px-2 w-full md:w-1/2'>

                <input type="text" placeholder="+212696080397" 
                id="phone" name="phone"  
                class="form-input" required
                value="<?php
                        isset($_SESSION['inputs']['phone'])?print($_SESSION['inputs']['phone']) : null;
                    ?>"
                >

                <div class="form-error">
                <?php
                        isset($_SESSION['errors']['phone'])?print($_SESSION['errors']['phone']) : null;
                    ?>
                </div>

            </div>

        </div>

        <!--message section-->
        <div class='form-group my-1 px-2 w-full'>

            <textarea rows="4"  placeholder="Your Message..." 
            id="message" name="message"  
            class="form-input" required
            ><?php 
            isset($_SESSION['inputs']['message'])?print($_SESSION['inputs']['message']) : print('');
            ?></textarea>

            <div class="form-error">
            <?php
                    isset($_SESSION['errors']['message'])?print($_SESSION['errors']['message']) : null;
                ?>
            </div>

        </div>
    
        <!--section four-->
        <div class="w-full block md:flex">
            <!--botValidation section-->
            <div class='form-group my-1 px-2 w-full md:w-1/2'>

                <input type="text" 
                placeholder="<?php echo $firstNum.$oparation.$secondNum ;?>=" 
                id="operation"   
                class="form-input" required
                value=""
                >

            </div>
        </div>

        <!--submit button-->
        <button type="submit" name="submit" class="w-24 btn-primary ml-2 mt-3 mb-6 ">
            Submit
        </button>

    </form>

    </section>

    <!--error msg-->
    <?php
    include_once('components/error.php');
    ?>


    <!--botValidation-->
    <script>
        function botValidation()
        {   let result = document.getElementById('operation').value;
            let trueresult = <?php echo $firstNum.$oparation.$secondNum ?>;
            if(result == trueresult)
            {
                return true;
            }else{
                event.preventDefault();
            }
            
        }
    </script>
    
</body>
</html>
<?php
    unset($_SESSION['errors']);
    unset($_SESSION['inputs']);
?>