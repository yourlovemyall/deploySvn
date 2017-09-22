 function init_real_table()
 {
     $("#real-data").dataTable({
         "order": [[1, "desc"]],
         "columns": table_header,
         "ajax": {
             "url": "out/server_info.php?opt=bu_real_data",
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
     init_com_chart();
 }
 function init_com_chart()
 {
     $("#com-chart").highcharts({
         xAxis: {
             type: 'datetime',
             dateTimeLabelFormats: {
                 minute: "%H:%M",
                 day: "%H:%M"
             }
         },
         plotOptions: {
             series: {
                 marker: {
                     radius: 1
                 },
                 pointStart: 0,
                 pointInterval: 300 * 1000 // one day
             }
         },
         tooltip: {
             xDateFormat: '%H:%M',
             shared: true
         },
         series: [{

         }, {

         }]

     });
     $('.input-group.date').datepicker({
         "format": "yyyy-mm-dd",
         "language": "zh-CN",
         "autoclose": true
     });
     $('#com1-date,#com2-date').change(function(){
         update_com_chart();
     });
     $("#com-form").hide();
 }
 function update_com_chart()
 {
     $("#com-form").show();
     var date_com1 = $("#com1-date").val();
     var date_com2 = $("#com2-date").val();
     var com_chart = $("#com-chart").highcharts();
     var url_com1 = "out/server_info.php?opt=domain_data&begin_date=" + date_com1 + "&key=" + $("#com-chart").data('key') + "&host=" + $("#com-chart").data("host");
     var url_com2 = "out/server_info.php?opt=domain_data&begin_date=" + date_com2 + "&key=" + $("#com-chart").data('key') + "&host=" + $("#com-chart").data("host");

     var title = '';
     for(i in table_header)
     {
         if(table_header[i].data == $("#com-chart").data('key'))
         {
             title = '[' + $("#com-chart").data('host') + ']-' + table_header[i].title;
             break;
         }
     }
     com_chart.setTitle({"text": title});

     if(url_com1 != $("#com-chart").data('url1'))
     {
         $("#com-chart").data("url1", url_com1);
         com_chart.showLoading();
         $.getJSON(url_com1, function(json){
             if(json.ret == 0)
             {
                 com_chart.series[0].update({
                     name: date_com1,
                     data:json.data[$("#com-chart").data('key')]
                 });
             }
             com_chart.hideLoading();
         });
     }
     if(url_com2 != $("#com-chart").data('url2'))
     {
         $("#com-chart").data("url2", url_com2);
         com_chart.showLoading();
         $.getJSON(url_com2, function(json){
             if(json.ret == 0)
             {
                 com_chart.series[1].update({
                     name: date_com2,
                     data:json.data[$("#com-chart").data('key')]
                 });
             }
             com_chart.hideLoading();
         });
     }
 }
