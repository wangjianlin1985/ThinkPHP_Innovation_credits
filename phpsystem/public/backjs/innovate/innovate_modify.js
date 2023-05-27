$(function () {
	//实例化编辑器
	//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	UE.delEditor('innovate_innovateContent_edit');
	var innovate_innovateContent_edit = UE.getEditor('innovate_innovateContent_edit'); //创新项目描述编辑器
	innovate_innovateContent_edit.addListener("ready", function () {
		 // editor准备好之后才可以使用 
		 ajaxModifyQuery();
	}); 
  function ajaxModifyQuery() {	
	$.ajax({
		url :  backURL + getVisitPath("Innovate") + "/update",
		type : "get",
		data : {
			innovateId : $("#innovate_innovateId_edit").val(),
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
				$("#innovate_innovateId_edit").val(innovate.innovateId);
				$("#innovate_innovateId_edit").validatebox({
					required : true,
					missingMessage : "请输入创新记录id",
					editable: false
				});
				$("#innovate_innovateTypeObj_innovateTypeId_edit").combobox({
					url: backURL + getVisitPath("InnovateType") + "/listAll",
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
				innovate_innovateContent_edit.setContent(innovate.innovateContent);
				$("#innovate_innovateFile").val(innovate.innovateFile);
				$("#innovate_innovateFileA").html(innovate.innovateFile == ""?"暂无文件":innovate.innovateFile);
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
				$(".messager-window").css("z-index",10000);
			}
		}
	});

  }

	$("#innovateModifyButton").click(function(){ 
		if ($("#innovateEditForm").form("validate")) {
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
                	var obj = jQuery.parseJSON(data);
                    if(obj.success){
                        $.messager.alert("消息","信息修改成功！");
                        $(".messager-window").css("z-index",10000);
                        //location.href="frontlist";
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    } 
			    }
			});
			//提交表单
			$("#innovateEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
