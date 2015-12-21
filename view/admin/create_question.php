<style type="text/css">
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
<div id="dlg" class="easyui-dialog" style="width:480px;height:500px;"
            closed="true" buttons="#dlg-buttons">
    <div class="easyui-layout" data-option="fit:true" style="height:100%" id="form-create-question">
        <div data-options="region:'north'" style="height:30%;border-left:0;border-right:0;border-top:0;padding:10px;">
            <div class="fitem">
                <label>选择调查:</label>
                <select class="easyui-combobox" style="width:80px" id="sel-survey" data-options="editable:false,valueField:'id',
                textField:'title'">

                </select>
            </div>
            <div class="fitem">
                <label>题目:</label>
                <input id="question" class="easyui-textbox">
            </div>
            <div class="fitem">
                <label>问题类型:</label>
                <select class="easyui-combobox" id="question-type" data-options="editable:false">
                    <option value="1">单选</option>
                    <option value="2">多选</option>
                    <option value="3">问答</option>
                </select>
            </div>
            <div class="fitem">
                <label>自定义题目项:</label>
                <input id="customer-option" class="easyui-switchbutton">
            </div>
        </div>
        <div data-options="region:'center'" style="height:70%;border-left:0;border-right:0;border-bottom:0">
            <table id="dg-question-items" title="题目选项" class="easyui-datagrid" style="border:none;"
            fitColumns="true" singleSelect="true" fit="true" border="false" tools="#tt2" onClickCell="onClickCell">
            </table>
        </div>
    </div>
</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveQuestion()" style="width:90px">Save</a>
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
    <select style="width:200px;" class="easyui-combobox" id="surveyid" 
            data-options="
                url:'?ctrl=admin&act=SurveyList',
                method:'get',
                valueField:'id',
                textField:'title',
                panelHeight:'auto',
                editable:false,
                onLoadSuccess:function(){
                    var data=$(this).combobox('getData');
                    console.log(data);
                    $('#sel-survey').combobox('loadData',data);
                }
            ">
    </select>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addItem()">添加</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="addItem()">编辑</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">删除</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="showItem()">查看详细</a>
</div>

<div id="tt2">
    <a href="javascript:void(0)" class="icon-add" onclick="javascript:addQuestionItem()"></a>&nbsp;&nbsp;
    <a href="javascript:void(0)" class="icon-ok" onclick="javascript:accept()"></a>
    <a href="javascript:void(0)" class="icon-remove" onclick="javascript:deleteQuestionItem()"></a>
</div>

<script>
    ajax_load_scripts(["http://www.jeasyui.com/easyui/datagrid-detailview.js"],function(){

        $.parser.parse("#toolbar");
        $('#dg').datagrid({
            toolbar: '#toolbar'
        });

        $.parser.parse("#dlg-buttons");
        $.parser.parse("#dlg");
        $('#dg-question-items').datagrid({
            columns:[[
                {field:'questionitem',width:80,editor:{
                    type:"textbox",
                    options:{
                        required:true
                    }
                }},
            ]],
            onClickCell:onClickCell
        });
        $('#dlg').dialog();
        $('#dg').datagrid({
            columns:[[
                {field:'id',width:40,title:"ID"},
                {field:'type',width:40,title:"类型",
                    formatter: function(value,row,index){
                        return ["单选","多选","问答"][value-1];
                    }
                },
                {field:'title',width:100,title:"标题"}
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

    var editIndex = undefined;
    function endEditing(){
        if (editIndex == undefined){return true}
        if ($('#dg-question-items').datagrid('validateRow', editIndex)){
            var ed = $('#dg-question-items').datagrid('getEditor', {index:editIndex,field:'questionitem'});
            $('#dg').datagrid('endEdit', editIndex);
            $('#dg-question-items').datagrid('acceptChanges');
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }

    function accept(){
        if (endEditing()){
            $('#dg-question-items').datagrid('acceptChanges');
        }
    }

    function deleteQuestionItem(){

    }

    function onClickCell(index, field){
        if (editIndex != index){
            if (endEditing()){
                $('#dg-question-items').datagrid('selectRow', index)
                        .datagrid('beginEdit', index);
                var ed = $('#dg-question-items').datagrid('getEditor', {index:index,field:field});
                if (ed){
                    ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                }
                editIndex = index;
            } else {
                $('#dg-question-items').datagrid('selectRow', editIndex);
            }
        }
    }

    //增加题目选项
    function addQuestionItem(){
        if(endEditing()){
            $('#dg-question-items').datagrid('appendRow',{questionitem:""});
            editIndex = $('#dg-question-items').datagrid('getRows').length-1;
            $('#dg-question-items').datagrid('selectRow', editIndex)
                .datagrid('beginEdit', editIndex);
        }
    }

    function saveQuestion(){
        var questionitems=$('#dg-question-items').datagrid("getData");
        if($('#sel-survey').combobox("getValue")==""){
            alert("请选择一项调查");
            return;
        }

        if($('#question').textbox("getValue")==""){
            alert("请填写题目内容");
            return;
        }

        if(questionitems.total==0 && $('#question-type').combobox("getValue")<3){
            alert("必须具有一条以上的题目选项");
        }else{

        }
    }

</script>