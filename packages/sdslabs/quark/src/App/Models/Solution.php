<?php

namespace SDSLabs\Quark\App\Models;

use Illuminate\Database\Eloquent\Model;


class Solution extends Model
{
    public function problem()
    {
    	return $this->hasOne('SDSLabs\Quark\App\Models\Problem');
    }
}
