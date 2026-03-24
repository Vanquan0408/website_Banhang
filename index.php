<?php
ob_start();
session_start();
include("admincp/config/config.php");

// Frontend logout must happen BEFORE rendering header/menu
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    unset($_SESSION['dangky']);
    unset($_SESSION['id_khachhang']);

    // Redirect to remove the query string and prevent partial UI render
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=20260324">
    <script type="text/javascript" src="js_VQ/jquery-3.7.1.js?v=20260324"></script>
    <script type="text/javascript" src="js_VQ/jscript.js?v=20260324"></script>
    <title>UI | Website Bán Hàng </title>
</head>
<body>
   <div class="wrapper"> 
    <?php
    include("pages/header.php");
    include("pages/menu.php");
    include("pages/main.php");
    include("pages/footer.php");
    ?>
   
   
   
   </div>
</body>
</html>