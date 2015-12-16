<!DOCTYPE html>
<html>
<head>
	<title>系统管理</title>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../view/css/common.css">
	<link rel="stylesheet" type="text/css" href="../view/js/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="../view/js/easyui/themes/icon.css">
	<script type="text/javascript" src="../view/js/jquery.min.js"></script>
	<script type="text/javascript" src="../view/js/easyui/jquery.easyui.min.js"></script>
</head>
<body>
	<table id="dg" title="调查题目列表" class="easyui-datagrid" style="width:100%;height:100%"
            url="get_users.php"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="firstname" width="50">First Name</th>
                <th field="lastname" width="50">Last Name</th>
                <th field="phone" width="50">Phone</th>
                <th field="email" width="50">Email</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit User</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Remove User</a>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:800px;height:380px;"
            closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post" novalidate style="padding:0;height:100%;">
            <div class="easyui-layout" style="width:100%;height:100%;" data-options="fit:true">
                <div data-options="region:'west',title:'基本配置',split:false,collapsible:false" style="width:50%;">
                    <div class="fitem">
                        <label>题目类型:</label>
                        <select id="sel-type">
                            <option value="1" selected="true">单选</option>
                            <option value="2">多选</option>
                            <option value="3">问答</option>
                        </select>
                    </div>
                </div>
                <div data-options="region:'east',title:'选项列表',split:false,collapsible:false" style="width:50%;"></div>
            </div>
        </form>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <script type="text/javascript">
        var url;
        function newUser(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','创建调查题目');
            $('#fm').form('clear');
            url = 'save_user.php';
        }
        function editUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit User');
                $('#fm').form('load',row);
                url = 'update_user.php?id='+row.id;
            }
        }
        function saveUser(){
            $('#fm').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.errorMsg){
                        $.messager.show({
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    } else {
                        $('#dlg').dialog('close');        // close the dialog
                        $('#dg').datagrid('reload');    // reload the user data
                    }
                }
            });
        }
        function destroyUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Are you sure you want to destroy this user?',function(r){
                    if (r){
                        $.post('destroy_user.php',{id:row.id},function(result){
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

        $("#sel-type").combobox({
            editable:false,
            valueField:'value',
            textField:'text',
            width:"80px"
        });
    </script>
    <style type="text/css">
        #fm{
            margin:0;
            padding:10px 30px;
        }
        .ftitle{
            font-size:14px;
            font-weight:bold;
            padding:5px 0;
            margin-bottom:10px;
            border-bottom:1px solid #ccc;
        }
        .fitem{
            margin-bottom:5px;
        }
        .fitem label{
            display:inline-block;
            width:80px;
        }
        .fitem input{
            width:160px;
        }
    </style>

</body>
</html>