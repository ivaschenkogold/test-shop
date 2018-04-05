$(document).ready(function () {
    $(".show_delete_user_modal").click(function () {
        var id = $(this).data('id');
        $("#delete_user_id").val(id);
        $("#delete_user_modal").modal('show');
    });
});