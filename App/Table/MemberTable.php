<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 08/04/2019
 * Time: 01:22
 */

namespace App\Table;

use Core\Table\Table;

class MemberTable extends Table
{

    public function all()
    {
        return $this->query('SELECT id, status, firstname, lastname, email, phone FROM ' . $this->table . ' ORDER BY id ASC;');
    }

    public function find($id)
    {
        return $this->query("SELECT id, status, firstname, lastname, email, phone, building FROM {$this->table} WHERE id = ?", [$id], true);
    }

    public function updateToken($id,$token){
        return $this->query("UPDATE member set member.token=? where member.id=?",[$token,$id],true);
    }

    public function verifyToken($id,$token){
        return $this->query("SELECT 1 from member where member.token=? and member.id=?",[$token,$id],true);
    }

    public function getApplicationStatus($id,$token){
        return $this->query("SELECT 1 from member where member.token=? and member.id=? and status='appliant'",[$token,$id],true);
    }

    public function candidate($data){
        return $this->query('UPDATE member set status="appliant" where member.id=?',[$data["id"]],true);
    }

    public function getIdMember($email){
        return $this->query("SELECT id from member where email = ?",[$email],true);
    }
}
