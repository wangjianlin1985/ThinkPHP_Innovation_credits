<?php
namespace app\common\model;
use think\Model;

class StudentModel extends Model {
    /*关联表名*/
    protected $table  = 't_student';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //所在班级复合属性的获取: 多对一关系
    public function classObjF(){
        return $this->belongsTo('ClassInfoModel','classObj');
    }

    /*添加学生记录*/
    public function addStudent($student) {
        $this->insert($student);
    }

    /*更新学生记录*/
    public function updateStudent($student) {
        $this->update($student);
    }

    /*删除多条学生信息*/
    public function deleteStudents($user_names){
        $user_nameArray = explode(",",$user_names);
        foreach ($user_nameArray as $user_name) {
            $this->user_name = $user_name;
            $this->delete();
        }
        return count($user_nameArray);
    }
    /*根据主键获取学生记录*/
    public function getStudent($user_name) {
        $student = StudentModel::where("user_name",$user_name)->find();
        return $student;
    }

    /*按照查询条件分页查询学生信息*/
    public function queryStudent($user_name, $classObj, $name, $birthDate, $telephone, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($user_name != "") $where['user_name'] = array('like','%'.$user_name.'%');
        if($classObj['classNo'] != "") $where['classObj'] = $classObj['classNo'];
        if($name != "") $where['name'] = array('like','%'.$name.'%');
        if($birthDate != "") $where['birthDate'] = array('like','%'.$birthDate.'%');
        if($telephone != "") $where['telephone'] = array('like','%'.$telephone.'%');
        $studentRs = StudentModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = StudentModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $studentRs;
    }

    /*按照查询条件查询所有学生记录*/
  public function queryOutputStudent( $user_name,  $classObj,  $name,  $birthDate,  $telephone) {
        $where = null;
        if($user_name != "") $where['user_name'] = array('like','%'.$user_name.'%');
        if($classObj['classNo'] != "") $where['classObj'] = $classObj['classNo'];
        if($name != "") $where['name'] = array('like','%'.$name.'%');
        if($birthDate != "") $where['birthDate'] = array('like','%'.$birthDate.'%');
        if($telephone != "") $where['telephone'] = array('like','%'.$telephone.'%');
        $studentRs = StudentModel::where($where)->select();
        return $studentRs;
    }

    /*查询所有学生记录*/
    public function queryAllStudent(){
        $studentRs = StudentModel::where(null)->select();
        return $studentRs;

    }

}
