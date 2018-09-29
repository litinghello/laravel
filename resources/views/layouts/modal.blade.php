
<div class="modal fade" id="message_info" tabindex="-1" role="dialog" aria-labelledby="message_info_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="message_info_label">标题</h4>
            </div>
            <div class="modal-body" id="message_info_body">数据</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<script type="text/javascript">
    function modal_show(object){
        $('#message_info_label').text(object.label);
        $('#message_info_body').html(object.body);
        $('#message_info').modal('show');
    }
</script>