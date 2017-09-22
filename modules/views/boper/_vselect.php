<div class="modal fade" id="version-selector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">版本列表</h4>
            </div>
            <input type="text" name="servers" id="servers-select" type="hidden"/>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-responsive" id="version-list-table"></table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary" id="publish-servers" >发布</button>-->
                <button type="button" class="btn btn-primary" id="back-servers" >发布</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="img-run-dialog-vs" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-img"  style="width: 216px; height:15px; margin: 129px auto">
        <img src='/Images/run_01.gif'alt='onloading'/>
    </div>
</div>
<script>
   

$('#version-list-table').dataTable({
        "columns":
        [
            {"title": "版本号", "data": "pv_v",type:"num"},
            {"title": "mtime", "data": "pv_mtime"},
            {"title": "上传时间", "data": "pv_create_time"},
            {"title": "上传人", "data": "pv_creater"},
            {"title": "说明", "data": "pv_explain"},
            {"title": "选择", "data": "radio"}
        ],
        "order": [[0, "desc"]],
        "ajax": {
            "url": "<?= Yii::$app->urlManager->createUrl(array('admin/boper/jsonversion', "id" => $id)) ?>",
            "dataSrc": function(json){
                if(json.ret != 1)
                {
                    return Array();
                }
                var data = json.data;
                var i;
                for(i in data)
                {
                    data[i]['radio'] = '<div class="radio">'
                        + '<input type="radio" name="version-select-radio" value="' + data[i]['pv_v'] + '">'
                        + '</div>';
                    data[i].pv_explain = '<a class="btn btn-default btn-xs" data-toggle="popover" data-placement="top" data-content="<pre>' + data[i].pv_explain + '</pre>">显示</a>';
                }
                return data;
            }
        },
        "drawCallback": function()
        {
            $('a[data-toggle=popover]').popover({html: true});
        },
        
        
        
        
    });
    $('#publish-servers').click(function(){
            var serids = $("#servers-select").val();
            var radio = $(".radio input[type='radio']:checked").val();
            if (radio ==null) {
                return false;
            }
            $("#version-selector").modal('hide');
            $("#img-run-dialog-vs").modal("show");
            $.ajax({
                url:"<?= Yii::$app->urlManager->createUrl(array('admin/boper/publishvn', "id" => $id)) ?>",
                data:{v:radio,serid:serids},
                type:"post",
                dataType:"json",
                success: function (data){
                    $("#img-run-dialog-vs").modal("hide");
                    if (data.ret !=1) {
                        alert(data.msg);
                        return ;
                    }
                    alert(data.msg);
                    location.reload();
                }
            });
        });
        
        $('#back-servers').click(function(){
            var serids = $("#servers-select").val();
            var radio = $(".radio input[type='radio']:checked").val();
            if (radio ==null) {
                return false;
            }
            $("#version-selector").modal('hide');
            $("#img-run-dialog-vs").modal("show");
            $.ajax({
                url:"<?= Yii::$app->urlManager->createUrl(array('admin/boper/backvn', "id" => $id)) ?>",
                data:{v:radio,serid:serids},
                type:"post",
                dataType:"json",
                success: function (data){
                    $("#img-run-dialog-vs").modal("hide");
                    if (data.ret !=1) {
                        alert(data.msg);
                        return ;
                    }
                    alert(data.msg);
                    location.reload();
                }
            });
        });
    
    
</script>
