 function update_server_chart()
 {
     $("#server-com-form").show();
     if($('#server-com-chart').data('init') != 'true')
     {
         $('#server-com-chart').data('init', 'true');
         init_server_chart();
     }
     var date_com1 = $("#server-com-date1").val();
     var date_com2 = $("#server-com-date2").val();
     var com_chart = $("#server-com-chart").highcharts();
     var url_com1 = "out/server_info.php?opt=server_data&begin_date=" + date_com1 + "&key=" + $("#server-com-chart").data('key') + "&sip=" + $("#server-com-chart").data("sip");
     var url_com2 = "out/server_info.php?opt=server_data&begin_date=" + date_com2 + "&key=" + $("#server-com-chart").data('key') + "&sip=" + $("#server-com-chart").data("sip");

     var title = '';
     for(i in table_header)
     {
         if(table_header[i].data == $("#server-com-chart").data('key'))
         {
             title = '[' + $("#server-com-chart").data('sip') + ']-' + table_header[i].title;
             break;
         }
     }
     com_chart.setTitle({"text": title});

     if(url_com1 != $("#server-com-chart").data('url1'))
     {
         $("#server-com-chart").data("url1", url_com1);
         com_chart.showLoading();
         $.getJSON(url_com1, function(json){
             if(json.ret == 0)
             {
                 com_chart.series[0].update({
                     name: date_com1,
                     data: json.data[$("#server-com-chart").data('key')]
                 });
             }
             com_chart.hideLoading();
         });
     }
     if(url_com2 != $("#server-com-chart").data('url2'))
     {
         $("#server-com-chart").data("url2", url_com2);
         com_chart.showLoading();
         $.getJSON(url_com2, function(json){
             if(json.ret == 0)
             {
                 com_chart.series[1].update({
                     name: date_com2,
                     data: json.data[$("#server-com-chart").data('key')]
                 });
             }
             com_chart.hideLoading();
         });
     }
 }
 function init_server_table()
 {
     $("#server-real-data").dataTable({
         "order": [[1, "desc"]],
         "columns": server_table_header,
         "ajax": {
             "url": "out/server_info.php?opt=server_real_data",
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
                     for(k in server_ip_list)
                     {
                         if(data[i].sip == server_ip_list[k].ip)
                         {
                             data[i].sip = data[i].sip + '(' + server_ip_list[k].name + ')';
                             break;
                         }
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
                 $('#server-com-chart').data('key', table_header[$(this, row).index()].data);
                 var i =  data.sip.indexOf('(');
                 var sip = data.sip;
                 if(i > 0)
                 {
                     sip = sip.substr(0, i);
                 }
                 $('#server-com-chart').data('sip', sip);
                 update_server_chart();
             });
         }
     });
     $("#server-com-form").hide();
     $('#server-com-date1,#server-com-date2').change(function(){
         update_server_chart();
     });
 }
 function init_server_chart()
 {
     $("#server-com-chart").highcharts({
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
 }
