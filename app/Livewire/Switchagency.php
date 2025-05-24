<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Switchagency extends Component
{
    public $agencies = [];
    public $agencies_count = [];
    
    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $hearders = [];

    function __construct()
    {
        set_time_limit(0);
        $this->BASE_URL = env("BASE_URL");
        $this->token = session()->get("token");
        $this->userId = session()->get("userId");

        $this->hearders = [
            "Authorization" => "Bearer " . $this->token,
        ];

        // AGENCES
        $agencies = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/agency/all")->json();

        if (!$agencies["status"]) {
            $this->agencies_count = 0;
            $this->agencies = [];
        } else {
            $this->agencies_count = count($agencies["data"]);
            $this->agencies = $agencies["data"];
        }
    }

    public function render()
    {
        return view('livewire.switchagency');
    }
}
