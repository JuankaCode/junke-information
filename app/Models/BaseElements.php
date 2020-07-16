<?php
namespace App\Models;

class BaseElements {
  protected $title;
  public $description;
  public $months;
  public $visible;

  public function __construct($title, $description){
    $this->setTitle($title);
    $this->description = $description;
  }

  public function setTitle($title){
    if($title === ''){
      $this->title = 'N/A';
    } else{
      $this->title = $title;
    }
  }

  public function getTitle(){
    return $this->title;
  }

  public function getDurationAsString(){
    $years = floor($this->months/12);
    $monthModule = $this->months%12;

    switch(0){
      case $years:
        return "$monthModule months";
      case $monthModule:
        return "$years years";
      default:
        return "$years years and $monthModule months";
    }
  }

};



