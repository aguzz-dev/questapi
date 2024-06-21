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
        $request = JsonRequest::get();
        VerifyToken::jwt($request->token);
        JsonResponse::send(true, 'Solicitud exitosa, assets existentes:', 200, (new Asset)->getAllAssets()); 
    }

    public function getUserAssetsByUserId()
    {
        $request = JsonRequest::get();
        VerifyToken::jwt($request->token);
        $res = (new Asset)->getUserAssetsByUserId($request->id);
        JsonResponse::send(true, 'Assets pertenecientes al usuario con ID '.$request->id, 200, $res);
    }

    public function buyAsset()
    {
        $request = JsonRequest::get();
        VerifyToken::jwt($request->token);
        try{
            (new AssetUser)->buyAsset($request->asset_id, $request->user_id);
            JsonResponse::send(true, 'Asset comprado con éxito');
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }

}