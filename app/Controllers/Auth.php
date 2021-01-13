<?php

namespace App\Controllers;

class Auth extends BaseController
{
    // view login user
    public function login()
    {
        $data = [];
		helper(['form']);
        echo 'login';
        if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'email' => 'required|min_length[6]|max_length[50]|valid_email',
				'password' => 'required|min_length[8]|max_length[255]|validateUser[email,password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Email or Password don\'t match'
				]
			];

			if (! $this->validate($rules, $errors)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new User();

				$user = $model->where('email', $this->request->getVar('email'))
											->first();

				$this->setUserSession($user);
				//$session->setFlashdata('success', 'Successful Registration');
				return redirect()->to('dashboard');

			}
        }
        // echo view('templates/header', $data);
        
    }
    // end view login user
//  *************************************************
    // view register user

    public function register()
    {
        $data = [];
		helper(['form']);
        echo 'register';
        
		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'firstname' => 'required|min_length[3]|max_length[20]',
				'lastname' => 'required|min_length[3]|max_length[20]',
				'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
				'password' => 'required|min_length[8]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];

			if (! $this->validate($rules)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new User();

				$newData = [
					'fullname' => $this->request->getVar('firstname'),
					'email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                    'role' => 0,
                    'avatar' => "",
				];
				$model->save($newData);
				$session = session();
				$session->setFlashdata('success', 'Successful Registration');
				return redirect()->to('/auth/login');

			}
        }
        // echo view('templates/header', $data);
    }
    // end view regsiter user

    private function setUserSession($user){
		$data = [
			'id' => $user['id'],
			'firstname' => $user['firstname'],
			'lastname' => $user['lastname'],
			'email' => $user['email'],
			'isLoggedIn' => true,
		];

		session()->set($data);
		return true;
    }
    
    public function logout(){
		session()->destroy();
		return redirect()->to('/');
    }
    
// ****************************************************

   
}
