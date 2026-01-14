$(document).ready(function () {
    //menu
    $(".ItemOder").hide();
    $(".cateOder").click(function (e) {
      e.preventDefault();
      $(this).next().slideDown();
    });
    $(".ItemOder").mouseleave(function () {
      $(this).slideUp();
    });
  //     form
  // $("#formreg").submit(function () {
  //   var username = $("input[name*='username']").val();
  //   if (username.length === 0 || username.length < 6) {
  //     $("input[name*='username']").focus();
  //     $("#noteForm").html("Username chưa hợp lệ!");
  //     return false;
  //   }
  //   var password = $("input[name*='password']").val();
  //   if (password.length === 0 || password.length < 6) {
  //     $("input[name*='password']").focus();
  //     $("#noteForm").html("Password chưa hợp lệ!");
  //     return false;
  //   }
  //   var hoten = $("input[name*='hoten']").val();
  //   if (hoten.length === 0 || hoten.length < 6) {
  //     $("input[name*='hoten']").focus();
  //     $("#noteForm").html("Họ tên chưa hợp lệ!");
  //     return false;
  //   }
  //   var ngaysinh = $("input[name*='ngaysinh']").val();
  //   if (ngaysinh.length === 0) {
  //     $("input[name*='ngaysinh']").focus();
  //     $("#noteForm").html("Ngày sinh chưa hợp lệ!");
  //     return false;
  //   }
  //   var diachi = $("input[name*='diachi']").val();
  //   if (diachi.length === 0) {
  //     $("input[name*='diachi']").focus();
  //     $("#noteForm").html("Địa chỉ chưa hợp lệ!");
  //     return false;
  //   }
  //   var dienthoai = $("input[name*='dienthoai']").val();
  //   if (dienthoai.length === 0) {
  //     $("input[name*='dienthoai']").focus();
  //     $("#noteForm").html("Điện thoại chưa hợp lệ!");
  //     return false;
  //   }
  //   return true;
  // });
  // update loại hàng dùng ajax + new layer
  // phải đóng trước thì mới có cái để mở lên 
  $("#w_update").hide();
  $(".w_update_btn_open").click(function (e){
    e.preventDefault();
    // lấy tọa độ vào super paramater e 
    // alert(e.pageX + '--' + e.pageY);
    // gán cho cửa sổ div thông quan css của nó 
    $("#w_update").css("left",e.pageX+5);
    $("#w_update").css("top",e.pageY+5);
    // lấy dữ liêuj cho thuộc tính value 
    var $idloaihang = $(this).attr('value');
    // sử dụng hàm load() của ajax để gọi trang update vào trong cửa sổ div
    //load post dữ liệu gửi dạng post . load get thì kiểu gởi get
    $("#w_update_form").load("./element_VQ/mLoaihang/loaihangUpdate.php",{idloaihang:$idloaihang},function (response,status,request){
      this ;

    });
    $("#w_update").show();
  });
  // xử lý đóng 
  $("#w_close_btn").click(function (e){
    e.preventDefault();
  $("#w_update").hide();
  });
  // update loại hàng dùng ajax + new layer
  // phải đóng trước thì mới có cái để mở lên 
  $("#w_update_hh").hide();
  $(".w_update_btn_open_hh").click(function (e){
    e.preventDefault();
    // lấy tọa độ vào super paramater e 
    // alert(e.pageX + '--' + e.pageY);
    // gán cho cửa sổ div thông quan css của nó 
    $("#w_update_hh").css("left",e.pageX+5);
    $("#w_update_hh").css("top",e.pageY+5);
    // lấy dữ liêuj cho thuộc tính value 
    var $idhanghoa = $(this).attr('value');
    // sử dụng hàm load() của ajax để gọi trang update vào trong cửa sổ div
    //load post dữ liệu gửi dạng post . load get thì kiểu gởi get
    $("#w_update_form_hh").load("./element_VQ/mHanghoa/hanghoaUpdate.php",{idhanghoa:$idhanghoa},function (response,status,request){
      this ;

    });
    $("#w_update_hh").show();
  });
  // xử lý đóng 
  $("#w_close_btn_hh").click(function (e){
    e.preventDefault();
  $("#w_update_hh").hide();
  });
  

});