<?
namespace App\Controllers;
use App\Models\Users; 
use Respect\Validation\Validator;

class UsersController extends BaseController{
  public function usersAction($request){
    $messageResponse = null;
    if($request->getMethod() == 'POST'){
      $postData = $request->getParsedBody();
      $userValidator = Validator::key('user', Validator::stringType()->notEmpty())
                        ->key('email', Validator::stringType()->notEmpty());
      try{
        $userValidator->assert($postData);

        $user = new Users();
        $user->user = $postData['user'];
        $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
        $user->email= $postData['email'];
        $user->save();
        
        $messageResponse='Save'; 
      }catch(\Exception $e){
        $messageResponse = $e->getMessage();        
      }
    }

    return $this->renderHTML('register.twig',[
      'messageResponse'=>$messageResponse, 
    ]); 
  } 
}
