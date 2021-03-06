<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Contract\BaseController;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class AdminUserController extends BaseController
{
    public function __construct()
    {
        parent::__construct(UserRepository::class);
    }

    public function index()
    {

        $users = $this->repository->all();
        return response()->json($users);
    }


    public function create()
    {
        return view('admin.user.create');
    }


    public function store(Request $request)
    {
        $inputs = $request->only('name', 'password', 'email');
        $inputs['type'] = 'user';
        $createdUser = $this->repository->create($inputs);
        if ($createdUser) {

            $request->session()->flash('success', 'کاربر با موفقیت اضافه گردید');
            return redirect()->route('users');
        }
    }


    public function show($user_id)
    {
        $user = $this->repository->find($user_id);
        return view('admin.user.edit', compact('user'));
    }


    public function edit($id)
    {
        $user = $this->repository->find($user_id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->repository->find($user_id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password') != null) {
            $user->password = $request->input('password');
        }
        $editedUser = $user->save();
        if ($editedUser) {
            $request->session()->flash('success', 'کاربر با موفقیت ویرایش شد');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $removed_user = $this->repository->delete($user_id);
        $request->session()->flash('success', 'کاربر با موفقیت حذف گردید');
        return redirect()->route('users');
    }
    public function promote($user_id,Request $request)
    {
        $user = $this->repository->find($user_id);
        $user->type = User::ADMIN_TYPE ;
        if($user->save()){
            $request->session()->flash('success','نقش کاربر با موفقیت ارتقا داده شد');
            return redirect()->route('users');
        }
    }
}
