<?php

class User {

	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				if($this->findUserID($user)) {
					$this->_isLoggedIn = true;
				} else {
					// process logout
				}
			}
		} else {
			$this->find($user);
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating this account.');
		}
	}

	public function update($fields = array(), $id = null) {

		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}

		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function findIdNumber($user = null) {
		if($user) {
			$field = 'id_number';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function findUserID($user = null) {
		if($user) {
			$field = is_numeric($user) ? 'id' : 'email';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function find($user = null) {
		if($user) {
			$field = is_numeric($user) ? 'id_number' : 'email';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($username = null, $password = null, $remember = false) {

		// check if username has been defined 
		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		}else {
			$user = $this->find($username);

			if($user) {
				if(password_verify($password, $this->data()->password)) {
					Session::put($this->_sessionName, $this->data()->id);

					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('user_sessions', array('user_id', '=', $this->data()->id));

						if(!$hashCheck->count()) {
							$query = $this->_db->insert('user_sessions', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					return true;
				}
			}
		}

		return false;
	}

	public function hasPermission($key) {
		$group = $this->_db->get('roles', array('id', '=', $this->data()->role_id));
		if($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);
			if($permissions[$key] == true || $permissions[$key] == 1) {
				return true;
			}
		}
		return false;
	}

	public function getStaffs() {
		$group = $this->_db->get('roles', array('name', '=', 'Staff'));

		if($group->count()) {
			$users = $this->_db->query(
				'SELECT * FROM users WHERE role_id = ?', [$group->first()->id]);

			return $users->results();
		}

		return false;
	}

	public function role() {
		$group = $this->_db->get('roles', array('id', '=', $this->data()->role_id));
		if($group->count()) {
			return strtolower($group->first()->name);
		}
		return '';
	}

	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	public function logout() {

		$this->_db->delete('user_session', array('user_id', '=', $this->data()->id));

		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function data() {
		return $this->_data;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}	

}