<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model{
  protected $table = 'Projects';

  public function getDurationAsString(){
    $years = floor($this->months/12);
    $monthModule = $this->months%12;

    switch(0){
      case $years:
        return "$lasted for over: monthModule months";
      case $monthModule:
        return "lasted for over: $years years";
      default:
        return "lasted for over: $years years and $monthModule months";
    }
  }
}