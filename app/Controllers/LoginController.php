<?php
namespace App\Controllers;
use App\Models\Users;
use Respect\Validation\Validator;
use Zend\Diactoros\Response\RedirectResponse;

class LoginController extends BaseController{
  public function loginAction(){
    return $this->renderHTML('login.twig');
  }
  public function postLogin($request){
    $postData = $request->getParsedBody();

    $user = Users::where('user', $postData['user'])->first();

    if($user && \password_verify($postData['password'], $user->password)){
      $_SESSION['userId'] = $user['id_user'];
      return new RedirectResponse('/admin');
    }else{
      return $this->renderHTML('login.twig',[
        'messageError'=>'Bad Credentials',
      ]);
    }
  }

  public function logout(){
    unset($_SESSION['userId']);
    return new RedirectResponse('login');
  }
}