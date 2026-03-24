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

  // UI: toggle show/hide password (used on signup/login pages)
  $(document).on('click', '.js-toggle-password', function () {
    var targetId = $(this).attr('data-target');
    if (!targetId) return;

    var $input = $('#' + targetId);
    if ($input.length === 0) return;

    var isPassword = $input.attr('type') === 'password';
    $input.attr('type', isPassword ? 'text' : 'password');
    $(this).toggleClass('is-on', isPassword);
    $(this).attr('aria-pressed', isPassword ? 'true' : 'false');
  });

  // UI: user dropdown menu in header
  function closeUserMenus() {
    $('.user-menu-wrap.is-open').removeClass('is-open');
    $('.js-user-menu-toggle[aria-expanded="true"]').attr('aria-expanded', 'false');
  }

  $(document).on('click', '.js-user-menu-toggle', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $wrap = $(this).closest('.user-menu-wrap');
    var isOpen = $wrap.hasClass('is-open');

    closeUserMenus();
    if (!isOpen) {
      $wrap.addClass('is-open');
      $(this).attr('aria-expanded', 'true');
    }
  });

  $(document).on('click', function () {
    closeUserMenus();
  });

  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
      closeUserMenus();
    }
  });

  function showToast(message, isError) {
    var $toast = $('#site-toast');
    if ($toast.length === 0) {
      $toast = $('<div id="site-toast" class="site-toast" role="status" aria-live="polite"></div>');
      $('body').append($toast);
    }

    $toast.removeClass('is-error is-success is-show');
    $toast.addClass(isError ? 'is-error' : 'is-success');
    $toast.text(message || '');

    // force reflow so animation re-triggers
    void $toast[0].offsetWidth;
    $toast.addClass('is-show');

    clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(function () {
      $toast.removeClass('is-show');
    }, 2200);
  }

  // AJAX: add to cart from product list (icon button)
  $(document).on('submit', '.js-add-to-cart-form', function (e) {
    e.preventDefault();
    var $form = $(this);
    var url = $form.attr('action') || 'pages/main/themgiohang.php';

    $.ajax({
      url: url + '?ajax=1',
      method: 'POST',
      data: $form.serialize(),
      dataType: 'json'
    })
      .done(function (res) {
        if (res && res.ok) {
          showToast(res.message || 'Thêm vào giỏ hàng thành công.');
          return;
        }
        if (res && res.redirect) {
          window.location.href = res.redirect;
          return;
        }
        showToast((res && res.message) ? res.message : 'Không thể thêm vào giỏ hàng.', true);
      })
      .fail(function (xhr) {
        // jQuery treats non-2xx (401/404/500) as fail even if server returns JSON.
        var res = null;
        if (xhr && xhr.responseJSON) {
          res = xhr.responseJSON;
        } else if (xhr && typeof xhr.responseText === 'string' && xhr.responseText.trim() !== '') {
          try {
            res = JSON.parse(xhr.responseText);
          } catch (e) {
            res = null;
          }
        }

        if (res && res.redirect) {
          window.location.href = res.redirect;
          return;
        }

        if (res && res.message) {
          showToast(res.message, true);
          return;
        }

        showToast('Có lỗi xảy ra, vui lòng thử lại.', true);
      });
  });
  

});