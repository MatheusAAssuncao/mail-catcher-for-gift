<?php

namespace App\Controllers;

use App\Libraries\Log;
use App\Libraries\NexmoSMS;
use App\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Oferta EA Live!',
            'errors' => [],
        ];
        
        if (session()->has('errors')) {
            $data['errors'] = session()->getFlashdata('errors');
        }

        return view('home', $data);
    }

    public function submit()
    {
        $validation = $this->validate($this->_getValidationRules());
        if (empty($validation)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789ABCDEFGHJKLMNPQRSTUVWXYZ123456789ABCDEFGHJKLMNPQRSTUVWXYZ123456789ABCDEFGHJKLMNPQRSTUVWXYZ123456789";
        $code = substr(str_shuffle($chars), 0, 6);

        $_model = new UserModel();
        $ret = $_model->saveUser([
            'name' => mb_strtoupper($this->request->getPost('name')),
            'email' => mb_strtolower($this->request->getPost('email')),
            'phone' => $this->request->getPost('phone'),
            'code' => $code
        ]);

        if (empty($ret['result'])) {
            $message = empty($ret['message']) ? 'Erro ao salvar.' : $ret['message'];
            Log::add('Home::submit', Log::ERROR, $message, ['ret' => $ret, 'phone' => $this->request->getPost('phone')]);
            return redirect()->back()
                ->withInput()
                ->with('errors', ['term' => $message]);
        }

        $message = "Apresente o codigo " . $code . " para receber o seu cartao de oferta de 1 copo de vinho EA";
        $_sender = new NexmoSMS();
        $ret = $_sender->sendSMS($this->request->getPost('phone'), $message);
        Log::add('Home::submit', Log::INFO, 'SMS Log', $ret);

        return redirect()->to('/success');
    }

    public function success()
    {
        $data = [
            'title' => 'Sucesso - Cartuxa',
        ];
        
        return view('success', $data);
    }

    public function term()
    {
        $data = [
            'title' => 'Política de privacidade e de tratamento de dados',
        ];
        
        return view('term', $data);
    }

    private function _getValidationRules()
    {
        return [
            'name' => [
                'label' => 'Nome',
                'rules' => ['required', 'min_length[6]', 'max_length[50]'],
                'errors' => [
                    'required' => 'O campo {field} é obrigatório',
                    'min_length' => 'O campo {field} deve ter, no mínimo, {param} caracteres',
                    'max_length' => 'O campo {field} deve ter, no máximo, {param} caracteres',
                ]
            ],
            'phone' => [
                'label' => 'Telemóvel',
                'rules' => ['required', 'regex_match[/^[0-9]*$/]', 'exact_length[9]'],
                'errors' => [
                    'required' => 'O campo {field} é obrigatório',
                    'regex_match' => 'O campo {field} deve conter apenas números',
                    'exact_length' => 'O campo {field} deve conter {param} números',
                ]
            ],
            'email' => [
                'label' => 'E-mail',
                'rules' => ['required', 'valid_email'],
                'errors' => [
                    'required' => 'O campo {field} é obrigatório',
                    'valid_email' => 'O campo {field} não é válido'
                ]
            ],
            'term' => [
                'label' => 'Política de Privacidade e de Tratamento de Dados',
                'rules' => ['required'],
                'errors' => [
                    'required' => 'É necessário concordar com a nossa {field}',
                ]
            ],
        ];
    }
}
