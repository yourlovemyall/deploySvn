<p>

</p>
<table class="table table-striped table-bordered table-responsive" id="deploy-table-<?php echo $siid?>">
    
</table>

<script>
    function init_deploy_table_<?php echo $siid?>()
    {
        var table = $("#deploy-table-<?php echo $siid?>").DataTable({
            "columns": [ {"title": "内网IP", "data": "si_local_ip"},
                        {"title": "外网IP", "data": "si_remote_ip"},
                        {"title": "版本号", "data": "pv_v"},
                        {"title": "发版人", "data": "sp_creater"},
                        {"title": "发版时间", "data": "sp_create_time"}],
            "order": [[4, "desc"]],
            "ajax": {
                "url": "<?= Yii::$app->urlManager->createUrl(array('admin/main/jsondata', "id" => $id,"siid"=>$siid)) ?>",
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
        $('#deploy-table-<?php echo $siid?> tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('info');
        } );

    }
    
    $(document).ready(function(){
        init_deploy_table_<?php echo $siid?>();
//        init_vcs_table();
    });
</script>