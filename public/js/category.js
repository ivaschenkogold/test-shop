$(document).ready(function () {
    ns = $('ol.sortable').nestedSortable({
        forcePlaceholderSize: true,
        handle: 'div',
        helper: 'original',
        items: 'li',
        cursor: 'move',
        opacity: .6,
        placeholder: 'placeholder',
        revert: 80,
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div',
        isTree: true,
        expandOnHover: 700,
        startCollapsed: false,
        update: function () {
            //При изменении выполняем функию обновления дерева
            update();
        }
    });

    $(".show_delete_category_modal").click(function () {
        var id = $(this).data('id');
        var token = $("meta[name=csrf-token]").attr('content');
        $("#delete_category_id").val(id);

        $.ajax({
            url: '/admin/category/check-delete',
            type: 'post',
            dataType: 'json',
            data: {
                _token: token,
                delete_category_id: id
            }
        }).done(function (data) {
            if (data.success == 1) {
                if (data.count > 0) {
                    $(".new-good-category").removeAttr('hidden');
                    $(".goods-in-category-count").html(data.count);
                    $("#move_goods_to").find("option").removeAttr('disabled');
                    $("#move_goods_to").find("option[value='" + id + "']").attr('disabled', true);
                    var count = 0;
                    $("#move_goods_to").find("option").each(function () {
                        count++;
                    });

                    if (count < 2) {
                        $(".move-goods-to").addClass("has-error").find('.help-block').removeAttr('hidden');
                    }
                }
            } else {
                window.location.reload();
            }
        });

        $("#delete_category_modal").modal('show');
    });
});

function update() {
    var token = $("meta[name=csrf-token]").attr('content');
    var tree = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});

    $.ajax({
        url: '/admin/category/rebuild-edit',
        type: 'post',
        dataType: 'json',
        data: {
            _token: token,
            tree: tree
        }
    }).done(function (data) {
        if (data.success == 1) {

        }
    });
}