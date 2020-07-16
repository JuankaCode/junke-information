<?php
namespace App\Controllers;
use App\Models\Job;
use Respect\Validation\Validator;

class JobsController extends BaseController{
  public function jobsAction($request){    
    $responseMessage=null;
    if($request->getMethod() == 'POST'){
      $postData = $request->getParsedBody();
      $jobValidator = Validator::key('title', Validator::stringType()->notEmpty())
                        ->key('description', Validator::stringType()->notEmpty());

    try{
        $jobValidator->assert($postData); 

        $files = $request->getUploadedFiles();
        $image = $files['logo'];

        if($image->getError()== UPLOAD_ERR_OK){
          $fileName= $image->getClientFilename();
          $image->moveTo("uploaded/$fileName");
        }
        $job = new Job();
        $job->title = $postData['title'];
        $job->description = $postData['description'];
        $job->months = settype($postData['months'],'int');
        $job->visible = settype($postData['visible'], 'bool');
        $job->save();
        $responseMessage = 'Save';
      } catch(\Exception $e){
        $responseMessage = $e->getMessage();
      }
    }

    return $this->renderHTML('addJob.twig',[
      'responseMessage'=>$responseMessage,
    ]);
  }
}
