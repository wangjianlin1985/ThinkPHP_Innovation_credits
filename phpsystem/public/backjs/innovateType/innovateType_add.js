$(function () {
	$("#innovateType_innovateTypeName").validatebox({
		required : true, 
		missingMessage : '请输入创新类型名称',
	});

	$("#innovateType_sjnr").validatebox({
		required : true, 
		missingMessage : '请输入实践内容',
	});

	$("#innovateType_xuefen").validatebox({
		required : true,
		validType : "number",
		missingMessage : '请输入学分',
		invalidMessage : '学分输入不对',
	});

	$("#innovateType_cjjz").validatebox({
		required : true, 
		missingMessage : '请输入成绩记载',
	});

	//单击添加按钮
	$("#innovateTypeAddButton").click(function () {
		//验证表单 
		if(!$("#innovateTypeAddForm").form("validate")) {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		} else {
			$("#innovateTypeAddForm").form({
			    url: backURL + getVisitPath("InnovateType") + "/add",
			    onSubmit: function(){
					if($("#innovateTypeAddForm").form("validate"))  { 
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
                    //此处data={"Success":true}是字符串
                	var obj = jQuery.parseJSON(data); 
                    if(obj.success){ 
                        $.messager.alert("消息","保存成功！");
                        $(".messager-window").css("z-index",10000);
                        $("#innovateTypeAddForm").form("clear");
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    }
			    }
			});
			//提交表单
			$("#innovateTypeAddForm").submit();
		}
	});

	//单击清空按钮
	$("#innovateTypeClearButton").click(function () { 
		$("#innovateTypeAddForm").form("clear"); 
	});
});
