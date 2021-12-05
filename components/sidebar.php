<?php
$profile_el = "U";
if(isset($_SESSION['user']))
{
    $profile_el = $_SESSION['user']['username'][0];
}
?>
<sidebar class="sidebar">
    <!--logo-->
    <img src="assets/images/logo.png" class="hidden sm:block sm:mx-auto mb-20 sm:w-8 " alt="zen-chat logo" >
    
    <!--link-->
    <a href="index.php" class="sidebar-link">
        <img src="assets/icons/home.svg" class="sidebar-link-icon " alt="home logo svg" >
    </a>

    <!--link-->
    <a href="settings.php" class="sidebar-link">
        <img src="assets/icons/settings.svg" class="sidebar-link-icon " alt="settings icon svg" >
    </a>

    <!--link-->
    <a href="contact.php" class="sidebar-link">
        <img src="assets/icons/contact.svg" class="sidebar-link-icon " alt="contact icon svg" >
    </a>

    <!--link-->
    <a href="about.php" class="sidebar-link">
        <img src="assets/icons/about.svg" class="sidebar-link-icon " alt="about icon svg" >
    </a>

    <!--button-->
    <!-- <button   class=" sidebar-btn">
        <img src="assets/icons/dark.svg" class="sidebar-button-icon" alt="dark mode icon svg" >
    </button> -->

    <!--link-->
    <a href="logout.php"  class=" sidebar-link">
        <img src="assets/icons/logout.svg" class="sidebar-link-icon" alt="logout icon svg" >
    </a>

    <!--link-->
    <a href="profile.php" class="profile-box">
        <?php echo $profile_el; ?>
    </a>

</sidebar>
</template>


