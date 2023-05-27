<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\ClassInfoModel;

class ClassInfo extends BackBase {
    protected $classInfoModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->classInfoModel = new ClassInfoModel();
    }

    /*添加班级信息*/
    public function add(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $classInfo = $this->getClassInfoForm(true);
            try {
                $this->classInfoModel->addClassInfo($classInfo);
                $message = "班级添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "班级添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return view('classInfo/classInfo_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("classNo",input("classNo"));
        return view("classInfo/classInfo_modify");
    }

    /*ajax方式按照查询条件分页查询班级信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->classInfoModel->setRows($this->request->param("rows"));
            $classNo = input("classNo")==null?"":input("classNo");
            $className = input("className")==null?"":input("className");
            $collegeName = input("collegeName")==null?"":input("collegeName");
            $specialName = input("specialName")==null?"":input("specialName");
            $mainTeacher = input("mainTeacher")==null?"":input("mainTeacher");
            $bornDate = input("bornDate")==null?"":input("bornDate");
            $classInfoRs = $this->classInfoModel->queryClassInfo($classNo, $className, $collegeName, $specialName, $mainTeacher, $bornDate, $this->currentPage);
            $expTableData = [];
            foreach($classInfoRs as $classInfoRow) {
                $expTableData[] = $classInfoRow;
            }
            $data["rows"] = $classInfoRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->classInfoModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("classInfo/classInfo_query");
        }
    }

    /*ajax方式查询班级信息*/
    public function listAll() {
        $classInfoRs = $this->classInfoModel->queryAllClassInfo();
        echo json_encode($classInfoRs);
    }
    /*更新班级信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $classInfo = $this->getClassInfoForm(false);
            try {
                $this->classInfoModel->updateClassInfo($classInfo);
                $message = "班级更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "班级更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取班级对象*/
            $classNo = input("classNo");
            $classInfo = $this->classInfoModel->getClassInfo($classNo);
            echo json_encode($classInfo);
        }
    }

    /*删除多条班级记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $classNos = input("classNos");
        try {
            $count = $this->classInfoModel->deleteClassInfos($classNos);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

    /*按照查询条件导出班级信息到Excel*/
    public function outToExcel() {
        $classNo = input("classNo")==null?"":input("classNo");
        $className = input("className")==null?"":input("className");
        $collegeName = input("collegeName")==null?"":input("collegeName");
        $specialName = input("specialName")==null?"":input("specialName");
        $mainTeacher = input("mainTeacher")==null?"":input("mainTeacher");
        $bornDate = input("bornDate")==null?"":input("bornDate");
        $classInfoRs = $this->classInfoModel->queryOutputClassInfo($classNo,$className,$collegeName,$specialName,$mainTeacher,$bornDate);
        $expTableData = [];
        foreach($classInfoRs as $classInfoRow) {
            $expTableData[] = $classInfoRow;
        }
        $xlsName = "ClassInfo";
        $xlsCell = array(
            array('classNo','班级编号','string'),
            array('className','班级名称','string'),
            array('collegeName','所在学院','string'),
            array('specialName','所在专业','string'),
            array('mainTeacher','班主任','string'),
            array('bornDate','成立日期','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

