var innovateType_manage_tool = null; 
$(function () { 
	initInnovateTypeManageTool(); //建立InnovateType管理对象
	innovateType_manage_tool.init(); //如果需要通过下拉框查询，首先初始化下拉框的值
	$("#innovateType_manage").datagrid({
		url : backURL + getVisitPath("InnovateType") + '/backList',
		fit : true,
		fitColumns : true,
		striped : true,
		rownumbers : true,
		border : false,
		pagination : true,
		pageSize : 5,
		pageList : [5, 10, 15, 20, 25],
		pageNumber : 1,
		sortName : "innovateTypeId",
		sortOrder : "desc",
		toolbar : "#innovateType_manage_tool",
		columns : [[
			{
				field : "innovateTypeId",
				title : "创新类型id",
				width : 70,
			},
			{
				field : "innovateTypeName",
				title : "创新类型名称",
				width : 140,
			},
			{
				field : "sjnr",
				title : "实践内容",
				width : 140,
			},
			{
				field : "xuefen",
				title : "学分",
				width : 70,
			},
			{
				field : "cjjz",
				title : "成绩记载",
				width : 140,
			},
		]],
	});

	$("#innovateTypeEditDiv").dialog({
		title : "修改管理",
		top: "50px",
		width : 700,
		height : 515,
		modal : true,
		closed : true,
		iconCls : "icon-edit-new",
		buttons : [{
			text : "提交",
			iconCls : "icon-edit-new",
			handler : function () {
				if ($("#innovateTypeEditForm").form("validate")) {
					//验证表单 
					if(!$("#innovateTypeEditForm").form("validate")) {
						$.messager.alert("错误提示","你输入的信息还有错误！","warning");
					} else {
						$("#innovateTypeEditForm").form({
						    url: backURL + getVisitPath("InnovateType") + "/update",
						    onSubmit: function(){
								if($("#innovateTypeEditForm").form("validate"))  {
				                	$.messager.progress({
										text : "正在提交数据中...",
									});
				                	return true;
				                } else { 
				                    return false; 
				                }
						    },
						    success:function(data){
						    	$.messager.progress("close");
						    	console.log(data);
			                	var obj = jQuery.parseJSON(data);
			                    if(obj.success){
			                        $.messager.alert("消息","信息修改成功！");
			                        $("#innovateTypeEditDiv").dialog("close");
			                        innovateType_manage_tool.reload();
			                    }else{
			                        $.messager.alert("消息",obj.message);
			                    } 
						    }
						});
						//提交表单
						$("#innovateTypeEditForm").submit();
					}
				}
			},
		},{
			text : "取消",
			iconCls : "icon-redo",
			handler : function () {
				$("#innovateTypeEditDiv").dialog("close");
				$("#innovateTypeEditForm").form("reset"); 
			},
		}],
	});
});

function initInnovateTypeManageTool() {
	innovateType_manage_tool = {
		init: function() {
		},
		reload : function () {
			$("#innovateType_manage").datagrid("reload");
		},
		redo : function () {
			$("#innovateType_manage").datagrid("unselectAll");
		},
		search: function() {
			var queryParams = $("#innovateType_manage").datagrid("options").queryParams;
			queryParams["innovateTypeName"] = $("#innovateTypeName").val();
			$("#innovateType_manage").datagrid("options").queryParams=queryParams; 
			$("#innovateType_manage").datagrid("load");
		},
		exportExcel: function() {
			$("#innovateTypeQueryForm").form({
			    url: backURL + getVisitPath("InnovateType") + "/outToExcel",
			});
			//提交表单
			$("#innovateTypeQueryForm").submit();
		},
		remove : function () {
			var rows = $("#innovateType_manage").datagrid("getSelections");
			if (rows.length > 0) {
				$.messager.confirm("确定操作", "您正在要删除所选的记录吗？", function (flag) {
					if (flag) {
						var innovateTypeIds = [];
						for (var i = 0; i < rows.length; i ++) {
							innovateTypeIds.push(rows[i].innovateTypeId);
						}
						$.ajax({
							type : "POST",
							url :  backURL + getVisitPath("InnovateType") + "/deletes",
							data : {
								innovateTypeIds : innovateTypeIds.join(","),
							},
							dataType: "json",
							beforeSend : function () {
								$("#innovateType_manage").datagrid("loading");
							},
							success : function (data) {
								if (data.success) {
									$("#innovateType_manage").datagrid("loaded");
									$("#innovateType_manage").datagrid("load");
									$("#innovateType_manage").datagrid("unselectAll");
									$.messager.show({
										title : "提示",
										msg : data.message
									});
								} else {
									$("#innovateType_manage").datagrid("loaded");
									$("#innovateType_manage").datagrid("load");
									$("#innovateType_manage").datagrid("unselectAll");
									$.messager.alert("消息",data.message);
								}
							},
						});
					}
				});
			} else {
				$.messager.alert("提示", "请选择要删除的记录！", "info");
			}
		},
		edit : function () {
			var rows = $("#innovateType_manage").datagrid("getSelections");
			if (rows.length > 1) {
				$.messager.alert("警告操作！", "编辑记录只能选定一条数据！", "warning");
			} else if (rows.length == 1) {
				$.ajax({
					url : backURL + getVisitPath("InnovateType") + "/update",
					type : "get",
					data : {
						innovateTypeId : rows[0].innovateTypeId,
					},
					dataType: "json",
					beforeSend : function () {
						$.messager.progress({
							text : "正在获取中...",
						});
					},
					success : function (innovateType, response, status) {
						$.messager.progress("close");
						if (innovateType) { 
							$("#innovateTypeEditDiv").dialog("open");
							$("#innovateType_innovateTypeId_edit").val(innovateType.innovateTypeId);
							$("#innovateType_innovateTypeId_edit").validatebox({
								required : true,
								missingMessage : "请输入创新类型id",
								editable: false
							});
							$("#innovateType_innovateTypeName_edit").val(innovateType.innovateTypeName);
							$("#innovateType_innovateTypeName_edit").validatebox({
								required : true,
								missingMessage : "请输入创新类型名称",
							});
							$("#innovateType_sjnr_edit").val(innovateType.sjnr);
							$("#innovateType_sjnr_edit").validatebox({
								required : true,
								missingMessage : "请输入实践内容",
							});
							$("#innovateType_xuefen_edit").val(innovateType.xuefen);
							$("#innovateType_xuefen_edit").validatebox({
								required : true,
								validType : "number",
								missingMessage : "请输入学分",
								invalidMessage : "学分输入不对",
							});
							$("#innovateType_cjjz_edit").val(innovateType.cjjz);
							$("#innovateType_cjjz_edit").validatebox({
								required : true,
								missingMessage : "请输入成绩记载",
							});
						} else {
							$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
						}
					}
				});
			} else if (rows.length == 0) {
				$.messager.alert("警告操作！", "编辑记录至少选定一条数据！", "warning");
			}
		},
	};
}
