<?php

namespace App\Http\Controllers\Api;

use App\Models\ApiModel;
use App\Models\Kyc;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class KycApiController extends ApiController
{
    protected $riderModel, $keyModel;

    public function __construct()
    {
        $this->riderModel = new Rider();
        $this->keyModel = new Kyc();
    }

    /*--------------------------------------------------
    Developer : Chandra Shekhar
    Action    : profile-category
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function profileCategory(Request $request)
    {
        try {
            $result = $this->keyModel->profileCategory($request);
            return finalResponse($result);
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
    Action    : vehicle-preferences
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function vehiclePreferences(Request $request)
    {
        try {
            $result = $this->keyModel->vehiclePreferences($request);
            return finalResponse($result);
            /*$requiredFields = [
        'profile_category' => [
        'required',
        Rule::in([config('constants.PROFILE_CATEGORIES.INDIVIDUAL'), config('constants.PROFILE_CATEGORIES.VENDER'),  config('constants.PROFILE_CATEGORIES.STUDENT'), config('constants.PROFILE_CATEGORIES.CORPORATE')]),
        ],
        ];
        $messages = [
        'phone.profile_category' => 'The profile type is required.',
        ];
        if (!$this->checkValidation($request, $requiredFields, $messages)) {
        return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
        } else {
        $result = $this->keyModel->vehiclePreferences($request);
        return finalResponse($result);
        }*/
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
    Action    : vehicle-details/{slug}
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function vehicleDetails(Request $request, $slug)
    {
        try {
            $result = $this->keyModel->vehicleDetails($request, $slug);
            return finalResponse($result);
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
    Action    : vehicle-preferences
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function createOrder(Request $request)
    {
        try {
            $requiredFields = [
                'rent_cycle' => [
                    'required',
                    Rule::in([config('constants.RENT_CYCLE.15_DAYS'), config('constants.RENT_CYCLE.30_DAYS')]),
                ],
                'vehicle_slug' => "required",
            ];
            $messages = [];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = $this->keyModel->createOrder($request);
                return finalResponse($result);
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
    Action    : uploadFile
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function uploadFile(Request $request)
    {
        try {
            $requiredFields = [
                'media_type' => [
                    'required',
                    Rule::in([1, 2]),
                ],
                'file_name' => 'required',
                'path' => 'required',
            ];
            if ($request->media_type == 1) {
                $requiredFields['file_name'] = 'required|mimes:jpeg,png,jpg,gif|max:2048';
            }
            if ($request->media_type == 2) {
                $requiredFields['file_name'] = 'required|mimes:doc,docx,xls,xlsx,pdf|max:2048';
            }

            $result = [];
            if (!$this->checkValidation($request, $requiredFields)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = ApiModel::uploadFile($request);
                return finalResponse($result);
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
    Action    : update-kys-steps
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function updateKycSteps(Request $request)
    {
        try {
            /*
            1 - Take your selfie
            2 - Personal Details
            3 - Personal Details
            4 - Bank Details

            AccountType - 1-Saving 2-Current
             */
            $stepOne = 1;
            $stepTwo = 2;
            $stepThree = 3;
            $stepFour = 4;

            $profileType = Auth::user()->profile_type;
            $messages = [];
            $requiredFields = [
                'step' => [
                    'required',
                    Rule::in([1, 2, 3, 4]),
                ],
            ];

            $step = (int) $request->step;
            if ($step == $stepOne) {
                $requiredFields['profile_image'] = 'required';
            }

            if ($step == $stepTwo) {
                $requiredFields['full_name'] = 'required';
                // $requiredFields['number'] = 'required';
                $requiredFields['alternate_number'] = 'required';
                $requiredFields['parent_phone'] = 'required';
                $requiredFields['sibling_phone'] = 'required';
                $requiredFields['current_address'] = 'required';
                $requiredFields['permanent_address'] = 'required';
            }

            if ($step == $stepThree) {

                /*if ($profileType == 1) { //Corporate
                $requiredFields['pan_card.front_image'] = 'required';
                $requiredFields['aadhar_card.front_image'] = 'required';
                } elseif ($profileType == 2) { //Individual
                $requiredFields['pan_card.front_image'] = 'required';
                $requiredFields['aadhar_card.front_image'] = 'required';
                $requiredFields['electicity_bill.front_image'] = 'required';
                } elseif ($profileType == 3) { //Student
                $requiredFields['pan_card.front_image'] = 'required';
                $requiredFields['aadhar_card.front_image'] = 'required';
                } elseif ($profileType == 4) { //Vender
                $requiredFields['pan_card.front_image'] = 'required';
                $requiredFields['aadhar_card.front_image'] = 'required';
                }

                $messages = [
                'pan_card.front_image.required' => 'The pan card front image is required.',
                'aadhar_card.front_image.required' => 'The aadhar card front image is required.',
                'driving_licence.front_image.required' => 'The driving licence front image is required.',
                'electicity_bill.front_image.required' => 'The electicity bill is required.',
                'credit_score.front_image.required' => 'The credit score image is required.',
                ];
                 */
                $requiredFields['documents'] = 'required';
            }

            if ($step == $stepFour) {
                $requiredFields['account_type'] = ['required', Rule::in([1, 2])];
                $requiredFields['account_name'] = 'required';
                $requiredFields['account_no'] = 'required';
                $requiredFields['ifsc_code'] = 'required';
                $requiredFields['upi_id'] = 'required';
            }

            $result = [];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = Kyc::updateKycSteps($request);
                return finalResponse($result);
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
    Action    : get-kys-step
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function getKycStep(Request $request)
    {
        try {
            $result = Kyc::getKycStep($request);
            return finalResponse($result);
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
    Action    : update-payment-status
    Request   : Object
    Return    : Json
    --------------------------------------------------*/
    public function updatePaymentStatus(Request $request)
    {
        try {
            /*
            Payment status => 1 => Succes, 2 => Pending, 3 => Failed, 4 => Rejected, 5 => COD
            Transaction mode=>  1 => Card, 2 => Wallet, 3 => UPI, 4 => COD
             */
            $requiredFields = [
                'payment_status' => [
                    'required',
                    Rule::in([1, 2, 3, 4, 5]),
                ],
                'transaction_mode' => [
                    'required',
                    Rule::in([1, 2, 3, 4]),
                ],
                'order_code' => "required",
            ];
            $messages = [];
            if (!$this->checkValidation($request, $requiredFields, $messages)) {
                return validationResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get('messages.VALIDATION_ERROR'), $this->errorMessage);
            } else {
                $result = $this->keyModel->updatePaymentStatus($request);
                return finalResponse($result);
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
