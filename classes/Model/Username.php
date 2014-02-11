<?php

namespace Deployer\Model;

class Username extends \Illuminate\Database\Eloquent\Model
{
    public function user()
    {
        return $this->belongsTo('Deployer\Model\User');
    }
}
