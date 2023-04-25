

$(document).ready(function (event) {

  $("#permission-all").on("click", function (e) {
    const target = e.target;
    if (target.checked) {
      $(target).next().html("Deselect All Permissions")
    } else {
      $(target).next().html("Select All Permissions")
    }
    $(".check-permission").prop("checked", target.checked);
    $(".check-permission").trigger("change");
  });

  $(".check-permission").on("change", function (e) {
    const target = e.target;
    $(target).next().html(target.checked ? "Enabled" : "Disabled");
  })

  $.ajax({})



  $("#permissions-form").on("submit", function (e) {
    e.preventDefault();

    Swal.fire({
      title: "Save Changes",
      text: "Are you sure you want to save your changes",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      showCancelButton: true
    })
  })

  $("#delete-role").on("click", function (e) {
    e.preventDefault();

    const target = e.target;

    Swal.fire({
      title: "Delete Role",
      text: "Are you sure you want to save your changes",
      confirmButtonText: "Yes",
      cancelButtonText: "Cancel",
      icon: 'warning',
      showCancelButton: true
    })
      .then(function (result) {

        if (result.isConfirmed) {
          $.ajax({
            type: "post",
            url: "/academy/admin/user/delete_role",
            data: {
              role: $(target).data("role")
            },
            success: function (res) {
              console.log(res)
            }
          })
        }
      })
  })

})
