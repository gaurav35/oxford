<?php

namespace Student\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class StudentTable {

    protected $tableGateway;
    private $class;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        //$adapter = $this->tableGateway->getAdapter();
        //$this->studentClass = new TableGateway('standard_class', $adapter);
    }

    public function fetchAll() {

        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getStudent($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function getAllStudent($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('class' => $id));
        if (empty($rowset)) {
            return false;
        }
        return $rowset;
    }

    public function checkStudent($class, $name, $roll, $admission) {
        $this->class = $class;
        $rowset = $this->tableGateway->select(array('class' => $this->class, 'student_name' => $name, 'roll_no' => $roll, 'admission_no' => $admission));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    public function getStudentWithClass($paginated = false) {


        if ($paginated) {
            // $select = new Select('student');
            // create a new Select object for the student album
            $select = $this->tableGateway->getSql()->select()->columns(array('id', 'student_name', 'father_name', 'mother_name', 'admission_no', 'roll_no', 'parent_mobile_1', 'parent_mobile_2', 'student_update_date'))->join('standard_class', 'student_detail.class = standard_class.id', array('class'), 'left');
            //echo "gaurav"; print_r($select);die;
            // $select = $this->tableGateway->selectWith($select);
            // create a new result set based on the student entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Student());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
                    // our configured select object
                    $select,
                    // the adapter to run it against
                    $this->tableGateway->getAdapter(),
                    // the result set to hydrate
                    $resultSetPrototype
            );
            // print_r($paginatorAdapter);die;
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        $select = $this->tableGateway->getSql()->select()->columns(array('id', 'student_name', 'father_name', 'mother_name', 'admission_no', 'roll_no', 'parent_mobile_1', 'parent_mobile_2', 'student_update_date'))->join('standard_class', 'student_detail.class = standard_class.id', array('class'), 'left');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function saveStudent(Student $student) {
        $data = array(
            'class' => $student->class,
            'student_name' => $student->student_name,
            'father_name' => $student->father_name,
            'mother_name' => $student->mother_name,
            'admission_no' => $student->admission_no,
            'roll_no' => $student->roll_no,
            'parent_mobile_1' => $student->parent_mobile_1,
            'parent_mobile_2' => $student->parent_mobile_2,
        );

        $id = (int) $student->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getStudent($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function saveStudentXls($student) {
        $data = array(
            'class' => $this->class,
            'student_name' => $student[0],
            'father_name' => $student[1],
            'mother_name' => $student[2],
            'parent_mobile_1' => $student[3],
            'parent_mobile_2' => $student[4],
            'roll_no' => $student[5],
            'admission_no' => $student[6],
        );
        $result = $this->checkStudent($data['class'], $data['student_name'], $data['roll_no'], $data['admission_no']);
        if (empty($result)) {
            $this->tableGateway->insert($data);
        } else {
            $this->tableGateway->update($data, array('class' => $data['class'], 'roll_no' => $data['roll_no']));
        }
    }

    public function deleteStudent($id) {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function getStudentByClass($class_id) {

        $select = $this->tableGateway->getSql()->select()->columns(array('id', 'student_name', 'father_name', 'mother_name', 'admission_no', 'roll_no', 'parent_mobile_1', 'parent_mobile_2', 'student_update_date'))->join('standard_class', 'student_detail.class = standard_class.id', array('class'), 'left');

        $select->where(array('standard_class.id' => $class_id));

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

}
