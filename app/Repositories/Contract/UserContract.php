<?php

namespace App\Repositories\Contract;

interface UserContract
{


//    public function find($id);
    public function create(array $data);
    public function update($id,array $data);

    // public function update($id, array $data);
     public function delete($id);
}
