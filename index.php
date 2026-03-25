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
    <?php
        $cssVer = @filemtime(__DIR__ . '/css/style.css') ?: '20260325';
        $jqVer = @filemtime(__DIR__ . '/js_VQ/jquery-3.7.1.js') ?: '20260325';
        $jsVer = @filemtime(__DIR__ . '/js_VQ/jscript.js') ?: '20260325';
    ?>
    <link rel="stylesheet" href="css/style.css?v=<?php echo urlencode((string)$cssVer); ?>">
    <script type="text/javascript" src="js_VQ/jquery-3.7.1.js?v=<?php echo urlencode((string)$jqVer); ?>"></script>
    <script type="text/javascript" src="js_VQ/jscript.js?v=<?php echo urlencode((string)$jsVer); ?>"></script>
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