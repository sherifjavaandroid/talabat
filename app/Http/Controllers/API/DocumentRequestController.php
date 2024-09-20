<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\DocumentRequest;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DocumentRequestController extends Controller
{
    //
    public function driver(Request $request)
    {
        //validate request documents, and must be files
        $validator = Validator::make(
            $request->all(),
            [
                'documents' => 'required',
                'documents.*' => 'file'
            ]
        );
        //if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }

        try {

            $documentRequest = DocumentRequest::where('model_type', User::class)
                ->where('model_id', Auth::id())
                ->where('status', 'requested')
                ->first();

            //if not found, return error
            if (empty($documentRequest)) {
                return response()->json([
                    "message" => __("Document request not found"),
                ], 400);
            }

            //if found
            foreach ($request->documents ?? [] as $document) {
                $documentRequest->addMedia($document->getRealPath())
                    ->usingFileName(genFileName($document))
                    ->toMediaCollection("documents");
            }
            //
            $documentRequest->status = 'pending';
            $documentRequest->save();
            //return success
            return response()->json([
                "message" => __("Document uploaded successfully"),
            ], 200);
        } catch (Exception $e) {
            logger("DocumentRequestController@driver", [$e]);
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    //vendor
    public function vendor(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'documents' => 'required',
                'documents.*' => 'file'
            ]
        );
        //if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }

        try {
            $vendorId = Auth::user()->vendor_id;
            $documentRequest = DocumentRequest::where('model_type', Vendor::class)
                ->where('model_id', $vendorId)
                ->where('status', 'requested')
                ->first();

            //if not found, return error
            if (empty($documentRequest)) {
                return response()->json([
                    "message" => __("Document request not found"),
                ], 400);
            }

            //if found
            foreach ($request->documents ?? [] as $document) {
                $documentRequest->addMedia($document->getRealPath())
                    ->usingFileName(genFileName($document))
                    ->toMediaCollection("documents");
            }
            $documentRequest->status = 'pending';
            $documentRequest->save();
            //return success
            return response()->json([
                "message" => __("Document uploaded successfully"),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }
}
