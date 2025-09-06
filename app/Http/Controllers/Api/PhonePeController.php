<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PhonePeClient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PhonePeController extends Controller
{
    public function __construct(private PhonePeClient $client)
    {
        // Optionally add auth middleware for all these routes
        // $this->middleware('auth:sanctum');
    }

    /**
     * OPTIONAL (debug/admin): fetch & return current access token (do not expose publicly)
     */
    public function authToken()
    {
        $token = $this->client->getAccessToken();
        return response()->json(['token' => $token]);
    }

    /**
     * Create Order Token
     * Body:
     *  - merchantOrderId (string, <=63, only [A-Za-z0-9_-])
     *  - amount (int, in paisa, >=100)
     *  - expireAfter (int seconds, 300..3600) optional
     *  - metaInfo.udf1..udf5 optional
     */
    public function createOrderToken(Request $request)
    {
        $validated = $request->validate([
            'merchantOrderId' => ['required','string','max:63','regex:/^[A-Za-z0-9_-]+$/'],
            'amount'          => ['required','integer','min:100']
        ]);

        // Ensure paymentFlow.type default
        if (!isset($validated['paymentFlow'])) {
            $validated['paymentFlow'] = ['type' => 'PG_CHECKOUT'];
        }
       return $validated;
       $resp = $this->client->createOrderToken($validated);

        return response()->json($resp, Response::HTTP_OK);
    }

    /**
     * Check Order Status
     * Query:
     *  - merchantOrderId (required)
     *  - details (bool) optional, default false
     *  - errorContext (bool) optional, default false
     */
    public function orderStatus(Request $request, string $merchantOrderId)
    {
        $details = filter_var($request->query('details', false), FILTER_VALIDATE_BOOLEAN);
        $errorContext = filter_var($request->query('errorContext', false), FILTER_VALIDATE_BOOLEAN);

        $resp = $this->client->getOrderStatus($merchantOrderId, $details, $errorContext);

        return response()->json($resp, Response::HTTP_OK);
    }

    /**
     * Initiate Refund
     * Body:
     *  - merchantRefundId (string, required)
     *  - originalMerchantOrderId (string, required)
     *  - amount (int, in paisa, required; must be <= original amount)
     */
    public function refund(Request $request)
    {
        $validated = $request->validate([
            'merchantRefundId'        => ['required','string','max:100'],
            'originalMerchantOrderId' => ['required','string','max:100'],
            'amount'                  => ['required','integer','min:1'],
        ]);

        $resp = $this->client->initiateRefund($validated);

        return response()->json($resp, Response::HTTP_OK);
    }

    /**
     * Refund Status
     */
    public function refundStatus(string $merchantRefundId)
    {
        $resp = $this->client->getRefundStatus($merchantRefundId);
        return response()->json($resp, Response::HTTP_OK);
    }
}
