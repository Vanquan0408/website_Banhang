<div id="main">
    <?php
   // không hiển thị sidebar khi chỉnh sửa địa chỉ (tránh danh mục bên trái)

if (
    !isset($_GET['quanly']) ||
    !in_array($_GET['quanly'], [
        'thaydoidiachi',
        'dangky',
        'tintuc',
        'lienhe',
        'thaydoimatkhau',
        'dangnhap'
    ])
) {
    include("sidebar/sidebar.php");
}
    ?>
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