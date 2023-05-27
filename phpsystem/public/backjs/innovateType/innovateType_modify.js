$(function () {
	$.ajax({
		url :  backURL + getVisitPath("InnovateType") + "/update",
		type : "get",
		data : {
			innovateTypeId : $("#innovateType_innovateTypeId_edit").val(),
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
				$(".messager-window").css("z-index",10000);
			}
		}
	});

	$("#innovateTypeModifyButton").click(function(){ 
		if ($("#innovateTypeEditForm").form("validate")) {
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
			$("#innovateTypeEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
