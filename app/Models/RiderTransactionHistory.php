<?php

namespace App\Models;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class RiderTransactionHistory extends Model
{
    use HasFactory;
    protected $table = "rider_transaction_histories";
    protected $primaryKey = 'rider_transaction_id';

    protected $appends = [
        'transaction_type_name', 'transaction_mode_name', 'payment_status_display'
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
                default:
                    return "Pending";
            }
        }
    }

    public function getTransactionHistory($request)
    {
        try {
            $auth = Auth::user();
            $transactions = RiderTransactionHistory::leftJoin('riders', 'rider_transaction_histories.rider_id','riders.rider_id')
            ->select(
                'rider_transaction_histories.transaction_id',
                'rider_transaction_histories.transaction_ammount',
                'rider_transaction_histories.created_at',
                'riders.customer_id',
                'rider_transaction_histories.transaction_type',
                'rider_transaction_histories.transaction_notes',
                DB::raw('CASE 
                            WHEN rider_transaction_histories.transaction_mode = 1 THEN "Card" 
                            WHEN rider_transaction_histories.transaction_mode = 2 THEN "Wallet" 
                            WHEN rider_transaction_histories.transaction_mode = 3 THEN "UPI" 
                            WHEN rider_transaction_histories.transaction_mode = 4 THEN "Net Banking" 
                            ELSE "" 
                        END as transaction_mode'),
                DB::raw('CASE 
                            WHEN rider_transaction_histories.payment_status = 1 THEN "Success" 
                            WHEN rider_transaction_histories.payment_status = 2 THEN "Pending" 
                            WHEN rider_transaction_histories.payment_status = 3 THEN "Failed" 
                            WHEN rider_transaction_histories.payment_status = 4 THEN "Rejected" 
                            ELSE "" 
                        END as payment_status')
            );

            if (isset($request->is_search) && $request->is_search == 1) {
                if (isset($request->trans_id) && !empty($request->trans_id)) {
                    $transactions = $transactions->where('rider_transaction_histories.transaction_id', 'LIKE', "%{$request->trans_id}%");
                }
                if (isset($request->ch_no) && !empty($request->ch_no)) {
                    $transactions = $transactions->where('rider_transaction_histories.transaction_type', $request->ch_no);
                }
                if (isset($request->cust_id) && !empty($request->cust_id)) {
                    $transactions = $transactions->where('riders.customer_id', 'LIKE', $request->cust_id);
                }
                if (isset($request->date) && !empty($request->date)) {
                    $transactions = $transactions->where('rider_transaction_histories.created_at', $request->date);
                }
                if (isset($request->status) && !empty($request->status)) {
                    $transactions = $transactions->where('rider_transaction_histories.payment_status', $request->status);
                }
            }
            $transactions = $transactions->orderBy('created_at', 'DESC')->paginate(20);

            $count = RiderTransactionHistory::leftJoin('riders', 'rider_transaction_histories.rider_id', 'riders.rider_id')
              ->get();
            $count = count($count);

            if (count($transactions) > 0) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['transactions' => $transactions, 'count' => $count]);
            } else {
                return successResponse(Response::HTTP_OK, Lang::get('messages.SELECT'), ['transactions' => [], 'count' => '']);
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
