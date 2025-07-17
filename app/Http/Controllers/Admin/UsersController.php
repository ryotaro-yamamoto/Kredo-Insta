<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(Request $request){
        $search = $request->input('search');

        $all_users = User::withTrashed()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(5);

        return view('admin.users.index', compact('all_users'));
    }

    public function deactivate($id){
        $this->user->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
