<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\InnovateTypeModel;

class InnovateType extends BackBase {
    protected $innovateTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->innovateTypeModel = new InnovateTypeModel();
    }

    /*添加创新类型信息*/
    public function add(){
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
            return view('innovateType/innovateType_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("innovateTypeId",input("innovateTypeId"));
        return view("innovateType/innovateType_modify");
    }

    /*ajax方式按照查询条件分页查询创新类型信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->innovateTypeModel->setRows($this->request->param("rows"));
            $innovateTypeName = input("innovateTypeName")==null?"":input("innovateTypeName");
            $innovateTypeRs = $this->innovateTypeModel->queryInnovateType($innovateTypeName, $this->currentPage);
            $expTableData = [];
            foreach($innovateTypeRs as $innovateTypeRow) {
                $expTableData[] = $innovateTypeRow;
            }
            $data["rows"] = $innovateTypeRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->innovateTypeModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("innovateType/innovateType_query");
        }
    }

    /*ajax方式查询创新类型信息*/
    public function listAll() {
        $innovateTypeRs = $this->innovateTypeModel->queryAllInnovateType();
        echo json_encode($innovateTypeRs);
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

    /*按照查询条件导出创新类型信息到Excel*/
    public function outToExcel() {
        $innovateTypeName = input("innovateTypeName")==null?"":input("innovateTypeName");
        $innovateTypeRs = $this->innovateTypeModel->queryOutputInnovateType($innovateTypeName);
        $expTableData = [];
        foreach($innovateTypeRs as $innovateTypeRow) {
            $expTableData[] = $innovateTypeRow;
        }
        $xlsName = "InnovateType";
        $xlsCell = array(
            array('innovateTypeId','创新类型id','int'),
            array('innovateTypeName','创新类型名称','string'),
            array('sjnr','实践内容','string'),
            array('xuefen','学分','float'),
            array('cjjz','成绩记载','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

