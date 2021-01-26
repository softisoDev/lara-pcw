<?php

namespace App\Http\Controllers\CPanel;

use App\Http\Requests\CPanel\UserPasswordUpdateRequest;
use App\Http\Requests\CPanel\UserProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserProfileController extends MainController
{

    public $subViewFolder;

    public function __construct()
    {
        parent::__construct();
        $this->subViewFolder = 'profile';
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $viewData = [
            'pageTitle' => 'User setting',
            'user'      => Auth::user(),
        ];

        return view("{$this->viewFolder}.{$this->subViewFolder}.index")->with($viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserProfileUpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UserProfileUpdateRequest $request, $id)
    {
        $data = User::findOrFail($id);

        $update = $data->update([
            'name'  => trim($request->full_name),
            'email' => trim($request->email),
        ]);

        if ($update):
            session()->flash('alert', [
                'title'    => 'Info updated successfully',
                'type'     => 'success',
                'position' => 'center'
            ]);
        else:
            session()->flash('alert', [
                'title'    => 'Something went wrong. Please try again!',
                'type'     => 'error',
                'position' => 'center'
            ]);
        endif;

        return back();

    }

    public function update_password(UserPasswordUpdateRequest $request, $id)
    {
        $data = User::findOrFail($id);

        $update = $data->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($update):
            session()->flash('alert', [
                'title'    => 'Password updated successfully',
                'type'     => 'success',
                'position' => 'center'
            ]);
        else:
            session()->flash('alert', [
                'title'    => 'Something went wrong. Please try again!',
                'type'     => 'error',
                'position' => 'center'
            ]);
        endif;

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {

    }
}
