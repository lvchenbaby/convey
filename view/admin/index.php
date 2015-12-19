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
  <script type="text/javascript" src="<?php echo JS; ?>/base.js"></script>
</head>
    <body>
       <div class="easyui-layout" data-options="fit:true">
           <div data-options="region:'west',split:true,title:'系统菜单'" style="width:20%;height:100%">
                <ul id="tt" class="easyui-tree">
                </ul>
           </div>
           <div data-options="region:'center',split:true" style="width:80%;height:100%">
               <div class="easyui-layout" data-options="fit:true">
                   <div data-options="region:'north'" style="height:28px;border-left:0;border-right:0;border-top:0">
                      <div class="panel-title">
                        <script>document.write(Date());</script>
                      </div>
                    </div>
                   <div id="page-content-area" data-options="region:'center'" style="border-left:0;border-right:0;border-top:0;border-bottom:0">
                       
                   </div>
               </div>
               <div class="mask">
                 <div class="loading">
                   <img src="<?php echo JS ?>/easyui/themes/default/images/loading.gif">
                 </div>
               </div>
           </div>
        </div>
    </body>
</html>

<script>
    $(function(){
        $("#tt").tree({
            data:[{
                "id":1,
                "text":"基本配置",
                "iconCls":"icon-save",
                "children":[{
                    "text":"问题列表",
                    "iconCls":"icon-add",
                    "checked":true,
                    "attributes":{
                        "url":"?ctrl=admin&act=CreateQuestion"
                    }},{
                    "text":"调查列表",
                    "checked":true,
                    "attributes":{
                    "url":"?ctrl=admin&act=ListSurvey"
                    }
                }]
              }
            ],
            onClick:function(node){
                if(node.attributes.url){
                    loadPage(node.attributes.url);
                }
            }
        });
    });
</script>