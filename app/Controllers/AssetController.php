<?php 

namespace App\Controllers;

use App\Models\Asset;
use App\Models\AssetUser;
use App\Helpers\JsonRequest;
use App\Helpers\JsonResponse;
use App\Middleware\VerifyToken;
use Exception;

class AssetController 
{
    public function getAllAssets()
    {
        VerifyToken::jwt();
        JsonResponse::send(true, 'Solicitud exitosa, assets existentes:', 200, (new Asset)->getAllAssets()); 
    }

    public function getUserAssetsByUserId()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        $res = (new Asset)->getUserAssetsByUserId($request->id);
        JsonResponse::send(true, 'Assets pertenecientes al usuario con ID '.$request->id, 200, $res);
    }

    public function buyAsset()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        try{
            (new AssetUser)->buyAsset($request->asset_id, $request->user_id);
            JsonResponse::send(true, 'Asset comprado con éxito');
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }

    public function checkAssetExpired()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        $res = (new AssetUser)->checkAssetExpired($request->id);
        return empty($res)  ? JsonResponse::send(true, 'El usuario no tiene assets expirados')
                            : JsonResponse::send(true, 'Se eliminaron los siguientes assets expirados del usuario con ID '.$request->id, 200, $res);
    }

}