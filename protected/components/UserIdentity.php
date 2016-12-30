<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    private $_id;
	private $_pid;
	private $_groupid;
	private $_data;
    public function authenticate()
    {
        $username = strtolower($this->username);
        $user = SysUser::model()->find('LOWER(username)=?', array(
            $username
        ));
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else 
            if (! $user->validatePassword($this->password))
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            else {
                $this->_id = $user->id;
                $this->username = $user->username;
                $this->_pid = $user->pid;
                $this->_groupid = $user->groupid;
                $this->_data = $user;
                $this->errorCode = self::ERROR_NONE;
            }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
    public function getPid()
    {
    	return $this->_pid;
    }
    public function getGroupid()
    {
    	return $this->_groupid;
    }
    public function getUser()
    {
    	return  $this->_data;
    }
}