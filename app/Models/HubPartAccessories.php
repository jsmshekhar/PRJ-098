<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HubPartAccessories extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "hub_part_accessories";
    protected $primaryKey = 'hub_part_accessories_id';

    /*--------------------------------------------------
    Developer : Raj Kumar
    Action    : get hubs
    --------------------------------------------------*/
    public function getHubPartAccessories($request)
    {
        // try {
        //     $auth = Auth::user();
        //     $perPage = env('PER_PAGE');
        //     if (isset($request->per_page) && $request->per_page > 0) {
        //         $perPage = $request->per_page;
        //     }
        //     if ($auth->hub_id == null || $auth->hub_id == "") {
        //         $hub_parts = HubPartAccessories::leftJoin('accessories', 'accessories.accessories_id', '=', 'hub_part_accessories.accessories_id')
        //         ->leftJoin('hubs', 'hubs.hub_id', '=', 'hub_part_accessories.hub_id')
        //         ->leftJoin('users', 'users.user_id', '=', 'hub_part_accessories.created_by')
        //         ->whereNull('hub_part_accessories.deleted_at');
        //     } else {
        //         $hub_parts = HubPartAccessories::leftJoin('accessories', 'accessories.accessories_id', '=', 'hub_part_accessories.accessories_id')
        //         ->leftJoin('hubs', 'hubs.hub_id', '=', 'hub_part_accessories.hub_id')
        //         ->leftJoin('users', 'users.user_id', '=', 'hub_part_accessories.created_by')
        //         ->where('hub_part_accessories.hub_id', $auth->hub_id)
        //             ->whereNull('hub_part_accessories.deleted_at');
        //     }

        //     $hub_parts = $hub_parts->select(
        //         'hub_part_accessories.*',
        //         'hubs.hubId',
        //         'hubs.city',
        //         'accessories.accessories_category_id',
        //         DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'),
        //         DB::raw('CASE
        //                 WHEN accessories.accessories_category_id = 1 THEN "Helmet"
        //                 WHEN accessories.accessories_category_id = 2 THEN "T-Shirt"
        //                 WHEN accessories.accessories_category_id = 3 THEN "Mobile Holder"
        //             END as accessories')
        //     )
        //     ->orderBy('hub_part_accessories.created_at', 'DESC')->paginate($perPage);

        //     if (count($hub_parts) > 0) {
        //         if (count($hub_parts) > 0) {
        //             if ($auth->hub_id == null || $auth->hub_id == "") {
        //                 $hub_parts['count'] = HubPartAccessories::count();
        //             } else {
        //                 $hub_parts['count'] = HubPartAccessories::where('hub_id', $auth->hub_id)
        //                 ->whereNull('deleted_at')->count();
        //             }
        //         }
        //         return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hub_parts' => $hub_parts]);
        //     } else {
        //         return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['hub_parts' => []]);
        //     }
        // } catch (\Throwable $ex) {
        //     $result = [
        //         'line' => $ex->getLine(),
        //         'file' => $ex->getFile(),
        //         'message' => $ex->getMessage(),
        //     ];
        //     return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        // }
    }
}
