
<div class="modal fade" id="message_info" tabindex="-1" role="dialog" aria-labelledby="message_info_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="message_info_label">标题</h4>
            </div>
            <div class="modal-body" id="message_info_body">数据</div>
            <div id="user_modal_footer" class="modal-footer">
                <button id="user_modal_button_confirm" type="button" class="btn btn-default">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<script type="text/javascript">
    function user_modal_hide(object){
        $("#user_modal_button_confirm").hide();
        $('#message_info_label').text("");
        $('#message_info_body').html("");
        $('#message_info').modal('hide');
    }
    function user_modal_show(title,body){
        $("#user_modal_button_confirm").hide();
        $('#message_info_label').text(title);
        $('#message_info_body').html(body);
        $('#message_info').modal('show');
    }
    function user_modal_prompt(html){
        $("#user_modal_button_confirm").hide();
        $('#message_info_label').text("提示");
        $('#message_info_body').html(html);
        $('#message_info').modal('show');
    }
    function user_modal_warning(html){
        $("#user_modal_button_confirm").hide();
        $('#message_info_label').text("警告");
        $('#message_info_body').html(html);
        $('#message_info').modal('show');
    }
    function user_modal_input(name,event) {
        $('#message_info_label').text("请确认");
        $('#message_info_body').html("<div class=\"input-group\">\n" +
            "<div class=\"input-group-addon\">"+name+"</div>\n" +
            "\t<input id=\"user_model_input\" type=\"text\" class=\"form-control\" name=\"user_model_input\" placeholder=\"手机号码\" value=\"\" required>\n" +
            "</div>\n" +
            "</div>");
        $('#message_info').modal('show');

        $("#user_modal_button_confirm").show().click(function () {
            event($("#user_model_input").val());
        });
    }
</script>