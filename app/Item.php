<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
   Protected $fillable=['name','quantity','manufacture_date'];
}
