<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\ClassInfoModel;
use app\common\model\StudentModel;

class Student extends BackBase {
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
    public function add(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $student = $this->getStudentForm(true);
            $this->uploadPhoto($student,"userPhoto","userPhotoFile"); //处理学生照片上传
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
            return view('student/student_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("user_name",input("user_name"));
        return view("student/student_modify");
    }

    /*ajax方式按照查询条件分页查询学生信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->studentModel->setRows($this->request->param("rows"));
            $user_name = input("user_name")==null?"":input("user_name");
            $classObj["classNo"] = input("classObj_classNo")==null?"":input("classObj_classNo");
            $name = input("name")==null?"":input("name");
            $birthDate = input("birthDate")==null?"":input("birthDate");
            $telephone = input("telephone")==null?"":input("telephone");
            $studentRs = $this->studentModel->queryStudent($user_name, $classObj, $name, $birthDate, $telephone, $this->currentPage);
            $expTableData = [];
            foreach($studentRs as $studentRow) {
                $studentRow["classObj"] = $this->classInfoModel->getClassInfo($studentRow["classObj"])["className"];
                $expTableData[] = $studentRow;
            }
            $data["rows"] = $studentRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->studentModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("student/student_query");
        }
    }

    /*ajax方式查询学生信息*/
    public function listAll() {
        $studentRs = $this->studentModel->queryAllStudent();
        echo json_encode($studentRs);
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

    /*按照查询条件导出学生信息到Excel*/
    public function outToExcel() {
        $user_name = input("user_name")==null?"":input("user_name");
        $classObj["classNo"] = input("classObj_classNo")==null?"":input("classObj_classNo");
        $name = input("name")==null?"":input("name");
        $birthDate = input("birthDate")==null?"":input("birthDate");
        $telephone = input("telephone")==null?"":input("telephone");
        $studentRs = $this->studentModel->queryOutputStudent($user_name,$classObj,$name,$birthDate,$telephone);
        $expTableData = [];
        foreach($studentRs as $studentRow) {
            $studentRow["classObj"] = $this->classInfoModel->getClassInfo($studentRow["classObj"])["className"];
            $expTableData[] = $studentRow;
        }
        $xlsName = "Student";
        $xlsCell = array(
            array('user_name','学号','string'),
            array('classObj','所在班级','string'),
            array('name','姓名','string'),
            array('gender','性别','string'),
            array('birthDate','出生日期','string'),
            array('userPhoto','学生照片','photo'),
            array('telephone','联系电话','string'),
            array('email','邮箱','string'),
            array('regTime','注册时间','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

