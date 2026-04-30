<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramService;
use App\Services\ZomatoService;
use App\Strategies\Telegram\TextStrategy;
use App\Strategies\Telegram\LocationStrategy;

use App\Strategies\Telegram\VideoStrategy;
use App\Strategies\Telegram\ContactStrategy;

class BotController extends Controller
{
    protected $telegram;
    protected $zomato;

    public function __construct(TelegramService $telegram, ZomatoService $zomato)
    {
        $this->telegram = $telegram;
        $this->zomato = $zomato;
    }

    public function webhook(Request $request)
    {
        $update = $request->all();

        if (!isset($update['message'])) {
            return response()->json(['status' => 'success']);
        }

        $message = $update['message'];
        $strategy = null;

        if (isset($message['text'])) {
            $strategy = new TextStrategy($this->telegram, $this->zomato);
        } elseif (isset($message['location'])) {
            $strategy = new LocationStrategy($this->telegram, $this->zomato);
        } elseif (isset($message['video'])) {
            $strategy = new VideoStrategy($this->telegram);
        } elseif (isset($message['contact'])) {
            $strategy = new ContactStrategy($this->telegram);
        }

        if ($strategy) {
            $strategy->handle($message);
        }

        return response()->json(['status' => 'success']);
    }
}
