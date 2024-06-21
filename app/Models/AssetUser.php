<?php

namespace App\Models;

use App\Database;

class AssetUser extends Database
{
    protected $table = 'asset_user';

    public function buyAsset($assetId, $userId)
    {
        $isProductPurchased = $this->query("SELECT * FROM {$this->table} WHERE asset_id = '{$assetId}' AND user_id = '{$userId}'");
        if ($isProductPurchased){
            throw new \Exception('El asset ya pertenece a este usuario');
        }
        $this->query("INSERT INTO {$this->table} (`asset_id`, `user_id`) VALUES ('{$assetId}', '{$userId}')");
    }

}