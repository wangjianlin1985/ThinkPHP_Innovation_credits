<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\ClassInfoModel;

class ClassInfo extends Base {
    protected $classInfoModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->classInfoModel = new ClassInfoModel();
    }

    /*添加班级信息*/
    public function frontAdd(){
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
            return $this->fetch('classInfo/classInfo_frontAdd');
        }
    }

    /*前台修改班级信息*/
    public function frontModify() {
        $this->assign("classNo",input("classNo"));
        return $this->fetch("classInfo/classInfo_frontModify");
    }

    /*前台按照查询条件分页查询班级信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $classNo = input("classNo")==null?"":input("classNo");
        $className = input("className")==null?"":input("className");
        $collegeName = input("collegeName")==null?"":input("collegeName");
        $specialName = input("specialName")==null?"":input("specialName");
        $mainTeacher = input("mainTeacher")==null?"":input("mainTeacher");
        $bornDate = input("bornDate")==null?"":input("bornDate");
        $classInfoRs = $this->classInfoModel->queryClassInfo($classNo, $className, $collegeName, $specialName, $mainTeacher, $bornDate, $this->currentPage);
        $this->assign("classInfoRs",$classInfoRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->classInfoModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->classInfoModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->classInfoModel->rows);
        $this->assign("classNo",$classNo);
        $this->assign("className",$className);
        $this->assign("collegeName",$collegeName);
        $this->assign("specialName",$specialName);
        $this->assign("mainTeacher",$mainTeacher);
        $this->assign("bornDate",$bornDate);
        return $this->fetch('classInfo/classInfo_frontlist');
    }

    /*ajax方式查询班级信息*/
    public function listAll() {
        $classInfoRs = $this->classInfoModel->queryAllClassInfo();
        echo json_encode($classInfoRs);
    }
    /*前台查询根据主键查询一条班级信息*/
    public function frontshow() {
        $classNo = input("classNo");
        $classInfo = $this->classInfoModel->getClassInfo($classNo);
       $this->assign("classInfo",$classInfo);
        return $this->fetch("classInfo/classInfo_frontshow");
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

}

