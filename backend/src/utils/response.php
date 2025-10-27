<?php
namespace Backend\Utils;

class Response {
    public static function json(int $status, $data = null) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        if ($data === null) {
            if ($status === 204) exit;
            echo json_encode(null);
            return;
        }
        echo json_encode($data);
    }

    public static function error(int $status, string $message, array $extra = []) {
        $payload = array_merge(['error' => $message], $extra);
        self::json($status, $payload);
    }
}
