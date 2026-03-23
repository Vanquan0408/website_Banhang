<div id="main">
    <div class="maincontent">
        <?php
       if(isset($_GET['quanly'])){
        $tam=$_GET['quanly'];
       }else{
        $tam='';
       }
       if($tam=='danhmucsanpham'){
        include('main/danhmuc.php');
       }elseif ($tam =='giohang'){
        include('main/giohang.php');
       } elseif ($tam=='tintuc'){
        include('main/tintuc.php');
       } elseif ($tam=='lienhe'){
        include('main/lienhe.php');
       }elseif ($tam=='sanpham'){
         include('main/sanpham.php');
      }elseif ($tam=='dangky'){
         include('main/dangky.php');
          // 'thanhtoan' page removed; ordering now handled inside giohang.php
      }elseif ($tam=='dangnhap'){
         include('main/dangnhap.php');
      }elseif ($tam=='timkiem'){
         include('main/timkiem.php');
      }elseif ($tam=='thaydoimatkhau'){
         include('main/thaydoimatkhau.php');
         } elseif ($tam=='thaydoidiachi'){
             include('main/thaydoidiachi.php');
      }else{
        include('main/index.php');
       }
       ?>
    </div>
</div>