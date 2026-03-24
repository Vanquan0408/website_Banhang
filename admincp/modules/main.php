<div class="clear"></div>
<div class="main">
<?php
       if(isset($_GET['action']) && $_GET['query']){
        $tam=$_GET['action'];
        $query=$_GET['query'];
       }else{
        $tam='';
        $query='';
       }
       if($tam=='quanlydanhmucsanpham' && $query=='them'){
        echo '<div class="admin-two-col">';
        echo '  <section class="admin-panel admin-panel--form">';
        include('modules/quanlydanhmucsp/them.php');
        echo '  </section>';
        echo '  <section class="admin-panel admin-panel--list">';
        include('modules/quanlydanhmucsp/lietke.php');
        echo '  </section>';
        echo '</div>';
       }elseif($tam =='quanlydanhmucsanpham' && $query=='sua'){
              include('modules/quanlydanhmucsp/sua.php');
       }elseif($tam =='quanlysp' && $query=='them'){
              echo '<section class="admin-panel admin-panel--page admin-product-page">';
              include('modules/quanlysp/them.php');
              echo '<div class="admin-divider" role="separator" aria-hidden="true"></div>';
              include('modules/quanlysp/lietke.php');
              echo '</section>';
       }elseif($tam =='quanlysp' && $query=='sua'){
              include('modules/quanlysp/sua.php');
       }elseif($tam =='quanlydonhang' && $query=='lietke'){
              echo '<section class="admin-panel admin-panel--page">';
              include('modules/quanlydonhang/lietke.php');
              echo '</section>';
       }elseif($tam =='donhang' && $query=='xemdonhang'){
              include('modules/quanlydonhang/xemdonhang.php');
       }elseif($tam =='taikhoan' && $query=='thongtin'){
              echo '<section class="admin-panel admin-panel--page">';
              include('modules/taikhoan/thongtin.php');
              echo '</section>';
       }else{
              include('modules/dashboard.php');
       }
       ?>
</div>