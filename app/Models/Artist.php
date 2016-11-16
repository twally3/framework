<?php

use Framework\Core\ORM\ORM as ORM;

class Artist extends ORM {
  // has many albums
  public function albums() {
    return $this->hasMany('album');
  }
}
