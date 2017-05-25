<?php

namespace Litmos;

use Litmos\User\Basic as UserBasic;

class Users
{

    /**
     * @var Service
     */
    private $service;

    public function __construct(Service $litmos_service)
    {
        $this->service = $litmos_service;
    }

    /**
     * @param PagingSearch $ps
     *
     * @return UserBasic[]
     */
    public function getAll(PagingSearch $ps = null)
    {
        $response = $this->service->get('/users', $ps);
		$xml         = new \SimpleXMLElement($response);
		$users_nodes = $xml->children();
		foreach ($users_nodes as $element) {
		  $one=array();
		  foreach($element as $key => $val) {
		   $one[$key] =(string)$val;
		  }
		
		   $users[]=$one;
		}
		

       /* $xml         = new \SimpleXMLElement($response);
        $users_nodes = $xml->children();

        $users = array();
        foreach ($users_nodes as $user_node) {
            $id         = (string)$user_node->Id;
            $username   = (string)$user_node->UserName;
            echo $first_name = (string)$user_node->FirstName;
            $last_name  = (string)$user_node->LastName;
            $users[]    = new UserBasic($this->service, $id, $username, $first_name, $last_name);
        }*/

        return $users;
    }

    /**
     * @param string|UserBasic $user_id
     * @return User
     */
    public function get($user_id,$returnKey=NULL)
    {
        if ($user_id instanceof UserBasic) {
            $user_id = $user_id->getUserId();
        }

        if (empty($user_id)) {
            throw new Exception\InvalidArgumentException('No User Id was specified.');
        }

        $response = $this->service->get("/users/{$user_id}");

        return User::FromXml($this->service, $response,$returnKey);
    }

    /**
     * @param string $user_name
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $skype
     * @param string $phone_work
     * @param string $phone_mobile
     * @param bool   $skip_first_login
     *
     * @return User
     */
    public function create(
        $user_name,
        $first_name,
        $last_name,
        $email = '',
        $skype = '',
        $phone_work = '',
        $phone_mobile = '',
        $skip_first_login = true
    ) {
		 
		
		
			
        $user = new User(
            $this->service,
            '',
            $user_name,
            $first_name,
            $last_name,
            '',
            $email,
            'Learner', //  $access_level,
            false,  // $disable_messages,
            true,//$active,
            $skype, 
            $phone_work,
            $phone_mobile,
            // '', //$last_login new \DateTime()
            '', 
            $skip_first_login  //By default all new users that you create will be prompted to change their password the first time they login. If you are using the single sign-on approach then this may not be desirable. To stop this from happening you need to set the SkipFirstLogin field to true. 
        );

        $req_xml = $user->toXml();
		//var_dump( $req_xml);
		//return;

        $rep_xml = $this->service->post('/users', $req_xml);
        // var_dump($rep_xml);
		// Get User ID from response XML.
	    return User::GetUserIDFromXml($this->service, $rep_xml);
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $xml = $user->toXml();

        $this->service->put("/users/{$user->getUserId()}", $xml);
    }
}
