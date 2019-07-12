<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 14/06/19
 * Time: 17:57
 */

namespace App\Table;


use Core\Table\Table;

class AppliantTable extends Table
{
    public function apply($data){
        return $this->create([
            "member"=> $data["id"],
            "job"=> $data["job"],
            "description"=> $data["description"],
            "status"=> "pending",
            "diploma"=> $data["diploma"],
            "disponibility"=> "MON"
        ]);
    }

    public function all(){
        return $this->query("SELECT * FROM appliant");
    }

    public function accept($id){
        $member = $this->query("SELECT member FROM appliant where appliant.id=?",[$id],true);
        return $this->query("UPDATE member set status='volunteer' where member.id=?",[$member->member]) && $this->query("UPDATE appliant SET status='active' WHERE appliant.id=?",[$id]);
    }

    public function refuse($id){
        $member = $this->query("SELECT member FROM appliant where appliant.id=?",[$id],true);
        return $this->delete($id) && $this->query("UPDATE member SET status='user' WHERE member.id=?",[$member->member]);
    }

    public function retreat($id){
        $member = $this->query("SELECT member FROM appliant where appliant.id=?",[$id],true);
        return $this->query("UPDATE appliant SET status='retire' WHERE appliant.id=?",[$id]) && $this->query("UPDATE member SET status='user' WHERE member.id=?",[$member->member]);
    }
}