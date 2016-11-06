<?php

class Artist extends ORM {
  // has many albums
  public function albums() {
    return $this->hasMany('album');
  }
}
