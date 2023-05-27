<?php
namespace app\common\model;
use think\Model;

class InnovateTypeModel extends Model {
    /*关联表名*/
    protected $table  = 't_innovateType';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加创新类型记录*/
    public function addInnovateType($innovateType) {
        $this->insert($innovateType);
    }

    /*更新创新类型记录*/
    public function updateInnovateType($innovateType) {
        $this->update($innovateType);
    }

    /*删除多条创新类型信息*/
    public function deleteInnovateTypes($innovateTypeIds){
        $innovateTypeIdArray = explode(",",$innovateTypeIds);
        foreach ($innovateTypeIdArray as $innovateTypeId) {
            $this->innovateTypeId = $innovateTypeId;
            $this->delete();
        }
        return count($innovateTypeIdArray);
    }
    /*根据主键获取创新类型记录*/
    public function getInnovateType($innovateTypeId) {
        $innovateType = InnovateTypeModel::where("innovateTypeId",$innovateTypeId)->find();
        return $innovateType;
    }

    /*按照查询条件分页查询创新类型信息*/
    public function queryInnovateType($innovateTypeName, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($innovateTypeName != "") $where['innovateTypeName'] = array('like','%'.$innovateTypeName.'%');
        $innovateTypeRs = InnovateTypeModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = InnovateTypeModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $innovateTypeRs;
    }

    /*按照查询条件查询所有创新类型记录*/
  public function queryOutputInnovateType( $innovateTypeName) {
        $where = null;
        if($innovateTypeName != "") $where['innovateTypeName'] = array('like','%'.$innovateTypeName.'%');
        $innovateTypeRs = InnovateTypeModel::where($where)->select();
        return $innovateTypeRs;
    }

    /*查询所有创新类型记录*/
    public function queryAllInnovateType(){
        $innovateTypeRs = InnovateTypeModel::where(null)->select();
        return $innovateTypeRs;

    }

}
