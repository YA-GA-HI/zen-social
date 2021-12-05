
<?php
if(isset($_SESSION['msg']))
{
$color = $_SESSION['color'];
echo '
<!--error cotainer-->
<div class="errorContainer bg-' . $color . '-500 duration-500 fixed z-50 left-1/2 top-9 transform opacity-0  -translate-x-1/2 p-2 text-white rounded-md">
    ' . $_SESSION['msg'] . '
</div>
<script>
    let errorContainers = document.getElementsByClassName("errorContainer");
    for(let i=0;i < errorContainers.length ; i++)
    {
        errorContainers[i].classList.add("opacity-100");
        errorContainers[i].classList.remove("opacity-0");
    }
    setTimeout(()=>{
        //hide container
        for(let i=0;i < errorContainers.length ; i++)
    {
        errorContainers[i].classList.remove("opacity-100");
        errorContainers[i].classList.add("opacity-0");
    }
    },3000);
</script>';

unset($_SESSION['msg']);
unset($_SESSION['color']);
}