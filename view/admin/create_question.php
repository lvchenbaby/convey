<div id="dlg" class="easyui-dialog" style="width:480px;height:500px;"
            closed="true" buttons="#dlg-buttons">
    <div class="easyui-layout" data-option="fit:true" style="height:100%" id="form-create-question">
        <div data-options="region:'north'" style="height:30%;border-left:0;border-right:0;border-top:0"></div>
        <div data-options="region:'center'" style="height:70%;border-left:0;border-right:0;border-bottom:0">
            <table id="dg-question-items" class="easyui-datagrid" style="border:none;"
            fitColumns="true" singleSelect="true" fit="true" border="false">
                <thead>
                    <tr>
                        <th field="title" editor="{
                            type:'textbox',
                            options:{
                                valueField:'productid',
                            }
                        }" width="100"><a href="javascript:void(0)" class="easyui-linkbutton c6" data-options="iconCls:'icon-add'">增加选项</a></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<table id="dg" title="问题列表" class="easyui-datagrid" style="border:0;"
            url=""
            toolbar="#toolbar" pagination="true"
            fitColumns="true" singleSelect="true" fit="true" border="false">
    <thead>
        <tr>
            <th field="id" width="40">ID</th>
            <th field="type" width="40">类型</th>
            <th field="title" width="100">标题</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    选择一个调查:
    <select style="width:200px;" class="easyui-combobox" id="surveyid" data-options="
                    url:'?ctrl=admin&act=SurveyList',
                    method:'get',
                    valueField:'id',
                    textField:'title',
                    panelHeight:'auto',
                    editable:false
            ">
    </select>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addItem()">添加</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="addItem()">编辑</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">删除</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="showItem()">查看详细</a>
</div>

<script>
    ajax_load_scripts(["http://www.jeasyui.com/easyui/datagrid-detailview.js"],function(){
        $.parser.parse("#toolbar");
        $('#dg').datagrid({
            toolbar: '#toolbar'
        });
        $.parser.parse("#dlg-buttons");
        $.parser.parse("#dlg");
        $('#dg-question-items').datagrid();
        $('#dlg').dialog();
        $('#dg').datagrid({
            columns:[[
                {field:'id',width:40,title:"ID"
                },
                {field:'type',width:40,title:"类型",
                    formatter: function(value,row,index){
                        return ["单选","多选","问答"][value-1];
                    }
                },
                {field:'title',width:100,title:"标题"
                }
            ]],
            view: detailview,
            detailFormatter:function(index,row){
                var html='<div class="ddv">';
                for(var i=0,len=row.items.length;i<len;i++){
                    html+="<div>"+row.items[i]+"</div>";
                }

                return html+'</div>';
            },
            onExpandRow: function(index,row){
                // var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
                // ddv.panel({
                //     border:false,
                //     cache:true,
                //     href:'show_form.php?index='+index,
                //     onLoad:function(){
                //         $('#dg').datagrid('fixDetailRowHeight',index);
                //         $('#dg').datagrid('selectRow',index);
                //         $('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
                //     }
                // });
                // $('#dg').datagrid('fixDetailRowHeight',index);
            }
        });


        $('#surveyid').combobox({
            onSelect:function(record){
                $('#dg').datagrid({
                    url:"?ctrl=admin&act=GetQuestions&ajax=1&id="+record.id
                });
            }
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

    function addItem(){
        $('#dlg').dialog('open').dialog('center').dialog('setTitle','添加题目');
    }

    function showItem(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            window.open("?ctrl=index&act=SurveyItem&id="+row.id);
        }
    }

    //增加题目选项
    function addQuestionItem(){

    }
</script>