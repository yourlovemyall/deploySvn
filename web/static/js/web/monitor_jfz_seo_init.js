var seo_table_init = false;
var highcharts_options = {
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: {
            millisecond: '%H:%M:%S.%L',
            second: '%H:%M:%S',
            minute: '%H:%M',
            hour: ' ',
            day: '%Y-%m-%e',
            week: '%e. %b',
            month: '%b \'%y',
            year: '%Y'
        }
    },
    plotOptions: {
        series: {
            marker: {
                radius: 1
            }
        }
    },
    tooltip: {
        xDateFormat: '%b-%e'
    },
    series: [{}]
};
var table;
function init_monitor_jfz_seo()
{
    var category_url = "out/seo_info.php?opt=get_category_list";
    $.getJSON(category_url, function(json){
        if(json.ret == 0)
        {
            $("#category").html('');
            var option = "<option value='0'>其它</option>";
            $("#category").append(option);
            var i;
            for(i in json.data)
            {
                var option = "<option value='" + json.data[i].id + "'>" + json.data[i].name + "</option>";
                $("#category").append(option);
            }
            update_seo_table();
        }
    });
    $("#seo-table-date,#category").change(function(){
        update_seo_table();
    });
    update_seo_editor();
    $("#seo-chart-form").hide();
    $("#begin-date,#end-date").change(function(){
        update_seo_chart();
    });
}
function update_seo_chart()
{
    $("#seo-chart-form").show();
    if($('#jfz-seo-chart').data('init') != 'true')
    {
        $('#jfz-seo-chart').data('init', 'true');
        $("#jfz-seo-chart").highcharts(highcharts_options);
    }
    var seo_chart = $("#jfz-seo-chart").highcharts();
    var url = "out/seo_info.php?opt=get_seo_detail_data&"
        + "begin_date="
        + $("#begin-date").val()
        + "&end_date="
        + $("#end-date").val()
        + "&key="
        + $("#jfz-seo-chart").data('key')
        + "&url="
        + $("#jfz-seo-chart").data('url');
    seo_chart.showLoading();
    var i;
    var title;
    for(i in seo_table_header)
    {
        if(seo_table_header[i].data == $("#jfz-seo-chart").data('key'))
        {
            title = seo_table_header[i].title + "--[" + $("#jfz-seo-chart").data('url') +"]";
            break;
        }
    }
    seo_chart.setTitle({text: title});
    $.getJSON(url, function(json){
        if(json.ret == 0)
        {
            seo_chart.series[0].update({
                pointStart: Date.parse($("#begin-date").val()),
                pointInterval: 3600 * 24 * 1000,
                data: json.data
            });
        }
        seo_chart.hideLoading();
    });
}
function update_seo_editor()
{
    var seo_edit_url = "out/seo_info.php?opt=get_category_detail";
    $.getJSON(seo_edit_url, function(json){
        if(json.ret == 0)
        {

            $("#seo-edit-accordion").html('');
            var i;
            for(i in json.data)
            {
                add_accordion(json.data[i].id, json.data[i].name, json.data[i].content);
                if(i == 0)
                {
                    $("#classify" + json.data[i].id).addClass('in');
                }
            }
        }
    });
}
function add_accordion(id, name, content)
{
    var content = '<div class="panel panel-default" id="panel-' + id + '">'
        + '<div class="panel-heading">'
        +    '<h4 class="panel-title" id="panel-title-' + id + '">'
        +        '<a data-toggle="collapse" data-parent="#seo-edit-accordion" id="anchor-classify-' + id + '" href="#classify-' + id +'">'
        +        name
        +        '</a>'
        +        '<button class="btn btn-xs btn-danger pull-right" onclick="delete_edit_category(' + id + ')">删除<span class="glyphicon glyphicon-trash"></span></button>'
        +        '<button class="btn btn-xs pull-right" onclick="change_edit_category(' + id + ')">修改<span class="glyphicon glyphicon-edit"></span></button>'
        +    '</h4>'
        + '</div>'
        + '<div id="classify-' + id + '" class="panel-collapse collapse">'
        +    '<div class="panel-body">'
        +        '<textarea class="form-control" rows="' + content.split("\n").length + '" category-id="' + id +'">'
        +            content
        +        '</textarea>'
        +    '</div>'
        + '</div>'
        + '</div>';
    $("#seo-edit-accordion").append(content);
}
function save_edit_classify()
{
    var url = 'report/seo_info_update.php?opt=save_classify';
    var data = new Array();
    $("textarea[category-id]").each(function(i){
        var id = $(this).attr('category-id');
        data.push({"content": $(this).val(), "id": id});
    });
    $.post(
        url,
        {
            "data": data
        },
        function(json, status)
        {
            if(json.ret == 0)
            {
                alert("保存成功");
            }
        },
        'json'
    );
}
function add_edit_category()
{
    var url = 'report/seo_info_update.php?opt=add_category';
    $.post(
        url,
        {
            name: '新建分类'
        }
        ,
        function(json, status)
        {
            if(json.ret == 0)
            {
                add_accordion(json.id, '新建分类', '');
                change_edit_category(json.id);
            }
        },
        'json'
    );
}
function delete_edit_category(id)
{
    var result = confirm('确认删除?');
    if(result)
    {
        var url = 'report/seo_info_update.php?opt=delete_category';
        $.post(
            url,
            {
                id: id
            }
            ,
            function(json, status)
            {
                if(json.ret == 0)
                {
                    $("#panel-" + id).remove();
                }
                else
                {
                    var alert_div = 'alert-danger';
                    var alert_content = '删除失败';
                    var html = '<div class="alert ' + alert_div + ' alert-dismissible" role="alert">'
                        + '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'
                        + alert_content
                        + '</div>';
                    $("#panel-title-" + id).prepend(html);
                    setTimeout("$(\"button[data-dismiss='alert']\").click();", 3000);
                }
            },
            'json'
        );
    }
}
function change_edit_category(id)
{
    var text = $('#anchor-classify-' + id).text();
    var html = '<input type="text" value="' + text + '" id="input-classify-' + id + '"></input>';
    $('#anchor-classify-' + id).html(html);
    $('#input-classify-' + id).focus();
    $('#input-classify-' + id).blur(function(){
        var value = $(this).val();
        if(value != text)
        {
            var url = 'report/seo_info_update.php?opt=update_category';
            $.post(
                url,
                {
                    name: value,
                    id: id
                }
                ,
                function(json, status)
                {
                    var alert_title = "#panel-title-" + id;
                    var alert_div;
                    var alert_content;
                    if(json.ret == 0)
                    {
                        alert_div = 'alert-success';
                        alert_content = '更新成功';
                    }
                    else
                    {
                        alert_div = 'alert-danger';
                        alert_content = '更新失败';
                    }
                    var html = '<div class="alert ' + alert_div + ' alert-dismissible" role="alert">'
                        + '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'
                        + alert_content
                        + '</div>';
                    $("#panel-title-" + id).prepend(html);
                    setTimeout("$(\"button[data-dismiss='alert']\").click();", 3000);
                },
                'json'
            );

        }
        $('#anchor-classify-' + id).html(value);
    });
}
function update_seo_table()
{
    var date = $('#seo-table-date').val();
    var category = $("#category").val();
    var table_data_url = "out/seo_info.php?opt=get_seo_table_data&date=" + date + "&category=" + category;
    if(!seo_table_init)
    {
        seo_table_init = true;
        table = $("#jfz-seo-table").dataTable({
            "order": [[3, "desc"]],
            "columns": seo_table_header,
            "ajax": {
                "url": table_data_url,
                "dataSrc": function(json){
                    var data = json.data;
                    return data;
                }
            },
            "rowCallback": function(row, data) {
                $('td', row).on('dblclick', function()
                {
                    if($(this, row).index() == 0)
                    {
                        return;
                    }
                    //$('#com-chart').data('key', table_header[$(this).children('div').attr('key'));
                    $('#jfz-seo-chart').data('key', seo_table_header[$(this, row).index()].data);
                    $('#jfz-seo-chart').data('url', data.url_rule);
                    update_seo_chart();
                });
            }
        });
    }
    else
    {
        table.api().ajax.url(table_data_url).load();
    }
}
