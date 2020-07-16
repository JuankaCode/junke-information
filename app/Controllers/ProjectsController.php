<?php
namespace App\Controllers;
use App\Models\Project;

class ProjectsController extends BaseController{
  public function projectAction(){
    if(!empty($_POST)){
    $project = new Project();
    $project->title = $_POST['title'];
    $project->description = $_POST['description'];
    $project->save();
    }

    return $this->renderHTML('addProject.twig');
  }
}
