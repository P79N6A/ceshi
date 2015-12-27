<?php namespace Models;

use Base\Model;

class Creative extends Model {

    protected $connection = 'app';

    public function campaigns()
    {
        return $this->hasMany('\Models\Campaign');
    }

    public function customer()
    {
        return $this->belongsTo('\Models\Customer');
    }
}