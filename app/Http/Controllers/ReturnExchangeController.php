<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rider;
use App\Models\Product;
use App\Models\MediaFile;
use App\Models\RiderOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ReturnExchange;
use App\Models\RiderOrderPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\AdminAppController;
use Carbon\Carbon;
use App\Traits\UploadsImageTrait;

class ReturnExchangeController extends AdminAppController
{
    use UploadsImageTrait;
    protected $model;
    public $viewPath;

    public function __construct()
    {
        $this->model = new ReturnExchange();
        $this->viewPath = "admin/orders";
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Order List
    --------------------------------------------------*/
    public function index(Request $request)
    {
        try {
            $permission = User::getPermissions();
            if (Gate::allows('view', $permission)) {
                $perPage = env('PER_PAGE');
                $permission = User::getPermissions();
                $records = $this->model::with(['rider', 'product', 'hub', 'order'])->whereNull('deleted_at');
                $records = $records->orderBy('created_at', 'DESC')->paginate($perPage);
                return view($this->viewPath . '/return-exchange', compact('records', 'permission'));
            } else {
                return view('admin.401.401');
            }
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Return View
    --------------------------------------------------*/
    public function returnView(Request $request, $slug)
    {
        try {
            $perPage = env('PER_PAGE');
            $permission = User::getPermissions();
            $records = ReturnExchange::with(['rider', 'order'])->where('slug', $slug)->where('status_id', 2)->whereNull('deleted_at')->first();
            if (!empty($records)) {
                $orderId = $records->order_id;
                $imagesData = MediaFile::where(['ref_id' => $orderId, 'ref_table_id' => config('table.REF_TABLE.RIDER_ORDER')])->get();
                $images = [];
                if (!empty($imagesData)) {
                    foreach ($imagesData as $image) {
                        $images[] = ['path' => asset('public/upload/mediafiles/' . $image->file_name), 'name' => $image->file_name];
                    }
                }
                return view($this->viewPath . '/return-view', compact('records', 'permission', 'images'));
            } else {
                return redirect()->back();
            }
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public function returnEvs(Request $request, $slug)
    {
        $request->validate([
            'damage' => 'required',
        ]);

        $result = ReturnExchange::where('slug', $slug)->whereNull('deleted_at')->first();
        if (!empty($result)) {
            $returnExchangeId = $result->return_exchange_id;
            $orderId = $result->order_id;
            $productId = $result->mapped_vehicle_id;
            $data = [
                'refund_ammount' => $request->refund_amount,
                'note' => $request->description,
                'damage_cost' => $request->damage_cost,
                'damage_type' => $request->damage,
                'return_date' => NOW(),
                'refund_date' => $request->refund_date,
                'status_id' => 1,
            ];
            $status = ReturnExchange::where('return_exchange_id', $returnExchangeId)->update($data);
            if ($status) {
                Product::where('product_id', $productId)->update(['status_id' => 1, 'ev_status' => 3]);
                RiderOrder::where('order_id', $orderId)->update(['status_id' => 4]);
            }
            return redirect()->route('return-exchange')->with('success', 'Data Updated Successfully !');
        }
        return redirect()->route('return-exchange')->with('error', 'Data not found!');
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : Exchange View
    --------------------------------------------------*/
    public function exchangeView(Request $request, $slug)
    {
        try {
            $perPage = env('PER_PAGE');
            $permission = User::getPermissions();
            $records = ReturnExchange::with(['rider', 'order', 'product'])->where('slug', $slug)->where('status_id', 2)->whereNull('deleted_at')->first();
            if (!empty($records)) {
                $mappedEvId = $records->order->mapped_vehicle_id;
                $hubId = $records->order->hub_id;

                $evList = Product::where(['status_id' => 1, 'hub_id' => $hubId])->where('product_id', '!=', $mappedEvId)->whereNull('deleted_at')->pluck('ev_number', 'slug')->toArray();

                $orderId = $records->order_id;
                $imagesData = MediaFile::where(['ref_id' => $orderId, 'ref_table_id' => config('table.REF_TABLE.RIDER_ORDER')])->get();
                $images = [];
                if (!empty($imagesData)) {
                    foreach ($imagesData as $image) {
                        $images[] = ['path' => asset('public/upload/mediafiles/' . $image->file_name), 'name' => $image->file_name];
                    }
                }

                return view($this->viewPath . '/exchange-view', compact('records', 'permission', 'images', 'evList'));
            } else {
                return redirect()->back();
            }
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public function exchangeEvs(Request $request, $slug)
    {
        $request->validate([
            'damage' => 'required',
        ]);

        $evSlug = $request->new_ev;


        $result = ReturnExchange::where('slug', $slug)->whereNull('deleted_at')->first();
        $newEvDetails = Product::where('slug', $evSlug)->whereNull('deleted_at')->first();

        if (!empty($result) && !empty($newEvDetails)) {
            $returnExchangeId = $result->return_exchange_id;
            $oldorderId = $result->order_id;
            $oldProductId = $result->mapped_vehicle_id;
            $riderId = $result->rider_id;

            $data = [
                'note' => $request->damage_desription,
                'damage_cost' => $request->damage_cost,
                'damage_type' => $request->damage,
                'return_date' => NOW(),
                'status_id' => 1,
            ];
            $status = ReturnExchange::where('return_exchange_id', $returnExchangeId)->update($data);
            if ($status) {
                $oldOrder = RiderOrder::where('rider_id', $oldorderId)->first();

                $orderCode = slug();
                $mappedVehicleId = $newEvDetails->product_id;
                $orderDetails = [
                    "rider_id" => $riderId,
                    "slug" => $orderCode,

                    "vehicle_id" => $oldOrder->vehicle_id,
                    "product_price" => $oldOrder->product_price,
                    "product_name" => $oldOrder->product_name,

                    "accessories_id" => $oldOrder->accessories_id,
                    "accessories_items" => $oldOrder->accessories_items,

                    "subscription_days" => $oldOrder->subscription_days,
                    "order_date" => $oldOrder->order_date,
                    "ordered_ammount" => $oldOrder->ordered_ammount ?? null,
                    "security_ammount" => $oldOrder->security_ammount ?? null,

                    "payment_status" =>  $oldOrder->payment_status ?? null,
                    "status_id" =>  1,
                    "requested_payload" => $oldOrder->requested_payload,
                    "created_by" => $oldOrder->created_by,
                    "created_at" => NOW(),

                    'mapped_vehicle_id' => $mappedVehicleId,
                    'mapped_product_price' => $newEvDetails->per_day_rent,
                    'mapped_product_name' => $newEvDetails->title,
                    'mapped_ev_range' => $newEvDetails->total_range,
                    'cluster_manager' => $oldOrder->cluster_manager ?? null,
                    'tl_name' => $oldOrder->tl_name ?? null,
                    'client_name' => $oldOrder->client_name ?? null,
                    'client_address' => $oldOrder->client_address ?? null,
                    'assigned_date' => $oldOrder->assigned_date ?? null,
                    'hub_id' => $newEvDetails->hub_id ?? null,
                ];
                $newOrderId = DB::table('rider_orders')->insertGetId($orderDetails);

                if ($newOrderId) {
                    Product::where('product_id', $oldProductId)->update(['status_id' => 1, 'ev_status' => 3]);
                    RiderOrder::where('order_id', $oldorderId)->update(['status_id' => 4]);
                    Product::where('product_id', $mappedVehicleId)->update(['status_id' => 4, 'ev_status' => 1]);

                    $rentCycle = (int)$oldOrder->subscription_days;

                    $lastPayment = RiderOrderPayment::selectRaw('*')->where(['rider_id' => $riderId, 'order_id' => $oldorderId])->orderBy('rider_order_payment_id', 'DESC')->first();
                    if (!empty($lastPayment)) {
                        $fromDate = !is_null($lastPayment) ? Carbon::parse($lastPayment->from_date) : '';
                        $toDate = !is_null($lastPayment) ? Carbon::parse($lastPayment->to_date) : '';

                        $riderOrderPayments = [
                            'slug' => slug(),
                            'order_id' => $newOrderId,
                            'rider_id' => $riderId,
                            'mapped_vehicle_id' => $mappedVehicleId,
                            'from_date' => $fromDate,
                            'to_date' => $toDate,
                            'status_id' => 1,
                        ];
                        RiderOrderPayment::insert($riderOrderPayments);
                    }

                    $imgArray = json_decode($request->evImages);
                    if ($imgArray) {
                        foreach ($imgArray as $images) {
                            if ($images != null) {
                                $image = $images->Content;
                                $filename = $images->FileName;
                                $imagebase = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
                                $folder = '/upload/mediafiles/';
                                $product_image = $this->uploadMultipleImage($imagebase, $folder, $filename);

                                $multiImage = new MediaFile();
                                $multiImage->slug = slug();
                                $multiImage->ref_id = $newOrderId;
                                $multiImage->ref_table_id = config('table.REF_TABLE.RIDER_ORDER');
                                $multiImage->module_type = 1;
                                $multiImage->file_type = 1;
                                $multiImage->created_by = Auth::id();
                                $multiImage->file_name = !empty($product_image) ? $product_image : "";
                                $multiImage->save();
                            }
                        }
                    }
                }
            }
            return redirect()->route('return-exchange')->with('success', 'Data Updated Successfully!');
        }
        return redirect()->route('return-exchange')->with('error', 'Data not found!');
    }
}
