<?php
session_start();
//get meesages
include_once('src/dbconnect.php'); 
if(!isset($_GET['message']))
{
    // prepare and bind
    $stmt = $conn->prepare("SELECT id,sender,msg,created_at FROM chat ORDER BY created_at DESC limit 30");
    //execute
    $stmt->execute();
    $fetch = $stmt->fetchAll();
    $result = array_reverse($fetch);
}
else
{
    // prepare and bind
    $stmt = $conn->prepare("SELECT id,sender,msg,created_at FROM chat ORDER BY created_at DESC limit ".(int)$_GET['message']);
    //execute
    $stmt->execute();
    $fetch = $stmt->fetchAll();
    $result = array_reverse($fetch);
}
foreach($result as $index => $query)
{   
    //date
    $old_date_timestamp = strtotime($result[$index]['created_at']);
    $result[$index]['created_at'] = date('H:i', $old_date_timestamp);   
    //image
    $stmt = $conn->prepare("SELECT image FROM users WHERE username =:username limit 1");
    $stmt->bindParam('username', $query["sender"]);
    //execute
    $stmt->execute();
    $fetchy = $stmt->fetchAll();
    $image = $fetchy[0];
    $result[$index]["image"] = $image[0];
}

if(isset($_SESSION['user']) )
{
    if( $_SESSION['user']['image'] !== null)
    {
        $image = $_SESSION['user']['image'];
    }
    else
    {
        $image = '<div class="flex items-center justify-center text-2xl w-full h-full rounded-full bg-indigo-500 flex-shrink-0">
        '.substr($_SESSION['user']['username'],0,1) .'
        </div>';
    }
}
else{
    $image = "";
}

//favorite stickers
//get stickers
// prepare and bind
$stmt = $conn->prepare(" SELECT author,username,sticker  FROM favorite_stickers WHERE username =:username ");
$stmt->bindParam('username',$_SESSION['user']['username']);
$stmt->execute();
$favorite_stickers = $stmt->fetchAll(); 


$stmt = $conn->prepare(" SELECT username,sticker  FROM stickers WHERE username =:username ");
$stmt->bindParam('username',$_SESSION['user']['username']);
$stmt->execute();
$created_stickers = $stmt->fetchAll(); 
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
<body class="bg-indigo-50 pb-16 md:pb-0 md:pl-16" 
style="<?php
if(isset($_SESSION['user']) && $_SESSION['user']['bakground'] !== null)
    {
        echo 'background-image:url(' . $_SESSION['user']['bakground'] . ');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center; 
        ' ;
    }
    ?>
    ">
    <?php
    /*--sidebar--*/
    include_once('components/sidebar.php');
?>

<div class="flex flex-col  h-full  mb-4    w-full  sm:block ">
    <!--top bar-->
    <div class="flex justify-between py-2 border-b border-purple-700 bg-gray-50 relative text-xl w-full font-medium pl-4">
        <div class="flex">
            <div class="w-14 h-14 relative ">
                <div class="overflow-hidden rounded-full w-full h-full border-2 border-purple-700">
                    <?php
                    if(isset($_SESSION['user']))
                    {
                        if($_SESSION['user']['image'] !== null)
                        {
                            echo '<img src="'.$_SESSION['user']['image'].'" alt="" class="shadow-lg  w-full h-auto align-middle border-none" />';
                        }else{
                            echo '<div class="flex items-center justify-center text-2xl w-full h-full rounded-full bg-indigo-500 flex-shrink-0">
                            '.substr($_SESSION['user']['username'],0,1) .'
                            </div>';
                        }
                    }
                    ?>
                
                
                </div>

            </div>

            <div class="text-gray-600 text-xs  ml-4 mt-3">
                <div class="font-bold">
                    <?php
                        if(isset($_SESSION['user']))
                        {
                            echo $_SESSION['user']['first_name'];
                            echo '<div class="text-gray-400">
                            En ligne
                            </div>';
                        }
                    ?>
                </div>

                

            </div>
        </div>

        <div class="flex items-center">
            <button type="button" class="chat-top-bar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </button>
            <button type="button" class="chat-top-bar">

                <svg
                class="h-5 w-6 "
                style=""
                role="img"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 192 512"
                >
                <path
                    fill="currentColor"
                    d="M96 184c39.8 0 72 32.2 72 72s-32.2 72-72 72-72-32.2-72-72 32.2-72 72-72zM24 80c0 39.8 32.2 72 72 72s72-32.2 72-72S135.8 8 96 8 24 40.2 24 80zm0 352c0 39.8 32.2 72 72 72s72-32.2 72-72-32.2-72-72-72-72 32.2-72 72z"
                ></path>
                </svg>
            </button>
        </div>
    </div>
    
    
    <!--body-->
    <div id="body" class="w-full mb-20">
    <a href="index.php?message=<?php
    isset($_GET['messages'])?  print(((int)$_GET['messages'])+30) : print(60) ;
    ?>" class="w-full py-3 block bg-gray-100 border-b border-indigo-700 text-center">Show More</a>
        <?php
        foreach($result as $index => $query)
        {   //image
            if($query['image'] !== null)
            {
                $image = "<img src='" . $query['image'] . "' alt='". $query['sender'] ."'>";
            }else{  
                $image = substr($query['sender'],0,1);
            }
            if(isset($_SESSION['user'] ) && $query['sender'] == $_SESSION['user']['username'])
            {
                echo '<div class="col-start-6 col-end-13 p-3 rounded-lg">
                <div class="my-msg">
                    <div class="profile-box">
                        '.$image.'
                    </div>
                    <div class="relative mr-3 text-sm text-white bg-gradient-to-br from-indigo-500 to-purple-700 py-2 px-4 shadow rounded-xl">
                        <div>'. $query["msg"] . '</div>
                        <div class="msg-time" >
                            <span class="mr-1.5 text-xs">'. $query["created_at"] . '</span>
                        </div>
                    </div>
                </div>
            </div>';
            }
            else{
                echo '<div class="flex flex-col h-full">
                <div class="grid grid-cols-12 gap-y-2">
                    <div class="col-start-1 col-end-8 p-3 rounded-lg">
                        <div class="flex flex-row items-center">
                            <a href="profile.php?user='.$query['sender'].'" class="profile-box">
                            '.$image.'
                            </a>
                            <!--msg-->
                            <div class="msg">
                                <div>'. $query["msg"] . '</div>
                                <div class="msg-time"> 
                                    <span class="mr-1.5 text-xs">'. $query["created_at"] . '</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            }
        }
        ?>
    </div>
    <!--send form-->
    <div class="flex send-form-width fixed w-full bottom-16 md:bottom-0 right-0 border-b border-indigo-700 flex-row items-center h-16  mt-5 bg-white px-4" 
    >
        <style>
            @media (min-width: 640px) {
                .send-form-width{
                    width: calc( 100% - 4rem ) !important;
                }
            }
        </style>
        <div class="flex-grow ml-4">
        <div class="relative w-full">
            <input
            type="text" id="msgInput"
            placeholder="Message..."
            class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10"
            />
            <button
                class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600"
                >
                <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
            </button>
            
        </div>
      </div>
      <div class="ml-4">
    <?php
            if(isset($_SESSION['user']['username']))
            {
                echo '<button  onclick="sendMessage()"
            class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0"
            >
            <span>Send</span>
            <span class="ml-2">
                <svg
                class="w-4 h-4 transform rotate-45 -mt-px"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                ></path>
                </svg>
            </span>
            </button>';
                
            }
            else
            {
                echo '<a href="login.php"
            class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0"
            >
            <span>Send</span>
            <span class="ml-2">
                <svg
                class="w-4 h-4 transform rotate-45 -mt-px"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                ></path>
                </svg>
            </span>
            </a>';
            }
            ?>
        </div>
    </div>
    </div>
    <div class="w-11/12 hidden rounded-md z-50 max-h-60 h-60 border overflow-auto bg-white p-2 fixed bottom-36 md:bottom-20 left-1/2 transform -translate-x-1/2">
        <div class="flex w-full border-b">
            <div onclick="showCont(0)" class="p-1 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-star" width=18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                </svg>
            </div>
            <div onclick="showCont(1)" class="p-1 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            </div>

            <div onclick="showCont(2)" class="p-1 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-sticker" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M20 12l-2 .5a6 6 0 0 1 -6.5 -6.5l.5 -2l8 8" />
                <path d="M20 12a8 8 0 1 1 -8 -8" />
            </svg>
            </div>
        </div>

        <!--favorite-->
        <div class="w-full stickers my-2">
        <?php 
    foreach($favorite_stickers as $sticker)
    {
        echo '
        <div class="w-1/4 sm:w-2/12 md:w-1/12 inline-block px-5 ">
            <img class="w-full border shadow-lg" src="'. $sticker['sticker'].'" alt="sticker">
        </div>
        ';
    }
    ?>

        </div>
        <!--created-->
        <div class="w-full hidden stickers my-2">
        <?php 
    foreach($created_stickers as $sticker)
    {
        echo '
        <div class="w-1/4 sm:w-2/12 md:w-1/12 inline-block px-5 ">
            <img class="w-full border shadow-lg" src="'. $sticker['sticker'].'" alt="sticker">
        </div>
        ';
    }
    ?>
        </div>
    </div>
</body>

<script>
    function showCont(index)
    {
        let stickerConts = document.getElementsByClassName('stickers')
        for(let i= 0 ; i<stickerConts.length;i++)
        {   
            let el = document.getElementsByClassName('stickers')[i];
            if(i == index )
            { 
                el.classList.remove('hidden')
            }
            else
            {
                if(!el.classList.contains('hidden') )
                {
                    el.classList.add('hidden')
                }
            }
        }
    }
window.scrollTo(0,document.body.scrollHeight);
var conn = new WebSocket(`ws://localhost:8080<?php if(isset($_SESSION['user']['username'])){echo '?token='.$_SESSION['user']['username'];}?>`);
conn.onopen = function(e) {
console.log("Connection established!");
};

conn.onmessage = function(e) {
    const sound = new Audio('assets/effect/msg.mp3');
    sound.play();
    let message = JSON.parse(e.data);
    let image = message.image!==null? `<img src="${message.image}" alt="${message.sender[0]} profile"`: message.sender[0].toUpperCase();
    
    let messageEL = `<div class="flex flex-col h-full">
                <div class="grid grid-cols-12 gap-y-2">
                    <div class="col-start-1 col-end-8 p-3 rounded-lg">
                        <div class="flex flex-row items-center">
                            <a href="profile.php?user=${message.sender}" class="profile-box">
                            ${image}
                            </a>
                            <!--msg-->
                            <div class="msg">
                                <div> ${message.msg}</div>
                                <div class="msg-time"> 
                                    <span class="mr-1.5 text-xs"> ${message.created_at}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        document.getElementById('body').innerHTML += messageEL;
        window.scrollTo(0,document.body.scrollHeight);
};


function sendSticker(value)
{
    conn.send(value);
}

function sendMessage(){
    let value = document.getElementById("msgInput").value.trim();
    if(value.length > 0 ){
        conn.send(value);
        const sound = new Audio('assets/effect/msg.mp3');
        sound.play();
        let image = `<?php echo $image ?>`;
        let today = new Date();
        let time = today.getHours() + ":" + today.getMinutes();
        let message = `<div class="col-start-6 col-end-13 p-3 rounded-lg">
            <div class="my-msg">
                <div class="profile-box">
                    ${image}
                </div>
                <div class="relative mr-3 text-sm text-white bg-gradient-to-br from-indigo-500 to-purple-700 py-2 px-4 shadow rounded-xl">
                    <div>${value}</div>
                    <div class="msg-time" >
                        <span class="mr-1.5 text-xs">${time}</span>
                    </div>
                </div>
            </div>
        </div>`;
        document.getElementById('body').innerHTML += message;
        document.getElementById("msgInput").value = "";
        window.scrollTo(0,document.body.scrollHeight);
    }

}

</script>
</html>