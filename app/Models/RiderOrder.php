<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Rider;
use Illuminate\Http\Response;
use App\Traits\UploadsImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiderOrder extends Model
{
    use HasFactory, SoftDeletes, UploadsImageTrait;
    protected $table = "rider_orders";
    protected $primaryKey = 'order_id';



    public function rider()
    {
        return $this->hasOne(Rider::class, 'rider_id', 'rider_id')->whereNull('deleted_at');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', 'vehicle_id')->whereNull('deleted_at');
    }

    public function hub()
    {
        return $this->hasOne(Hub::class, 'hub_id', 'hub_id')->whereNull('deleted_at');
    }

    protected $appends = [
        'payment_status_display'
    ];


    public function getPaymentStatusDisplayAttribute()
    {
        if (is_null($this->payment_status) || $this->payment_status == "") {
            return "";
        } else {
            switch ($this->payment_status) {
                case 1:
                    return 'Succes';
                    break;
                case 2:
                    return 'Pending';
                    break;
                case 3:
                    return 'Failed';
                    break;
                case 4:
                    return 'Rejected';
                    break;
                default:
                    return "Pending";
            }
        }
    }


    public function assignEv($request)
    {
        try {
            $orderSlug = $request->order_slug ?? null;
            $subscriptionValidity = $request->subscription_validity ?? null;
            $orderDetails = RiderOrder::where(['slug' => $orderSlug, 'status_id' => config('constants.ORDER_STATUS.PENDING'), 'payment_status' => config('constants.PAYMENT_STATUS.SUCCESS')])->whereNull('deleted_at')->first();
            if (!is_null($orderDetails)) {
                $riderId = $orderDetails->rider_id ?? null;
                $orderId = $orderDetails->order_id ?? null;
                $evSlug = $request->mapped_ev ?? null;
                $rider = Rider::where(['rider_id' => $riderId])->whereNull('deleted_at')->first();
                $product = Product::where(['slug' => $evSlug])->where('status_id', config('constants.ACTIVE_STATUS'))->whereNull('deleted_at')->first();
                if (!is_null($rider) && !is_null($product)) {
                    $rentCycle = (int)$orderDetails->subscription_days;
                    $assignDate = NOW();
                    $currentDate = Carbon::now();
                    $toDate = $currentDate->addDays($rentCycle);

                    $mappedVehicleId = $product->product_id;
                    $records = [
                        'status_id' => config('constants.ORDER_STATUS.ASSIGNED'),
                        'mapped_vehicle_id' => $product->product_id,
                        'mapped_product_price' => $product->per_day_rent,
                        'mapped_product_name' => $product->title,
                        'mapped_ev_range' => $product->total_range,
                        'cluster_manager' => $request->cluster_manager ?? null,
                        'tl_name' => $request->tl_name ?? null,
                        'client_name' => $request->client_name ?? null,
                        'client_address' => $request->client_address ?? null,
                        'assigned_date' => NOW(),
                        'hub_id' => $product->hub_id ?? null,
                        'subscription_validity' => $subscriptionValidity,
                    ];
                    $status = RiderOrder::where('slug', $orderSlug)->update($records);
                    $riderOrderId = RiderOrder::where('slug', $orderSlug)->select('order_id')->first();
                    $imgArray = json_decode($request->evImages);
                    $auth = Auth::user();
                    if ($imgArray) {
                        foreach ($imgArray as $images) {
                            if ($images != null) {
                                $image = $images->Content;
                                $filename = $images->FileName;
                                $imagebase = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
                                $folder = '/upload/mediafiles/';
                                $product_image = $this->uploadMultipleImage($imagebase, $folder, $filename);

                                $multi_image = new MediaFile();
                                $multi_image->slug = slug();
                                $multi_image->ref_id = $riderOrderId->order_id;
                                $multi_image->ref_table_id = config('table.REF_TABLE.RIDER_ORDER');
                                $multi_image->module_type = 1;
                                $multi_image->file_type = 1;
                                $multi_image->created_by = $auth->user_id;
                                $multi_image->file_name = !empty($product_image) ? $product_image : "";
                                $multi_image->save();
                            }
                        }
                    }
                    if ($status) {
                        $riderOrderPayments = [
                            'slug' => slug(),
                            'order_id' => $orderId,
                            'rider_id' => $riderId,
                            'mapped_vehicle_id' => $mappedVehicleId,
                            'from_date' => $assignDate,
                            'to_date' => $toDate,
                            'status_id' => 1,
                        ];
                        RiderOrderPayment::insert($riderOrderPayments);
                        Product::where('slug', $evSlug)->update(['status_id' => config('constants.EV_STATUS.ASSIGNED'), 'ev_status' => 1]);
                        $response = [
                            'status' => Response::HTTP_OK,
                            'message' => Lang::get('messages.EV_ASSIGNED'),
                        ];
                        return response()->json($response);
                    }
                }
            }
            $response = [
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => Lang::get('messages.EV_ASSIGNED_ERROR'),
            ];
            return response()->json($response);
        } catch (\Throwable $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }
}
