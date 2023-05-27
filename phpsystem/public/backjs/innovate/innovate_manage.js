var innovate_manage_tool = null; 
$(function () { 
	initInnovateManageTool(); //建立Innovate管理对象
	innovate_manage_tool.init(); //如果需要通过下拉框查询，首先初始化下拉框的值
	$("#innovate_manage").datagrid({
		url : backURL + getVisitPath("Innovate") + '/backList',
		fit : true,
		fitColumns : true,
		striped : true,
		rownumbers : true,
		border : false,
		pagination : true,
		pageSize : 5,
		pageList : [5, 10, 15, 20, 25],
		pageNumber : 1,
		sortName : "innovateId",
		sortOrder : "desc",
		toolbar : "#innovate_manage_tool",
		columns : [[
			{
				field : "innovateId",
				title : "创新记录id",
				width : 70,
			},
			{
				field : "innovateTypeObj",
				title : "创新类型",
				width : 140,
			},
			{
				field : "innovateTitle",
				title : "创新项目标题",
				width : 140,
			},
			{
				field : "innovateFile",
				title : "创新项目文件",
				width : "350px",
				formatter: function(val,row) {
 					if(val == "") return "暂无文件";
					return "<a href='" + publicURL + val + "' target='_blank' style='color:red;'>" + val + "</a>";
				}
 			},
			{
				field : "innovateScore",
				title : "申请创新学分",
				width : 70,
			},
			{
				field : "studentObj",
				title : "申请的学生",
				width : 140,
			},
			{
				field : "sqTime",
				title : "申请时间",
				width : 140,
			},
			{
				field : "shenHeState",
				title : "审核状态",
				width : 140,
			},
			{
				field : "innovateChengji",
				title : "创新项目成绩",
				width : 140,
			},
		]],
	});

	$("#innovateEditDiv").dialog({
		title : "修改管理",
		top: "10px",
		width : 1000,
		height : 600,
		modal : true,
		closed : true,
		iconCls : "icon-edit-new",
		buttons : [{
			text : "提交",
			iconCls : "icon-edit-new",
			handler : function () {
				if ($("#innovateEditForm").form("validate")) {
					//验证表单 
					if(!$("#innovateEditForm").form("validate")) {
						$.messager.alert("错误提示","你输入的信息还有错误！","warning");
					} else {
						$("#innovateEditForm").form({
						    url: backURL + getVisitPath("Innovate") + "/update",
						    onSubmit: function(){
								if($("#innovateEditForm").form("validate"))  {
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
			                        $("#innovateEditDiv").dialog("close");
			                        innovate_manage_tool.reload();
			                    }else{
			                        $.messager.alert("消息",obj.message);
			                    } 
						    }
						});
						//提交表单
						$("#innovateEditForm").submit();
					}
				}
			},
		},{
			text : "取消",
			iconCls : "icon-redo",
			handler : function () {
				$("#innovateEditDiv").dialog("close");
				$("#innovateEditForm").form("reset"); 
			},
		}],
	});
});

function initInnovateManageTool() {
	innovate_manage_tool = {
		init: function() {
			$.ajax({
				url : backURL + getVisitPath("InnovateType") + "/listAll",
				type : "post",
				dataType: "json",
				success : function (data, response, status) {
					$("#innovateTypeObj_innovateTypeId_query").combobox({ 
					    valueField:"innovateTypeId",
					    textField:"innovateTypeName",
					    panelHeight: "200px",
				        editable: false, //不允许手动输入 
					});
					data.splice(0,0,{innovateTypeId:0,innovateTypeName:"不限制"});
					$("#innovateTypeObj_innovateTypeId_query").combobox("loadData",data); 
				}
			});
			$.ajax({
				url : backURL + getVisitPath("Student") + "/listAll",
				type : "post",
				dataType: "json",
				success : function (data, response, status) {
					$("#studentObj_user_name_query").combobox({ 
					    valueField:"user_name",
					    textField:"name",
					    panelHeight: "200px",
				        editable: false, //不允许手动输入 
					});
					data.splice(0,0,{user_name:"",name:"不限制"});
					$("#studentObj_user_name_query").combobox("loadData",data); 
				}
			});
		},
		reload : function () {
			$("#innovate_manage").datagrid("reload");
		},
		redo : function () {
			$("#innovate_manage").datagrid("unselectAll");
		},
		search: function() {
			var queryParams = $("#innovate_manage").datagrid("options").queryParams;
			queryParams["innovateTypeObj.innovateTypeId"] = $("#innovateTypeObj_innovateTypeId_query").combobox("getValue");
			queryParams["innovateTitle"] = $("#innovateTitle").val();
			queryParams["studentObj.user_name"] = $("#studentObj_user_name_query").combobox("getValue");
			queryParams["sqTime"] = $("#sqTime").datebox("getValue"); 
			queryParams["shenHeState"] = $("#shenHeState").val();
			$("#innovate_manage").datagrid("options").queryParams=queryParams; 
			$("#innovate_manage").datagrid("load");
		},
		exportExcel: function() {
			$("#innovateQueryForm").form({
			    url: backURL + getVisitPath("Innovate") + "/outToExcel",
			});
			//提交表单
			$("#innovateQueryForm").submit();
		},
		remove : function () {
			var rows = $("#innovate_manage").datagrid("getSelections");
			if (rows.length > 0) {
				$.messager.confirm("确定操作", "您正在要删除所选的记录吗？", function (flag) {
					if (flag) {
						var innovateIds = [];
						for (var i = 0; i < rows.length; i ++) {
							innovateIds.push(rows[i].innovateId);
						}
						$.ajax({
							type : "POST",
							url :  backURL + getVisitPath("Innovate") + "/deletes",
							data : {
								innovateIds : innovateIds.join(","),
							},
							dataType: "json",
							beforeSend : function () {
								$("#innovate_manage").datagrid("loading");
							},
							success : function (data) {
								if (data.success) {
									$("#innovate_manage").datagrid("loaded");
									$("#innovate_manage").datagrid("load");
									$("#innovate_manage").datagrid("unselectAll");
									$.messager.show({
										title : "提示",
										msg : data.message
									});
								} else {
									$("#innovate_manage").datagrid("loaded");
									$("#innovate_manage").datagrid("load");
									$("#innovate_manage").datagrid("unselectAll");
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
			var rows = $("#innovate_manage").datagrid("getSelections");
			if (rows.length > 1) {
				$.messager.alert("警告操作！", "编辑记录只能选定一条数据！", "warning");
			} else if (rows.length == 1) {
				$.ajax({
					url : backURL + getVisitPath("Innovate") + "/update",
					type : "get",
					data : {
						innovateId : rows[0].innovateId,
					},
					dataType: "json",
					beforeSend : function () {
						$.messager.progress({
							text : "正在获取中...",
						});
					},
					success : function (innovate, response, status) {
						$.messager.progress("close");
						if (innovate) { 
							$("#innovateEditDiv").dialog("open");
							$("#innovate_innovateId_edit").val(innovate.innovateId);
							$("#innovate_innovateId_edit").validatebox({
								required : true,
								missingMessage : "请输入创新记录id",
								editable: false
							});
							$("#innovate_innovateTypeObj_innovateTypeId_edit").combobox({
							    url: backURL + getVisitPath("InnovateType") + "/listAll",
							    dataType: "json",
							    valueField:"innovateTypeId",
							    textField:"innovateTypeName",
							    panelHeight: "auto",
						        editable: false, //不允许手动输入 
						        onLoadSuccess: function () { //数据加载完毕事件
									$("#innovate_innovateTypeObj_innovateTypeId_edit").combobox("select", innovate.innovateTypeObj);
									//var data = $("#innovate_innovateTypeObj_innovateTypeId_edit").combobox("getData"); 
						            //if (data.length > 0) {
						                //$("#innovate_innovateTypeObj_innovateTypeId_edit").combobox("select", data[0].innovateTypeId);
						            //}
								}
							});
							$("#innovate_innovateTitle_edit").val(innovate.innovateTitle);
							$("#innovate_innovateTitle_edit").validatebox({
								required : true,
								missingMessage : "请输入创新项目标题",
							});
							innovate_innovateContent_editor.setContent(innovate.innovateContent, false);
							$("#innovate_innovateFile").val(innovate.innovateFile);
							if(innovate.innovateFile == "") $("#innovate_innovateFileA").html("暂无文件");
							else $("#innovate_innovateFileA").html(innovate.innovateFile);
							$("#innovate_innovateFileA").attr("href", publicURL + innovate.innovateFile);
							$("#innovate_innovateScore_edit").val(innovate.innovateScore);
							$("#innovate_innovateScore_edit").validatebox({
								required : true,
								validType : "number",
								missingMessage : "请输入申请创新学分",
								invalidMessage : "申请创新学分输入不对",
							});
							$("#innovate_studentObj_user_name_edit").combobox({
							    url: backURL + getVisitPath("Student") + "/listAll",
							    dataType: "json",
							    valueField:"user_name",
							    textField:"name",
							    panelHeight: "auto",
						        editable: false, //不允许手动输入 
						        onLoadSuccess: function () { //数据加载完毕事件
									$("#innovate_studentObj_user_name_edit").combobox("select", innovate.studentObj);
									//var data = $("#innovate_studentObj_user_name_edit").combobox("getData"); 
						            //if (data.length > 0) {
						                //$("#innovate_studentObj_user_name_edit").combobox("select", data[0].user_name);
						            //}
								}
							});
							$("#innovate_sqTime_edit").datetimebox({
								value: innovate.sqTime,
							    required: true,
							    showSeconds: true,
							});
							$("#innovate_shenHeState_edit").val(innovate.shenHeState);
							$("#innovate_shenHeState_edit").validatebox({
								required : true,
								missingMessage : "请输入审核状态",
							});
							$("#innovate_innovateChengji_edit").val(innovate.innovateChengji);
							$("#innovate_innovateChengji_edit").validatebox({
								required : true,
								missingMessage : "请输入创新项目成绩",
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
