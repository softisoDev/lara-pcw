<?php

namespace App\Http\Controllers\CPanel;


class DashboardController extends MainController
{
    public function __construct()
    {
        $this->pageTitle = "Dashboard";
    }

    public function index()
    {
        return view('cpanel.dashboard.index');
    }


}
