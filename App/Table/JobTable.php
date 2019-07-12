<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 06/06/19
 * Time: 15:16
 */

namespace App\Table;


use Core\Table\Table;

class JobTable extends Table
{
    public function getAll(){
        return $this->query("SELECT * FROM job");
    }

    public function jobById($id){
        return $this->query("SELECT * FROM job WHERE job.id=?",[$id],true);
    }
}