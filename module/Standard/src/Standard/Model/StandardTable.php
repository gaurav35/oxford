<?php
namespace Standard\Model;

 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Select;
 use Zend\Paginator\Adapter\DbSelect;
 use Zend\Paginator\Paginator;

class StandardTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
                $select = new Select('standard_class');
                   //$select = $this->tableGateway->select();
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Standard());
      
                // create a new pagination adapter object
                $paginatorAdapter = new DbSelect(
               // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGateway->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
        );
  
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
            
        }
             $resultSet = $this->tableGateway->select();
             return $resultSet;
    }

    public function getStandard($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveStandard(Standard $standard)
    {
        $data = array(
            'class' => $standard->class,
        );

        $id = (int)$standard->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getStandard($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteStandard($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}