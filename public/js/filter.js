$(document).ready(function () {
    $("#add_parameter").click(function () {
        var name = $("#add_parameter_name").val().trim();
        var error = 0;
        var error_text = "";

        if (name.length == 0) {
            error = 1;
            error_text = "Введите название параметра";
        } else if (name.length > 255) {
            error = 1;
            error_text = "Названее параметра не божет быть длиннее 255 символов";
        }

        if (error) {
            $(".add_parameter_name").addClass("has-error").find(".help-block").removeAttr('hidden').find('strong').html(error_text);
        } else {
            $(".add_parameter_name").removeClass("has-error").find('.help-block').attr('hidden', true);
            var count = 0;
            $("ol.sortparameter").find("li").each(function () {
                count++;
            });
            count++;

            $("#add_parameter_name").val("");

            var html = '<li id="list_' + count + '" class="parameter" data-id="' + count + '">' +
                '<div class="row">' +
                '<span class="col-md-10">' +
                '<input type="text" class="form-control" ' +
                'name="parameter[' + count + ']" value="' + name + '">' +
                '</span>' +
                '<span class="col-md-2">' +
                '<a class="btn btn-danger remove-parameter" data-id="' + count + '">Удалить</a>' +
                '</span>' +
                '</div>' +
                '</li>';
            $("ol.sortparameter").append(html);
            startParameterSort();
            updateParametersOrder();
        }
    });

    if ($('ol').is(".sortparameter")) {
        startParameterSort();
        updateParametersOrder();
    }

    if ($('ol').is(".sortfilter")) {
        startFilterSort();
        //updateFiltersOrder();
    }

    $(".show_delete_filter_modal").click(function () {
        var id = $(this).data('id');
        $("#delete_filter_id").val(id);
        $("#delete_filter_modal").modal('show');
    });
});

function startParameterSort() {
    ns = $('ol.sortparameter').nestedSortable({
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
            updateParametersOrder();
        }
    });
}

function updateParametersOrder() {
    var tree = $('ol.sortparameter').nestedSortable('toArray', {startDepthCount: 0});
    var order = new Array();
    for (var i = 0; i < tree.length; i++) {
        order.push(tree[i].item_id);
    }

    $("#parameters_order").val(JSON.stringify(order));
}

function deleteParameter(el) {
    var id = el.data('id');
    $(".parameter[data-id='"+id+"']").remove();
    startParameterSort();
    updateParametersOrder();
}

function startFilterSort() {
    ns = $('ol.sortfilter').nestedSortable({
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
            updateFiltersOrder();
        }
    });
}

function updateFiltersOrder() {
    var token = $("meta[name=csrf-token]").attr('content');
    var order = $('ol.sortfilter').nestedSortable('toArray', {startDepthCount: 0});
    /*var order = new Array();
    for (var i = 0; i < tree.length; i++) {
        order.push(tree[i].item_id);
    }*/

    $.ajax({
        url: '/admin/filter/category-update',
        type: 'post',
        dataType: 'json',
        data: {
            _token: token,
            order: order
        }
    })
}