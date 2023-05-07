$(".password-visibility-btn").on('click', function (event) {
  let target = $(".password-visibility-btn");
  const input = $(target).prev();

  const current_type = $(input).attr('type');
  if (current_type === "password") {
    $(target).html(`<i data-feather="eye-off"></i>`);
    $(input).attr("type", "text");
  } else {
    $(target).html(`<i data-feather="eye"></i>`);
    $(input).attr("type", "password");
  }


  feather.replace();
})