<?php

use App\Exceptions\CantSendMoneyToMyselfException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\UnauthorizedTransactionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Return json format
 *
 * @return JsonResponse
 */
if (! function_exists('json')) {
    function json($function, $code = 200): JsonResponse
    {
        try {
            $data = $function();
        } catch (InsufficientBalanceException $ex) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'saldo_insuficiente' => $ex->getMessage()
                ]
            ], 400);
        } catch (CantSendMoneyToMyselfException $ex) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'cant_myself' => $ex->getMessage()
                ]
            ], 400);
        } catch (UnauthorizedTransactionException $ex) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'unauthorized' => $ex->getMessage()
                ]
            ], 400);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::critical($ex);
            return response()->json([
                'errors' => [
                    'error' => "Ocorreu um erro. Entre em contato com o administrador."
                ]
            ], 500);
        }

        return response()->json($data, $code);
    }
}
  