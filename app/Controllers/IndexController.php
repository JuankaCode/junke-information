<?php
namespace App\Controllers;
use App\Models\{Job, Project};

class IndexController extends BaseController {
  public function indexAction(){
    $jobs = Job::all();
    $projects = Project::all();
    $lastName = 'Mamani Flores';
    $login = False;

    return $this->renderHTML('index.twig',[
      'lastName'=>$lastName,
      'jobs'=>$jobs,
      'projects'=>$projects,
    ]);
  }
}
