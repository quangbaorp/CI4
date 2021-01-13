<?php

namespace App\Controllers;

class Admin extends BaseController
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
                if($user['role'] === 1){
                    $this->setUserSession($user);
                    return redirect()->to('manager/admin');
                }
                else {
                    $session->setFlashdata('error', 'Bạn không có quyền truy cập vào Admin');
                }


			}
        }
        // echo view('templates/header', $data);
    }
    private function setUserSession($user){
		$data = [
			'id' => $user['id'],
			'fullname' => $user['firstname'],
			'email' => $user['email'],
            'isLoggedIn' => true,
            'role' => $user['role'],
		];

		session()->set($data);
		return true;
    }
    
    public function logout(){
		session()->destroy();
		return redirect()->to('/auth/login');
    }
}