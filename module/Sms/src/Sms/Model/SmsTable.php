<?php
namespace Sms\Model;

 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Select;
 use Zend\Paginator\Adapter\DbSelect;
 use Zend\Paginator\Paginator;

class SmsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        
        if ($paginated) {
                $select = new Select('sms_template');
                   //$select = $this->tableGateway->select();
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Sms());
      
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

    public function getSms($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSms(Sms $sms)
    {
        $data = array(
            'sms_title' => $sms->sms_title,
            'sms_temp'  => $sms->sms_temp,
        );

        $id = (int)$sms->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getSms($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteSms($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}