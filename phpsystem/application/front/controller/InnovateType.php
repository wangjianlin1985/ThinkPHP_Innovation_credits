<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\InnovateTypeModel;

class InnovateType extends Base {
    protected $innovateTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->innovateTypeModel = new InnovateTypeModel();
    }

    /*添加创新类型信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $innovateType = $this->getInnovateTypeForm(true);
            try {
                $this->innovateTypeModel->addInnovateType($innovateType);
                $message = "创新类型添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "创新类型添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('innovateType/innovateType_frontAdd');
        }
    }

    /*前台修改创新类型信息*/
    public function frontModify() {
        $this->assign("innovateTypeId",input("innovateTypeId"));
        return $this->fetch("innovateType/innovateType_frontModify");
    }

    /*前台按照查询条件分页查询创新类型信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $innovateTypeName = input("innovateTypeName")==null?"":input("innovateTypeName");
        $innovateTypeRs = $this->innovateTypeModel->queryInnovateType($innovateTypeName, $this->currentPage);
        $this->assign("innovateTypeRs",$innovateTypeRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->innovateTypeModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->innovateTypeModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->innovateTypeModel->rows);
        $this->assign("innovateTypeName",$innovateTypeName);
        return $this->fetch('innovateType/innovateType_frontlist');
    }

    /*ajax方式查询创新类型信息*/
    public function listAll() {
        $innovateTypeRs = $this->innovateTypeModel->queryAllInnovateType();
        echo json_encode($innovateTypeRs);
    }
    /*前台查询根据主键查询一条创新类型信息*/
    public function frontshow() {
        $innovateTypeId = input("innovateTypeId");
        $innovateType = $this->innovateTypeModel->getInnovateType($innovateTypeId);
       $this->assign("innovateType",$innovateType);
        return $this->fetch("innovateType/innovateType_frontshow");
    }

    /*更新创新类型信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $innovateType = $this->getInnovateTypeForm(false);
            try {
                $this->innovateTypeModel->updateInnovateType($innovateType);
                $message = "创新类型更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "创新类型更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取创新类型对象*/
            $innovateTypeId = input("innovateTypeId");
            $innovateType = $this->innovateTypeModel->getInnovateType($innovateTypeId);
            echo json_encode($innovateType);
        }
    }

    /*删除多条创新类型记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $innovateTypeIds = input("innovateTypeIds");
        try {
            $count = $this->innovateTypeModel->deleteInnovateTypes($innovateTypeIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

