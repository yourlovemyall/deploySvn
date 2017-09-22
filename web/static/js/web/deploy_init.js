 function init_jfz_deploy_table()
 {
     $("#deploy-table").dataTable({
         "order": [[1, "desc"]],
         "columns": deploy_table_header,
         "ajax": {
             "url": "out/deploy_info.php?opt=get_deploy_table_info&bu_id=1",
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
                 $('#com-chart').data('key', table_header[$(this, row).index()].data);
                 $('#com-chart').data('host', data.host);
                 update_com_chart();
             });
         }
     });
 }
