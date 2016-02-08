/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 10.07.12
 * Time: 1:45
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
    uploader.init();

});
var uploader = {
    init: function(){

        $('.template-download').delegate('.edit-start', 'click', uploader.editStart);
        $('.template-download').delegate('.edit-cancel', 'click', uploader.editCancel);
        $('.template-download').delegate('.edit-save', 'click', uploader.editSave);

        $('#fileupload').fileupload({
            prependFiles: true
        });
        // Enable iframe cross-domain access via redirect option:
        $('#fileupload').fileupload(
            'option',
            'redirect',
            '/rest/companyimage/iframe_transport?%s'
            /*
            window.location.href.replace(
                /\/[^\/]*$/,
                '/rest/companyimage/iframe_transport?%s'
            )
            */
        );
        $('#fileupload').fileupload('option', {
            maxFileSize: 15728640,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            process: [
                {
                    action: 'load',
                    fileTypes: /^image\/(gif|jpeg|png)$/,
                    maxFileSize: 15728640 // 15MB
                },
                {
                    action: 'resize',
                    maxWidth: 1440,
                    maxHeight: 900
                },
                {
                    action: 'save'
                }
            ]
        });

        $('#fileupload').bind('fileuploadsubmit', function (e, data) {
            data.context.find('.data-error').empty().hide();
            var inputs = data.context.find(':input');
            data.formData = inputs.serializeArray();
            data.formData.push({name: 'company_id', value: $('input[name="company_id"]').val()});
        });

        $('#fileupload').bind('fileuploadcompleted', function (e, data) {
            $(data.context).delegate('.edit-start', 'click', uploader.editStart);
            $(data.context).delegate('.edit-cancel', 'click', uploader.editCancel);
            $(data.context).delegate('.edit-save', 'click', uploader.editSave);
            console.log('end');
        });
    },
    editStart: function(e){
        e.preventDefault();
        $(this).parent().addClass('editing').parents('.template-download').find('.title, .description').addClass('editing');
    },
    editCancel: function(e){
        e.preventDefault();
        $(this).parents('.edit').removeClass('editing').parents('.template-download').find('.title, .description').removeClass('editing');
    },
    editSave: function(e){
        e.preventDefault();
        var button = $(this);
        var form = $(this).parents('.template-download');
        var image_id = $(this).data('image-id');
        var data = form.find('textarea, input').serializeArray();
        data.push({name: 'company_id', value: $(this).data('company-id')});

        form.find('.system').html('загрузка');
        $.ajax({
            url: '/rest/companyimage/edit/' + image_id,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(result){
                form.find('.title .value').html(result.title);
                form.find('.description .value').html(result.description);
                form.find('.title, .description').removeClass('editing').siblings('.system').empty();
                button.parents('.edit').removeClass('editing');
            },
            error: function(){
                alert('Ошика отправки/принятия данных, перезагрузите страницу и попробуйте снова');
            }
        });
    }
};