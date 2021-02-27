<?php

namespace App;

class News extends BaseModel
{
    function getLocalizedProperty($language_id){
        $this->getLocalizedProperties(class_basename($this), $language_id);
    }

    function images(){
       return $this->hasMany(NewsImage::class);
    }
}
