<p>
<div class="btn-group">
<!--    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#server-selector">添加设备</button>
  <button type="button" class="btn btn-danger" id="delete-server-btn">删除设备</button> -->
<span id="pbh_version"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#version-selector" id="publish-btn">版本发布</button>
</span>
    <!--<button type="button" class="btn btn-warning" id="unpublish-btn">版本下架</button>-->
</div>
</p>
<table class="table table-striped table-bordered table-responsive" id="deploy-table">
    
</table>

<script>
    function init_deploy_table()
    {
        var table = $("#deploy-table").DataTable({
            "columns": deploy_table_header,
            "order": [[3, "desc"]],
            "ajax": {
                "url": "<?= Yii::$app->urlManager->createUrl(array('admin/boper/jsondata', "id" => $id)) ?>",
                "dataSrc": function(json){
                    if(json.ret !== 1 )
                    {
                        return false;
                    }
                    var data = json.data;
                    console.log(data);
                    return data;
                }
            }
        });
        $('#deploy-table tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('info');
        } );
        $('#delete-server-btn').click(function(){
            var data = table.rows('.info').data();
            var length = data.length;
            if(length === 0)
            {
                alert('未选中任何服务器');
            }
            for(var i = 0; i < length; i++)
            {
                if(data[i].version != 0)
                {
                    alert('服务器【' + data[i].local_ip + '】需要先进行【版本下架】操作才可以删除设备');
                    return;
                }
            }
        });
        $('#publish-btn').click(function(){
            var data = table.rows('.info').data();
            var length = data.length;
            var serid  = '';
            if(length === 0)
            {
                alert('未选中任何服务器');
                return false;
            }
            
            for(var i = 0; i < length; i++)
            {
                serid += data[i]['si_id']+',';
                console.log(serid);
                console.log(data[i]);
            }
            $("#servers-select").val(serid.substring(0,serid.length-1));
        });
        
        $('#ver-back-btn').click(function(){
            var data = table.rows('.info').data();
            var length = data.length;
            var serid  = '';
            if(length === 0)
            {
                alert('未选中任何服务器');
                return false;
            }
            
            for(var i = 0; i < length; i++)
            {
                serid += data[i]['si_id']+',';
                console.log(serid);
                console.log(data[i]);
            }
            $("#servers-select").val(serid.substring(0,serid.length-1));
        });

        $('#unpublish-btn').click(function(){
            var data = table.rows('.info').data();
            var length = data.length;
            if(length === 0)
            {
                alert('未选中任何服务器');
            }
            for(var i = 0; i < length; i++)
            {
                console.log(data[i]);
            }
        })
    }
    function delete_servers()
    {
        var table = $("#deploy-table").dataTable();
        console.log(table.rows('.warning').data());
    }
    
    function publish_version () {
        $.ajax({
            url :"<?= Yii::$app->urlManager->createUrl(array('admin/boper/versionst', "id" => $id)) ?>",
            type:"post",
            dataType:"json",
            success: function (msg){
                if(msg.p_status==1) {
                    var btn_group = $("#pbh_version");
                    var htmlstr = "<button type='button' class='btn btn-warning' >版本发布中</button>";
                    btn_group.html("");
                    btn_group.html(htmlstr);
                    return;
                }
            }
        });
    }
    
    $(document).ready(function(){
        init_deploy_table();
        setInterval(publish_version,5000);
//        init_vcs_table();
    });
</script>