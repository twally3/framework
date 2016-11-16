<?php

use Framework\Core\ORM\ORM as ORM;

class Listener extends ORM {

  protected static $timestamps = false;
  // protected static $primaryKey = 'listener_id';

  public function albums() {
    return $this->belongsToMany('Album');
  }

}
