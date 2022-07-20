<?php

namespace App\Models;

use App\Libraries\Database;
use App\Libraries\Log;
use CodeIgniter\Model;

class UserModel extends Model
{
    public function saveUser($data) 
    {
        if (empty($data)) {
            return ['result' => false, 'message' => 'Nenhum dado recebido'];
        }

        $db = new Database();
        $params = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'code' => $data['code'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        try {
            $sql = "INSERT INTO users (name, email, phone, code, created_at) VALUES (:name, :email, :phone, :code, :created_at)";
            $result = $db->EXE_NON_QUERY($sql, $params);
        } catch (\Exception $e) {
            Log::add('UserModel::saveUser', Log::ERROR, 'Exception', ['message' => $e->getMessage()]);
            return ['result' => false, 'message' => 'Não foi possível solicitar o código'];
        }

        if (empty($result)) {
            return ['result' => false, 'message' => 'Erro ao salvar o registro'];
        }

        return ['result' => true];
    }

    public function checkCode($hash) 
    {
        $db = new Database();

        try {
            $sql = "SELECT id, created_at, validated_at FROM users WHERE code = :code LIMIT 1";
            $result = $db->EXE_QUERY($sql, ['code' => $hash]);
        } catch (\Exception $e) {
            Log::add('UserModel::checkCode', Log::ERROR, 'Exception', ['message' => $e->getMessage()]);
            return ['result' => false, 'message' => 'Não foi possível validar o código'];
        }

        if (empty($result)) {
            return ['result' => false, 'message' => 'Código não encontrado'];
        }

        if (!empty($result[0]['validated_at'])) {
            return ['result' => false, 'message' => 'Código ' . $hash . ' já utilizado em ' . $result[0]['validated_at']];
        }

        $sql = "UPDATE users SET validated_at = :validated_at WHERE id = :id";
        $result = $db->EXE_NON_QUERY($sql, ['validated_at' => date('Y-m-d H:i:s'), 'id' => $result[0]['id']]);

        if (empty($result)) {
            return ['result' => false, 'message' => 'Erro ao validar o código'];
        }

        return ['result' => true, 'message' => 'Código validado com sucesso'];
    }
}
