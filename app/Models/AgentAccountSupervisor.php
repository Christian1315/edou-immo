<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentAccountSupervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        "agent_account",
        "supervisor"
    ];
}
