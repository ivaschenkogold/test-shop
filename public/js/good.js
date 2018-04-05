$(document).ready(function () {
    $(".show_delete_good_modal").click(function () {
        var id = $(this).data('id');
        $("#delete_good_id").val(id);
        $("#delete_good_modal").modal('show');
    });

    if ($("ol").is('.sortgood')) {
        startImagesSort();
        updateGoodImagesOrder();
    }

    $("select[name=category]").change(function () {
        var token = $("meta[name=csrf-token]").attr('content');
        var category = $("select[name=category]").val();
        var good = '';
        if($("input").is("[name=good_id]")){
            good = $("input[name=good_id]").val();
        }

        $.ajax({
            url: '/admin/good/create_edit-filter',
            type: 'post',
            dataType: 'json',
            data: {
                _token: token,
                category: category,
                good: good
            }
        }).done(function (data) {
            $(".filters").html(data.filters);
        });
    });

});

function showPreview(el) {
    input = el[0];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var id = el.data('id');
        reader.onload = function (e) {
            var html = '<li id="list_' + id + '">';
            html += '<div class="row">' +
                '<span class="col-md-4">' +
                '<img src="' + e.target.result + '"' +
                '</span>' +
                '<span class="col-md-6">' +
                '<label for="">Alt:</label>' +
                '<input type="text" class="form-control" name="alt[' + id + ']">' +
                '</span>' +
                '<span class="col-md-2">' +
                '<a class="btn btn-danger" data-id="'+id+'" onclick="deleteImage($(this))">Удалить</a>' +
                '</span>' +
                '</div>';
            html += "</li>";
            $(".sortgood").append(html);
            startImagesSort();
            updateGoodImagesOrder();
            var next_id = parseInt(id + 1);
            var field_html = '<input type="file" class="image-upload-field" name="image[' + next_id + ']" data-id="' + next_id + '" value="" onchange="showPreview($(this))">';
            $(".image-upload-field").attr('hidden', true);
            $(".image-upload-fields").append(field_html);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function startImagesSort() {
    ns = $('ol.sortgood').nestedSortable({
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
        isTree: false,
        maxLevels: 1,
        expandOnHover: 700,
        startCollapsed: false,
        update: function () {
            //При изменении выполняем функию обновления дерева
            updateGoodImagesOrder();
        }
    });
}

function updateGoodImagesOrder() {
    var tree = $('ol.sortgood').nestedSortable('toArray', {startDepthCount: 0});
    var order = new Array();
    for(var i = 0; i < tree.length; i++) {
        order.push(tree[i].item_id);
    }

    $("#images_order").val(JSON.stringify(order));
}

function deleteImage(el) {
    var id = el.data('id');
    $("#list_" + id).remove();
    updateGoodImagesOrder();
}