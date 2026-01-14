<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="css/style.css">
    <script type="text/javascript"src="js_VQ/jquery-3.7.1.js"> </script>
    <script type="text/javascript"src="js_VQ/jscript.js"></script>
    <title>Web bán hàng </title>
</head>
<body>
   <div class="wrapper"> 
    <?php
    session_start();
    include("admincp/config/config.php");
    include("pages/header.php");
    include("pages/menu.php");
    include("pages/main.php");
    include("pages/footer.php");
    ?>
   
   
   
   </div>
</body>
</html>