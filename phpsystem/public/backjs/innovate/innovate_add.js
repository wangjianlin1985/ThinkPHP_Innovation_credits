$(function () {
	//实例化编辑器
	//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	UE.delEditor('innovate_innovateContent');
	var innovate_innovateContent_editor = UE.getEditor('innovate_innovateContent'); //创新项目描述编辑框
	$("#innovate_innovateTypeObj_innovateTypeId").combobox({
	    url: backURL + getVisitPath("InnovateType") + '/listAll',
	    valueField: "innovateTypeId",
	    textField: "innovateTypeName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#innovate_innovateTypeObj_innovateTypeId").combobox("getData"); 
            if (data.length > 0) {
                $("#innovate_innovateTypeObj_innovateTypeId").combobox("select", data[0].innovateTypeId);
            }
        }
	});
	$("#innovate_innovateTitle").validatebox({
		required : true, 
		missingMessage : '请输入创新项目标题',
	});

	$("#innovate_innovateScore").validatebox({
		required : true,
		validType : "number",
		missingMessage : '请输入申请创新学分',
		invalidMessage : '申请创新学分输入不对',
	});

	$("#innovate_studentObj_user_name").combobox({
	    url: backURL + getVisitPath("Student") + '/listAll',
	    valueField: "user_name",
	    textField: "name",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#innovate_studentObj_user_name").combobox("getData"); 
            if (data.length > 0) {
                $("#innovate_studentObj_user_name").combobox("select", data[0].user_name);
            }
        }
	});
	$("#innovate_sqTime").datetimebox({
	    required : true, 
	    showSeconds: true,
	    editable: false
	});

	$("#innovate_shenHeState").validatebox({
		required : true, 
		missingMessage : '请输入审核状态',
	});

	$("#innovate_innovateChengji").validatebox({
		required : true, 
		missingMessage : '请输入创新项目成绩',
	});

	//单击添加按钮
	$("#innovateAddButton").click(function () {
		if(innovate_innovateContent_editor.getContent() == "") {
			alert("请输入创新项目描述");
			return;
		}
		//验证表单 
		if(!$("#innovateAddForm").form("validate")) {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		} else {
			$("#innovateAddForm").form({
			    url: backURL + getVisitPath("Innovate") + "/add",
			    onSubmit: function(){
					if($("#innovateAddForm").form("validate"))  { 
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
                        $("#innovateAddForm").form("clear");
                        innovate_innovateContent_editor.setContent("");
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    }
			    }
			});
			//提交表单
			$("#innovateAddForm").submit();
		}
	});

	//单击清空按钮
	$("#innovateClearButton").click(function () { 
		$("#innovateAddForm").form("clear"); 
	});
});
