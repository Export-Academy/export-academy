$(".password-visibility-btn").on('click', function (event) {
  const target = event.target;
  const input = $(target).prev();

  const current_type = $(input).attr('type');
  if (current_type === "password") {
    $(target).html("Hide");
    $(input).attr("type", "text");
  } else {
    $(target).html("Show");
    $(input).attr("type", "password");
  }
})