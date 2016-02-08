/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 31.07.12
 * Time: 22:32
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
    $.ajax({
        url: '/rest/statistics/company/' + company_id,
        type: 'post',
        data: visit_data,
        dataType: 'json',
        success: function (result)
        {


        }
    });
});