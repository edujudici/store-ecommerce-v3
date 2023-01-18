<?php

namespace App\Traits;

use App\Exceptions\BusinessError;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

trait MakeRequest
{
    private static $time;
    private static $contt;

    public function __construct()
    {
        self::$time = microtime(true);
    }

    public static function _callService($class, $method, $params)
    {
        try {
            DB::transaction(static function () use (
                $class,
                $method,
                $params
            ) {
                $con = resolve($class);
                self::$contt = self::makeResponse(
                    1,
                    $con->{$method}($params),
                    'Request executed with success'
                );
            });
        } catch (BusinessError $exc) {
            self::$contt = self::makeResponse(0, null, $exc->getMessage());
        } catch (ModelNotFoundException $exc) {
            self::$contt = self::makeResponse(0, null, $exc->getMessage());
        } catch (Exception $exc) {
            self::logError($exc);
        }
        $timeFormated = number_format(round((microtime(true) - self::$time)
            / 1000, 3), 3, ',', '.');
        debug("{$class}@{$method} time:" . $timeFormated . ' ms');
        return self::$contt;
    }

    private static function makeResponse($status, $response, $message)
    {
        return [
            'status' => $status,
            'response' => $response,
            'message' => $message,
        ];
    }

    private static function logError(Exception $exc)
    {
        debug([
            'line' => $exc->getLine(),
            'code' => $exc->getCode(),
            'erro' => $exc->getMessage(),
            'trace' => $exc->getTraceAsString(),
        ]);
        throw new Exception('Error!, contact the system administrator');
    }
}
