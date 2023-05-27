<?php
namespace app\front\controller;
use think\Controller;

class Base extends Controller
{
    protected $currentPage = 1;
    protected $request = null;

    //向客户端输出ajax响应结果
    public function writeJsonResponse($success, $message) {
        $response = array(
            "success" => $success,
            "message" => $message,
        );
        echo json_encode($response);
    }

    /**
     * @param $obj:  保存图片路径的对象
     * @param $indexName 索引名称
     * @param $photoFiledName 提交的图片表单名称
     */
    public function uploadPhoto(&$obj,$indexName,$photoFiledName) {
        if($_FILES[$photoFiledName]['tmp_name']){
            $file = $this->request->file($photoFiledName);
            //控制上传的文件类型，大小
            if(!(($_FILES[$photoFiledName]["type"]=="image/jpeg"
                    || $_FILES[$photoFiledName]["type"]=="image/jpg"
                    || $_FILES[$photoFiledName]["type"]=="image/png") && $_FILES[$photoFiledName]["size"] < 1024000)) {
                $message = "图书图片请选择jpg或png格式的图片!";
                $this->writeJsonResponse(false,$message);
                exit;
            }
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    /**
     * @param $obj:  保存文件路径的对象
     * @param $indexName 索引名称
     * @param $resourceFiledName 提交的文件表单名称
     */
    public function uploadFile(&$obj,$indexName,$resourceFiledName) {
        if($_FILES[$resourceFiledName]['tmp_name']){
            $file = $this->request->file($resourceFiledName);
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    //接收提交的Student信息参数
    public function getStudentForm($insertFlag) {
        $student = [
            'user_name'=> input("student_user_name"), //学号
            'password'=> input("student_password"), //登录密码
            'classObj'=> input("student_classObj_classNo"), //所在班级
            'name'=> input("student_name"), //姓名
            'gender'=> input("student_gender"), //性别
            'birthDate'=> input("student_birthDate"), //出生日期
            'userPhoto' => $insertFlag==true?"upload/NoImage.jpg":input("student_userPhoto"), //学生照片
            'telephone'=> input("student_telephone"), //联系电话
            'email'=> input("student_email"), //邮箱
            'address'=> input("student_address"), //家庭地址
            'regTime'=> input("student_regTime"), //注册时间
        ];
        return $student;
    }

    //接收提交的ClassInfo信息参数
    public function getClassInfoForm($insertFlag) {
        $classInfo = [
            'classNo'=> input("classInfo_classNo"), //班级编号
            'className'=> input("classInfo_className"), //班级名称
            'collegeName'=> input("classInfo_collegeName"), //所在学院
            'specialName'=> input("classInfo_specialName"), //所在专业
            'mainTeacher'=> input("classInfo_mainTeacher"), //班主任
            'bornDate'=> input("classInfo_bornDate"), //成立日期
        ];
        return $classInfo;
    }

    //接收提交的InnovateType信息参数
    public function getInnovateTypeForm($insertFlag) {
        $innovateType = [
            'innovateTypeId'=> input("innovateType_innovateTypeId"), //创新类型id
            'innovateTypeName'=> input("innovateType_innovateTypeName"), //创新类型名称
            'sjnr'=> input("innovateType_sjnr"), //实践内容
            'xuefen'=> input("innovateType_xuefen"), //学分
            'cjjz'=> input("innovateType_cjjz"), //成绩记载
        ];
        return $innovateType;
    }

    //接收提交的Innovate信息参数
    public function getInnovateForm($insertFlag) {
        $innovate = [
            'innovateId'=> input("innovate_innovateId"), //创新记录id
            'innovateTypeObj'=> input("innovate_innovateTypeObj_innovateTypeId"), //创新类型
            'innovateTitle'=> input("innovate_innovateTitle"), //创新项目标题
            'innovateContent'=> input("innovate_innovateContent"), //创新项目描述
            'innovateFile' => $insertFlag==true?"":input("innovate_innovateFile"), //创新项目文件
            'innovateScore'=> input("innovate_innovateScore"), //申请创新学分
            'studentObj'=> input("innovate_studentObj_user_name"), //申请的学生
            'sqTime'=> input("innovate_sqTime"), //申请时间
            'shenHeState'=> input("innovate_shenHeState"), //审核状态
            'innovateChengji'=> input("innovate_innovateChengji"), //创新项目成绩
        ];
        return $innovate;
    }

    //接收提交的Notice信息参数
    public function getNoticeForm($insertFlag) {
        $notice = [
            'noticeId'=> input("notice_noticeId"), //公告id
            'title'=> input("notice_title"), //标题
            'content'=> input("notice_content"), //公告内容
            'publishDate'=> input("notice_publishDate"), //发布时间
        ];
        return $notice;
    }

}

