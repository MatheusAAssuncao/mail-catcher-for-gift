<?php

namespace App\Controllers;

use App\Libraries\Log;
use App\Models\UserModel;

class Validate extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Oferta EA Live!',
            'errors' => [],
            'successMsg' => [],
        ];
        
        if (session()->has('errors')) {
            $data['errors'] = session()->getFlashdata('errors');
        }

        if (session()->has('successMsg')) {
            $data['successMsg'] = session()->getFlashdata('successMsg');
        }

        return view('validate', $data);
    }

    public function submit()
    {
        $validation = $this->validate($this->_getValidationRules());
        if (empty($validation)) {
            $errors = $this->validator->getErrors();
            Log::add('Validate::submit', Log::ERROR, 'Erro na validação da hash', $errors);
            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }

        $_model = new UserModel();
        $ret = $_model->checkCode(mb_strtoupper($this->request->getPost('code')));

        if (empty($ret['result'])) {
            Log::add('Validate::submit', Log::INFO, 'Hash não válida', $ret);
            return redirect()->back()
                ->withInput()
                ->with('errors', ['code' => empty($ret['message']) ? 'Erro ao validar' : $ret['message']]);
        }

        Log::add('Validate::submit', Log::INFO, 'Hash validada com sucesso', [$this->request->getPost('code')]);
        return redirect()->back()
            ->withInput()
            ->with('successMsg', ['code' => empty($ret['message']) ? 'Código validado com sucesso!' : $ret['message']]);
    }
    
    private function _getValidationRules()
    {
        return [
            'code' => [
                'label' => 'Código',
                'rules' => ['required', 'min_length[6]', 'max_length[6]'],
                'errors' => [
                    'required' => 'O campo {field} é obrigatório',
                    'min_length' => 'O campo {field} deve ter {param} caracteres',
                    'max_length' => 'O campo {field} deve ter {param} caracteres',
                ]
            ]
        ];
    }
}
