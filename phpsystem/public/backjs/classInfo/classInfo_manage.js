var classInfo_manage_tool = null; 
$(function () { 
	initClassInfoManageTool(); //建立ClassInfo管理对象
	classInfo_manage_tool.init(); //如果需要通过下拉框查询，首先初始化下拉框的值
	$("#classInfo_manage").datagrid({
		url : backURL + getVisitPath("ClassInfo") + '/backList',
		fit : true,
		fitColumns : true,
		striped : true,
		rownumbers : true,
		border : false,
		pagination : true,
		pageSize : 5,
		pageList : [5, 10, 15, 20, 25],
		pageNumber : 1,
		sortName : "classNo",
		sortOrder : "desc",
		toolbar : "#classInfo_manage_tool",
		columns : [[
			{
				field : "classNo",
				title : "班级编号",
				width : 140,
			},
			{
				field : "className",
				title : "班级名称",
				width : 140,
			},
			{
				field : "collegeName",
				title : "所在学院",
				width : 140,
			},
			{
				field : "specialName",
				title : "所在专业",
				width : 140,
			},
			{
				field : "mainTeacher",
				title : "班主任",
				width : 140,
			},
			{
				field : "bornDate",
				title : "成立日期",
				width : 140,
			},
		]],
	});

	$("#classInfoEditDiv").dialog({
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
				if ($("#classInfoEditForm").form("validate")) {
					//验证表单 
					if(!$("#classInfoEditForm").form("validate")) {
						$.messager.alert("错误提示","你输入的信息还有错误！","warning");
					} else {
						$("#classInfoEditForm").form({
						    url: backURL + getVisitPath("ClassInfo") + "/update",
						    onSubmit: function(){
								if($("#classInfoEditForm").form("validate"))  {
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
			                        $("#classInfoEditDiv").dialog("close");
			                        classInfo_manage_tool.reload();
			                    }else{
			                        $.messager.alert("消息",obj.message);
			                    } 
						    }
						});
						//提交表单
						$("#classInfoEditForm").submit();
					}
				}
			},
		},{
			text : "取消",
			iconCls : "icon-redo",
			handler : function () {
				$("#classInfoEditDiv").dialog("close");
				$("#classInfoEditForm").form("reset"); 
			},
		}],
	});
});

function initClassInfoManageTool() {
	classInfo_manage_tool = {
		init: function() {
		},
		reload : function () {
			$("#classInfo_manage").datagrid("reload");
		},
		redo : function () {
			$("#classInfo_manage").datagrid("unselectAll");
		},
		search: function() {
			var queryParams = $("#classInfo_manage").datagrid("options").queryParams;
			queryParams["classNo"] = $("#classNo").val();
			queryParams["className"] = $("#className").val();
			queryParams["collegeName"] = $("#collegeName").val();
			queryParams["specialName"] = $("#specialName").val();
			queryParams["mainTeacher"] = $("#mainTeacher").val();
			queryParams["bornDate"] = $("#bornDate").datebox("getValue"); 
			$("#classInfo_manage").datagrid("options").queryParams=queryParams; 
			$("#classInfo_manage").datagrid("load");
		},
		exportExcel: function() {
			$("#classInfoQueryForm").form({
			    url: backURL + getVisitPath("ClassInfo") + "/outToExcel",
			});
			//提交表单
			$("#classInfoQueryForm").submit();
		},
		remove : function () {
			var rows = $("#classInfo_manage").datagrid("getSelections");
			if (rows.length > 0) {
				$.messager.confirm("确定操作", "您正在要删除所选的记录吗？", function (flag) {
					if (flag) {
						var classNos = [];
						for (var i = 0; i < rows.length; i ++) {
							classNos.push(rows[i].classNo);
						}
						$.ajax({
							type : "POST",
							url :  backURL + getVisitPath("ClassInfo") + "/deletes",
							data : {
								classNos : classNos.join(","),
							},
							dataType: "json",
							beforeSend : function () {
								$("#classInfo_manage").datagrid("loading");
							},
							success : function (data) {
								if (data.success) {
									$("#classInfo_manage").datagrid("loaded");
									$("#classInfo_manage").datagrid("load");
									$("#classInfo_manage").datagrid("unselectAll");
									$.messager.show({
										title : "提示",
										msg : data.message
									});
								} else {
									$("#classInfo_manage").datagrid("loaded");
									$("#classInfo_manage").datagrid("load");
									$("#classInfo_manage").datagrid("unselectAll");
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
			var rows = $("#classInfo_manage").datagrid("getSelections");
			if (rows.length > 1) {
				$.messager.alert("警告操作！", "编辑记录只能选定一条数据！", "warning");
			} else if (rows.length == 1) {
				$.ajax({
					url : backURL + getVisitPath("ClassInfo") + "/update",
					type : "get",
					data : {
						classNo : rows[0].classNo,
					},
					dataType: "json",
					beforeSend : function () {
						$.messager.progress({
							text : "正在获取中...",
						});
					},
					success : function (classInfo, response, status) {
						$.messager.progress("close");
						if (classInfo) { 
							$("#classInfoEditDiv").dialog("open");
							$("#classInfo_classNo_edit").val(classInfo.classNo);
							$("#classInfo_classNo_edit").validatebox({
								required : true,
								missingMessage : "请输入班级编号",
								editable: false
							});
							$("#classInfo_className_edit").val(classInfo.className);
							$("#classInfo_className_edit").validatebox({
								required : true,
								missingMessage : "请输入班级名称",
							});
							$("#classInfo_collegeName_edit").val(classInfo.collegeName);
							$("#classInfo_collegeName_edit").validatebox({
								required : true,
								missingMessage : "请输入所在学院",
							});
							$("#classInfo_specialName_edit").val(classInfo.specialName);
							$("#classInfo_specialName_edit").validatebox({
								required : true,
								missingMessage : "请输入所在专业",
							});
							$("#classInfo_mainTeacher_edit").val(classInfo.mainTeacher);
							$("#classInfo_mainTeacher_edit").validatebox({
								required : true,
								missingMessage : "请输入班主任",
							});
							$("#classInfo_bornDate_edit").datebox({
								value: classInfo.bornDate,
							    required: true,
							    showSeconds: true,
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
