<?php
namespace app\common\model;
use think\Model;

class InnovateModel extends Model {
    /*关联表名*/
    protected $table  = 't_innovate';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //创新类型复合属性的获取: 多对一关系
    public function innovateTypeObjF(){
        return $this->belongsTo('InnovateTypeModel','innovateTypeObj');
    }

    //申请的学生复合属性的获取: 多对一关系
    public function studentObjF(){
        return $this->belongsTo('StudentModel','studentObj');
    }

    /*添加学生创新记录*/
    public function addInnovate($innovate) {
        $this->insert($innovate);
    }

    /*更新学生创新记录*/
    public function updateInnovate($innovate) {
        $this->update($innovate);
    }

    /*删除多条学生创新信息*/
    public function deleteInnovates($innovateIds){
        $innovateIdArray = explode(",",$innovateIds);
        foreach ($innovateIdArray as $innovateId) {
            $this->innovateId = $innovateId;
            $this->delete();
        }
        return count($innovateIdArray);
    }
    /*根据主键获取学生创新记录*/
    public function getInnovate($innovateId) {
        $innovate = InnovateModel::where("innovateId",$innovateId)->find();
        return $innovate;
    }

    /*按照查询条件分页查询学生创新信息*/
    public function queryInnovate($innovateTypeObj, $innovateTitle, $studentObj, $sqTime, $shenHeState, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($innovateTypeObj['innovateTypeId'] != 0) $where['innovateTypeObj'] = $innovateTypeObj['innovateTypeId'];
        if($innovateTitle != "") $where['innovateTitle'] = array('like','%'.$innovateTitle.'%');
        if($studentObj['user_name'] != "") $where['studentObj'] = $studentObj['user_name'];
        if($sqTime != "") $where['sqTime'] = array('like','%'.$sqTime.'%');
        if($shenHeState != "") $where['shenHeState'] = array('like','%'.$shenHeState.'%');
        $innovateRs = InnovateModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = InnovateModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $innovateRs;
    }

    /*按照查询条件查询所有学生创新记录*/
  public function queryOutputInnovate( $innovateTypeObj,  $innovateTitle,  $studentObj,  $sqTime,  $shenHeState) {
        $where = null;
        if($innovateTypeObj['innovateTypeId'] != 0) $where['innovateTypeObj'] = $innovateTypeObj['innovateTypeId'];
        if($innovateTitle != "") $where['innovateTitle'] = array('like','%'.$innovateTitle.'%');
        if($studentObj['user_name'] != "") $where['studentObj'] = $studentObj['user_name'];
        if($sqTime != "") $where['sqTime'] = array('like','%'.$sqTime.'%');
        if($shenHeState != "") $where['shenHeState'] = array('like','%'.$shenHeState.'%');
        $innovateRs = InnovateModel::where($where)->select();
        return $innovateRs;
    }

    /*查询所有学生创新记录*/
    public function queryAllInnovate(){
        $innovateRs = InnovateModel::where(null)->select();
        return $innovateRs;

    }

}
