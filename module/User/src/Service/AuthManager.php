<?php
namespace User\Service;

use Laminas\Authentication\Result;

/**
 * The AuthManager service is responsible for user's login/logout and simple access 
 * filtering. The access filtering feature checks whether the current visitor 
 * is allowed to see the given page or not.  
 */
class AuthManager
{
    const ACCESS_GRANTED =  1;
    const ACCESS_DENIED =  3;
    const AUTH_REQUIRED =  2;

    /**
     * Authentication service.
     * @var \Laminas\Authentication\AuthenticationService
     */
    private $authService;
    
    /**
     * Session manager.
     * @var Laminas\Session\SessionManager
     */
    private $sessionManager;
    
    /**
     * Contents of the 'access_filter' config key.
     * @var array 
     */
    private $config;
    
    /**
     * Constructs the service.
     */
    public function __construct($authService, $sessionManager, $config) 
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
    }
    
    /**
     * Performs a login attempt. If $rememberMe argument is true, it forces the session
     * to last for one month (otherwise the session expires on one hour).
     */
    public function login($email, $password, $rememberMe)
    {   
        // Check if user has already logged in. If so, do not allow to log in 
        // twice.
        if ($this->authService->getIdentity()!=null) {
                    //redirect to the login action of authController
            header('Location: home ');
            //throw new \Exception('Already logged in');
        }
            
        // Authenticate with login/password.
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);
        $result = $this->authService->authenticate();

        // If user wants to "remember him", we will make session to expire in 
        // one month. By default session expires in 1 hour (as specified in our 
        // config/global.php file).
        if ($result->getCode()==Result::SUCCESS && $rememberMe) {
            // Session cookie will expire in 1 month (30 days).
            $this->sessionManager->rememberMe(60*60*24*30);
        }
        
        return $result;
    }
    
    /**
     * Performs user logout.
     */
    public function logout()
    {
       
        // Allow to log out only when user is logged in.
        if ($this->authService->getIdentity()==null) { 
            header("location: login");
           // throw new \Exception('The user is not logged in');
        }
        
        // Remove identity from session.
        $this->authService->clearIdentity();               
    }
    
/**
 * This is a simple access control filter. It is able to restrict unauthorized
 * users to visit certain pages.
 * 
 * This method uses the 'access_filter' key in the config file and determines
 * whenther the current visitor is allowed to access the given controller action
 * or not. It returns true if allowed; otherwise false.
 */
public function filterAccess($controllerName, $actionName)
{
    

    // Determine mode - 'restrictive' (default) or 'permissive'. In restrictive
    // mode all controller actions must be explicitly listed under the 'access_filter'
    // config key, and access is denied to any not listed action for unauthorized users. 
    // In permissive mode, if an action is not listed under the 'access_filter' key, 
    // access to it is permitted to anyone (even for not logged in users.
    // Restrictive mode is more secure and recommended to use.
    $mode = isset($this->config['options']['mode'])?$this->config['options']['mode']:'restrictive';
    if ($mode!='restrictive' && $mode!='permissive')
        throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');
    
    if (isset($this->config['controllers'][$controllerName])) {
        $items = $this->config['controllers'][$controllerName];
        foreach ($items as $item) {
            $actionList = $item['actions'];
            $allow = $item['allow'];
            if (is_array($actionList) && in_array($actionName, $actionList) ||
                $actionList=='*') {
                 if (!$this->authService->hasIdentity()) {
                    // Only authenticated user is allowed to see the page.
                    return self::AUTH_REQUIRED;                        
                }               
                if ($allow=='*')
                    // Anyone is allowed to see the page.
                    return self::ACCESS_GRANTED; 
   
                if ($allow=='@') {
                    // Any authenticated user is allowed to see the page.
                    return self::ACCESS_GRANTED;                         
                } else if (substr($allow, 0, 1)=='@') {
                    // Only the user with specific identity is allowed to see the page.
                    $identity = substr($allow, 1);
                    if ($this->authService->getIdentity()==$identity)
                        return self::ACCESS_GRANTED; 
                    else
                        return self::ACCESS_DENIED;
                } else if (substr($allow, 0, 1)=='+') {
                    // Only the user with this permission is allowed to see the page.
                    $permission = substr($allow, 1);
                    if ($this->rbacManager->isGranted(null, $permission))
                        return self::ACCESS_GRANTED; 
                    else
                        return self::ACCESS_DENIED;
                } else {
                    throw new \Exception('Unexpected value for "allow" - expected ' .
                            'either "?", "@", "@identity" or "+permission"');
                }
            }
        }            
    }
    
    // In restrictive mode, we require authentication for any action not 
    // listed under 'access_filter' key and deny access to authorized users 
    // (for security reasons).
    if ($mode=='restrictive') {
        if(!$this->authService->hasIdentity())
            return self::AUTH_REQUIRED;
        else
            return self::ACCESS_DENIED;
    }
    
    // Permit access to this page.
    return self::ACCESS_GRANTED;
}

}