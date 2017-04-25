<?php
/*
 * V1.0 Silex Authentication module
 * @Author : Jagadeeshraj V S
 * @Date : 30-08-2016
 * @Puropose : Stub Apis for "Authentication Module" using silex framework.
 */

namespace QuizPlat\EndUser\Authentication;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Classname : AuthenticationController;
 * controller class which defines all the authentication skeleton methods.
 */


   
class AuthenticationController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * @SWG\Get(
     *     path="/login",
     *     summary="User authentication token genration of Quizzing Platform",
     *     operationId="login",
     *     produces={"application/json"},
     *     tags={"authentication"},
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="query",
     *         description="External integrated client Id",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="secret_code",
     *         in="query",
     *         description="Secret code provided during registeration",
     *         required=true,
     *         type="string"
     *        
     *     ),     
     *     @SWG\Response(
     *         response="200",
     *         description="Successfull Response",
     *         @SWG\Schema(
     *             ref="#/definitions/loginDto"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="not found"
     *     )      
     * )
     */ 

    /**
     *     @SWG\Definition(
     *         definition="loginDto",
     *         required={"token"},
     *         @SWG\Property(
     *             property="token",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="expires_in",
     *             type="integer",
     *             format="int32"
     *         )     
     * )
     */

    public function login(Request $request)
    {
         if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 
            //print_r($request);die;
            $clientId = $request->get('client_id');
            $secretCode = $request->get('secret_code');
            $signature = md5($clientId.'#'.$secretCode);
            $response = array("token"=>$signature,"expiresIn"=>60*60);
            return new JsonResponse($response);
        }else{
             $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;    
             return new JsonResponse($errorMsg,'401'); 
        }
    } 

   
}
