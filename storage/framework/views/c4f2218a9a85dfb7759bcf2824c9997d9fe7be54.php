
<div id="card_body_datatables" class="card-body">
    <div class="row center-block">
        <table id="datalables_body" class="dataTables_wrapper form-inline" style="width:100%">
            <thead id="datalables_body_thead" hidden>
            <tr id="datalables_body_thead_tr"></tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    // var user_datatables_object;
    function user_datatables_hidden() {
        $("#datalables_body_thead").hide();
    }function user_datatables_show() {
        $("#datalables_body_thead").show();
    }
    function user_datatables_init(object,data,click_event) {
        //var info_object_key = new Array();
        var info_object_columns = new Array();
        $("#datalables_body_thead_tr").html("");
        for(key in object){
            //info_object_key.push(key);
            info_object_columns.push({data:key});
            $("#datalables_body_thead_tr").append("<th>"+info_object[key]+"</th>");
        }
        var user_datatables_object = $('#datalables_body').DataTable( {
            "scrollX": true,
            "scrollY": true,
            "processing": true,
            // "serverSide": true,
            // "ajax": {
            //     "url":object.url,
            //     "type": "POST",
            //     "headers": {'X-CSRF-TOKEN': object.csrf_token},
            // },
            data:data,
            columns: info_object_columns,
            language: {
                lengthMenu: '<select class="form-control input-xsmall">' + '<option value="1">1</option>' + '<option value="10">10</option>' + '<option value="20">20</option>' + '<option value="30">30</option>' + '<option value="40">40</option>' + '<option value="50">50</option>' + '</select>条记录',//左上角的分页大小显示。
                search: '<span class="label label-success">搜索：</span>',//右上角的搜索文本，可以写html标签
                paginate: {//分页的样式内容。
                    previous: "上一页",
                    next: "下一页",
                    first: "第一页",
                    last: "最后"
                },
                zeroRecords: "没有内容",//table tbody内容为空时，tbody的内容。
                //下面三者构成了总体的左下角的内容。
                info: "第 _START_ - _END_ 共 _PAGES_ 页，共 _TOTAL_ 条，初始_MAX_ 条 ",//左下角的信息显示，大写的词为关键字。
                infoEmpty: "0条记录",//筛选为空时左下角的显示。
                infoFiltered: ""//筛选之后的左下角筛选提示，
            },
            paging: true,
            pagingType: "full_numbers"//分页样式的类型
        }).on('click', 'tr', function () {
            if(user_datatables_object.row( this ).data() !== undefined){
                click_event(user_datatables_object.row( this ).data());
            }
        });
    }
    $("#datalables_body input[type=search]").css({ width: "auto" });
</script>