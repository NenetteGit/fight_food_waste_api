<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-06-15
 * Time: 17:02
 */

namespace App\Table;


use Core\Table\Table;

class SubscribeTable extends Table
{
    public function subscribe($id, $data)
    {
        $member = $this->query("SELECT id FROM member WHERE member.token = ?", [$data['token']], true);

        return $this->create([
            "subscriber" => $member->id,
            "offer" => $id,
            'offer_end' => $data['offer_end']
        ]);
    }
}