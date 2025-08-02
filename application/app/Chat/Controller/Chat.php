<?php

declare(strict_types=1);

namespace App\Chat\Controller;

use Sys\Controller\WebController;
use Az\Route\Route;
use HttpSoft\Response\JsonResponse;

class Chat extends WebController
{
    public function __construct(){}

    public function __invoke($room_id, $author_id)
    {
        $data = [
            'title' => 'Chat',
            'room_id' => $room_id,
            'author_id' => $author_id,
        ];

        $this->app->js('/assets/js/chat.js');

        return view('chat/chat', $data);
    }

    #[Route(methods: 'post')]
    public function post(int $room_id, int $author_id)
    {
        $post = $this->request->getBody()->getContents();
        $data = [
            'from' => $author_id,
            'message' => $post,
        ];

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        file_put_contents(STORAGE . 'logs/socket.txt', $data);

        // $fp = stream_socket_client('tcp://127.0.0.1:8080');
        // fwrite($fp, $json);

        return new JsonResponse($data);
    }
}
