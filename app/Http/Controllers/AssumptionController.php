<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class AssumptionController extends Controller
{
    public function all_user_has_detail()
    {
        $users = User::whereDoesntHave('userDetail')->count();

        if ($users > 0) {
            return "$users failed";
        }

        return "Assumption all_user_has_detailwas successful";
    }


    public function fix_all_user_has_detail()
    {
        $usersWithoutDetails = User::whereDoesntHave('userDetail')->get();

        foreach ($usersWithoutDetails as $user) {
            UserDetail::create([
                'user_id' => $user->id,
            ]);
        }

        return "successful";
    }
}
