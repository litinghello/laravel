
<div class="card-body">
    <div class="row center-block">
        <table id="datalables_body" class="table table-striped table-hover table-condensed" style="width:100%">
            <thead id="datalables_body_thead">
            <tr id="datalables_body_thead_tr">
                <th>决定书号</th>
                <th>车主姓名</th>
                <th>车牌号牌</th>
                <th>罚款额</th>
                <th>滞纳金</th>
                
            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    function set_datalables(object){
        object.forEach(function (value) {
            $("#datalables_body_thead_tr").append("<th>"+value+"</th>");
        });
    }
</script>