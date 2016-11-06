<?php

class Album extends ORM {

  // protected static $primaryKey = 'album_id';

  //belongs to one Artist
  function artist() {
    return $this->belongsTo('artist');
  }

  function listeners() {
    return $this->belongsToMany('listener');
  }

}
