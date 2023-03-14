<?php

class BaseService
{
    protected $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function get_all()
    {
        return $this->dao->get_all();
    }

    public function get_by_id($id)
    {
        return $this->dao->get_by_id($id);
    }

    public function add_element($element)
    {
        return $this->dao->add_element($element);
    }

    public function update_element($id, $entity)
    {
        return $this->dao->update_element($id, $entity);
    }

    public function delete_element($id)
    {
        return $this->dao->delete_element($id);
    }

}

?>