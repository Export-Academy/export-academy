

$(document).ready(function () {


  // Select and deselect all permissions whenever clicked 
  $("#permission-all").on("click", function (e) {
    const target = e.target;

    // If checked will update all check boxes accordingly
    if (target.checked) {
      $(target).next().html("Deselect All Permissions")
    } else {
      $(target).next().html("Select All Permissions")
    }


    $(".check-permission").prop("checked", target.checked);
    $(".check-permission").trigger("change");
  });

  // Update Checkbox label for each checkboxes
  $(".check-permission").on("change", function (e) {

    const target = e.target;
    $(target).next().html(target.checked ? "Enabled" : "Disabled");


  });


  $("#permissions-form").on("submit", function (e, allowSubmit) {
    if (allowSubmit) {
      return;
    }
    e.preventDefault();

    const target = e.target;
    Swal.fire({
      title: `Update ${$(target).data("role")}`,
      text: "Are you sure you want to assign the selected permissions?",
      confirmButtonText: "Yes",
      cancelButtonText: "Cancel",
      showCancelButton: true
    }).then(function (result) {
      if (result.isConfirmed)
        $(target).trigger('submit', [true])
    })
  });


  $("#delete-role").on("click", function (e, allowSubmit) {
    const target = e.target;
    if (allowSubmit) {
      window.location.replace(`/academy/admin/user/delete_role?role=${$(target).data("id")}`);
      return;
    }

    Swal.fire({
      title: `Delete ${$(target).data("role")}`,
      html: "Are you sure you want to delete the <b>" + $(target).data("role") + "</b>",
      confirmButtonText: "Yes",
      cancelButtonText: "Cancel",
      showCancelButton: true
    }).then(function (result) {
      if (result.isConfirmed)
        $(target).trigger('click', [true])
    })

  })



})
