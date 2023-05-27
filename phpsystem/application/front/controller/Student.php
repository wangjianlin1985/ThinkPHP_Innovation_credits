<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\ClassInfoModel;
use app\common\model\StudentModel;

class Student extends Base {
    protected $classInfoModel;
    protected $studentModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->classInfoModel = new ClassInfoModel();
        $this->studentModel = new StudentModel();
    }

    /*添加学生信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $student = $this->getStudentForm(true);
            $this->uploadPhoto($student,"userPhoto","userPhotoFile"); //处理学生照片上传
            $student["regTime"] = date('Y-m-d H:i:s');
            try {
                $this->studentModel->addStudent($student);
                $message = "学生添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "学生添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('student/student_frontAdd');
        }
    }

    /*前台修改学生信息*/
    public function frontModify() {
        $this->assign("user_name",input("user_name"));
        return $this->fetch("student/student_frontModify");
    }

    /*前台学生修改个人信息*/
    public function frontSelfModify() {
        $this->assign("user_name",session("user_name"));
        return $this->fetch("student/student_frontModify");
    }



    /*前台按照查询条件分页查询学生信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $user_name = input("user_name")==null?"":input("user_name");
        $classObj["classNo"] = input("classObj_classNo")==null?"":input("classObj_classNo");
        $name = input("name")==null?"":input("name");
        $birthDate = input("birthDate")==null?"":input("birthDate");
        $telephone = input("telephone")==null?"":input("telephone");
        $studentRs = $this->studentModel->queryStudent($user_name, $classObj, $name, $birthDate, $telephone, $this->currentPage);
        $this->assign("studentRs",$studentRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->studentModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->studentModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->studentModel->rows);
        $this->assign("user_name",$user_name);
        $this->assign("classObj",$classObj);
        $this->assign("classInfoList",$this->classInfoModel->queryAllClassInfo());
        $this->assign("name",$name);
        $this->assign("birthDate",$birthDate);
        $this->assign("telephone",$telephone);
        return $this->fetch('student/student_frontlist');
    }

    /*ajax方式查询学生信息*/
    public function listAll() {
        $studentRs = $this->studentModel->queryAllStudent();
        echo json_encode($studentRs);
    }
    /*前台查询根据主键查询一条学生信息*/
    public function frontshow() {
        $user_name = input("user_name");
        $student = $this->studentModel->getStudent($user_name);
       $this->assign("student",$student);
        return $this->fetch("student/student_frontshow");
    }

    /*更新学生信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $student = $this->getStudentForm(false);
            $this->uploadPhoto($student,"userPhoto","userPhotoFile"); //处理学生照片上传
            try {
                $this->studentModel->updateStudent($student);
                $message = "学生更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "学生更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取学生对象*/
            $user_name = input("user_name");
            $student = $this->studentModel->getStudent($user_name);
            echo json_encode($student);
        }
    }

    /*删除多条学生记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $user_names = input("user_names");
        try {
            $count = $this->studentModel->deleteStudents($user_names);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

