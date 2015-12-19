<table id="dg" title="调查列表" class="easyui-datagrid" style="border:0;"
            url="?ctrl=admin&act=listSurvey&ajax=1"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true" fit="true">
    <thead>
        <tr>
            <th field="ip" width="50">IP地址</th>
            <th field="createdon" width="50">调查时间</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">删除</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="showItem()">查看详细</a>
</div>
<script>
    ajax_load_scripts([],function(){
        $('.easyui-linkbutton').linkbutton();
        $('#dg').datagrid({
            toolbar: '#toolbar'
        });
    });

    function destroyItem(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','确定要删除该条目?',function(r){
                if (r){
                    $.post('?ctrl=admin&act=delete',{id:row.id},function(result){
                        if (result.success){
                            $('#dg').datagrid('reload');    // reload the user data
                        } else {
                            $.messager.show({    // show error message
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
                    },'json');
                }
            });
        }
    }

    function showItem(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            window.open("?ctrl=index&act=SurveyItem&id="+row.id);
        }
    }
</script>