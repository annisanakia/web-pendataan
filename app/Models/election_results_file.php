<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class election_results_file extends Model {

    use SoftDeletes;

    protected $table = 'election_results_file';
    protected $guarded = ['id'];

    public function election_results()
    {
        return $this->belongsTo('Models\election_results');
    }
}