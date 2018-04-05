$(document).ready(function () {
    $(".show_delete_role_modal").click(function () {
        var id = $(this).data('id');
        var users = $(this).data('users');
        $("#delete_role_id").val(id);
        $("#move_users_to").find('option').prop('disabled', false);
        $("#move_users_to").find('option[value="' + id + '"]').prop('disabled', true);

        if(users > 0) {
            $(".new-user-role").removeAttr("hidden");
            $(".users-in-role-count").html(users);
            var count = 0;
            $("#move_users_to").find("option").each(function () {
                count++;
            });

            if(count <= 2){
                $(".move-users-to").addClass('has-error').find(".help-block").removeAttr('hidden');
            }
        }
        $("#delete_role_modal").modal('show');
    });
});