<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class RiderTransactionHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "rider_transaction_histories";
    protected $primaryKey = 'rider_transaction_id';

    protected $appends = [
        'transaction_type_name', 'transaction_mode_name', 'payment_status_display',
    ];

    //1 => Credited, 2 => Debited
    public function getTransactionTypeNameAttribute()
    {
        if (is_null($this->transaction_type) || $this->transaction_type == "") {
            return "";
        } else {
            switch ($this->transaction_type) {
                case 1:
                    return 'Credited';
                    break;
                case 2:
                    return 'Debited';
                    break;
                default:
                    return "";
            }
        }
    }

    //1 => Card, 2 => Wallet, 3 => UPI
    public function getTransactionModeNameAttribute()
    {
        if (is_null($this->transaction_mode) || $this->transaction_mode == "") {
            return "";
        } else {
            switch ($this->transaction_mode) {
                case 1:
                    return 'Card';
                    break;
                case 2:
                    return 'Wallet';
                    break;
                case 3:
                    return 'UPI';
                    break;
                case 4:
                    return 'COD';
                    break;
                default:
                    return "";
            }
        }
    }

    //1 => Succes, 2 => Pending, 3 => Failed, 4 => Rejected
    public function getPaymentStatusDisplayAttribute()
    {
        if (is_null($this->payment_status) || $this->payment_status == "") {
            return "";
        } else {
            switch ($this->payment_status) {
                case 1:
                    return 'Success';
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
                case 5:
                    return 'Success';
                    break;
                default:
                    return "Pending";
            }
        }
    }

    public function getTransactionHistory($request)
    {
        try {
            $userSlug = $request->user_slug ?? null;
            $auth = Auth::user();
            $transactions = RiderTransactionHistory::leftJoin('riders', 'rider_transaction_histories.rider_id', 'riders.rider_id')
                ->select(
                    'rider_transaction_histories.transaction_id',
                    'rider_transaction_histories.transaction_ammount',
                    'rider_transaction_histories.created_at',
                    'riders.customer_id',
                    'riders.name',
                    'rider_transaction_histories.transaction_type',
                    'rider_transaction_histories.transaction_notes',
                    DB::raw('CASE
                            WHEN rider_transaction_histories.transaction_mode = 1 THEN "Card"
                            WHEN rider_transaction_histories.transaction_mode = 2 THEN "Wallet"
                            WHEN rider_transaction_histories.transaction_mode = 3 THEN "UPI"
                            WHEN rider_transaction_histories.transaction_mode = 4 THEN "COD"
                            ELSE ""
                        END as transaction_mode'),
                    DB::raw('CASE
                            WHEN rider_transaction_histories.payment_status = 1 THEN "Success"
                            WHEN rider_transaction_histories.payment_status = 2 THEN "Pending"
                            WHEN rider_transaction_histories.payment_status = 3 THEN "Failed"
                            WHEN rider_transaction_histories.payment_status = 4 THEN "Rejected"
                            WHEN rider_transaction_histories.payment_status = 5 THEN "Success"
                            ELSE ""
                        END as payment_status')
                );

            if (!empty($userSlug)) {
                $userId = User::where('slug', $userSlug)->whereNull('deleted_at')->value('user_id');
                $transactions = $transactions->join('transaction_collected_ammounts AS tca', 'tca.transaction_id', 'rider_transaction_histories.rider_transaction_id');
                $transactions = $transactions->where('tca.user_id', $userId);
            }
            if (isset($request->is_search) && $request->is_search == 1) {
                if (isset($request->tr_id) && !empty($request->tr_id)) {
                    $transactions = $transactions->where('rider_transaction_histories.transaction_id', 'LIKE', "%{$request->tr_id}%");
                }
                if (isset($request->cu_id) && !empty($request->cu_id)) {
                    $transactions = $transactions->where('riders.customer_id', 'LIKE', $request->cu_id);
                }
                if (isset($request->date) && !empty($request->date)) {
                    $transactions = $transactions->whereDate('rider_transaction_histories.created_at', $request->date);
                }
                if (isset($request->p_status) && !empty($request->p_status)) {
                    $transactions = $transactions->where('rider_transaction_histories.payment_status', $request->p_status);
                }
                if (isset($request->p_mode) && !empty($request->p_mode)) {
                    $transactions = $transactions->where('rider_transaction_histories.transaction_mode', $request->p_mode);
                }
                if (isset($request->p_type) && !empty($request->p_type)) {
                    $transactions = $transactions->where('rider_transaction_histories.transaction_type', $request->p_type);
                }
            }
            $transactions = $transactions->orderBy('created_at', 'DESC')->paginate(20);

            $count = RiderTransactionHistory::leftJoin('riders', 'rider_transaction_histories.rider_id', 'riders.rider_id')
                ->get();
            $count = count($count);
            $payStatus = ['1' => 'Success', '2' => 'Pending', '3' => 'Failed', '4' => 'Reject'];
            $payModes = ['1' => 'Card', '2' => 'Wallet', '3' => 'UPI', '4' => 'COD'];
            $payTypes = ['1' => 'Credit', '2' => 'Debit'];
            if (count($transactions) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['transactions' => $transactions, 'count' => $count, 'payStatus' => $payStatus, 'payModes' => $payModes, 'payTypes' => $payTypes]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['transactions' => [], 'count' => '', 'payStatus' => $payStatus, 'payModes' => $payModes, 'payTypes' => $payTypes]);
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
}
