<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use HasFactory;
   
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'image',
        'gender',
        'skills',
    ];

    protected $dates = ['deleted_at'];

    /*  public function setSkillsAttribute($value)
    {
        $this->attributes['skills'] = json_encode($value);
    }

   public function getSkillsAttribute($value)
    {
        return $this->attributes['skills'] = json_decode($value);
    } */
    public function getSkillsAttribute($value)
    {
        return explode(',', $value);
    }

    // Define a mutator to save the skills as a comma-separated string
    public function setSkillsAttribute($value)
    {
        $this->attributes['skills'] = implode(',', $value);
    }
}
