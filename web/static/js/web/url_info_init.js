var url_table_init = false;
var table;
function init_url_table()
{
    update_category1_list();
    $("#url-table-date").change(function(){
       update_category1_list();
    });
    $("#category1").change(function(){
        update_category2_list($(this).val());
    });
    $("#category2").change(function(){
        update_url_table($("#category1").val(), $(this).val());
    });
}
function update_category1_list()
{
    var date = $('#url-table-date').val();
    var category1_url = "out/url_info.php?opt=get_category1_list&date=" + date;
    $.getJSON(category1_url, function(json){
        if(json.ret == 0)
        {
            if(json.data.length == 0)
            {
                update_category2_list(false);
                $("#category1").html('');
                return;
            }
            else
            {
                update_category2_list(json.data[0].category1);
            }
            $("#category1").html('');
            var i;
            for(i in json.data)
            {
                var option = "<option value='" + json.data[i].category1 + "'>" + json.data[i].category1 + "(" + json.data[i].num + ")</option>";
                $("#category1").append(option);
            }
        }
    });
}
function update_category2_list(category1)
{
    $("#category2").html('');
    if(category1 == false)
    {
        return;
    }
    var option = "<option value='*'>*</option>";
    $("#category2").append(option);
    var date = $('#url-table-date').val();
    var category2_url = "out/url_info.php?opt=get_category2_list&date=" + date + "&category1=" + category1;
    $.getJSON(category2_url, function(json){
        if(json.ret == 0)
        {
            var i;
            for(i in json.data)
            {
                var option = "<option value='" + json.data[i] + "'>" + json.data[i] + "</option>";
                $("#category2").append(option);
            }

        }

    });
    update_url_table(category1, '*');
}

function update_url_table(category1, category2)
{
    var date = $('#url-table-date').val();
    var table_data_url = "out/url_info.php?opt=get_url_data&date=" + date + "&category1=" + category1 + "&category2=" + category2;
    if(!url_table_init)
    {
        url_table_init = true;
        table = $("#url-table").dataTable({
            "order": [
                [1, "desc"],
                [2, "desc"],
                [5, "desc"]
            ],
            "columns": url_table_header,
            "ajax": {
                "url": table_data_url,
                "dataSrc": function(json){
                    //单位转换
                    var i;
                    var data = json.data;
                    for(i in data)
                    {
                        //To Kb/s
                        data[i].flux = data[i].flux / 1024 / 300 * 8;
                        data[i].flux = data[i].flux.toFixed(2);
                        //To ms/r
                        var request = parseInt(data[i]['200_code']);
                        if(request == 0)
                        {
                            data[i].request_time = 0;
                        }
                        else
                        {
                            data[i].request_time = Math.round(data[i].request_time / request);
                        }
                    }
                    return data;
                }
            }
        });
    }
    else
    {
        table.api().ajax.url(table_data_url).load();
    }
}
