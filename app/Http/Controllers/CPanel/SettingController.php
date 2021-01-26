<?php

namespace App\Http\Controllers\CPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CPanel\PriceUpdateRunner;
use App\Jobs\ArtisanCommandRunner;
use App\Libraries\PriceParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends MainController
{
    public $subViewFolder;

    public function boot()
    {
        $this->subViewFolder = 'setting';
    }

    public function showPriceUpdater()
    {
        $domains = (new PriceParser())->getSupportedDomain();

        $viewData = [
            'pageTitle' => 'Price updater',
            'domains'   => array_combine($domains, $domains),
        ];

        return view("{$this->viewFolder}.{$this->subViewFolder}.price_update")->with($viewData);
    }

    public function priceUpdaterRun(PriceUpdateRunner $request)
    {
//        $command = 'products:update-price';

//        $domains = implode(',', array_filter($request->post('domains'), 'trim'));

//        $command = implode(" ", array($command, $domains));

//        ArtisanCommandRunner::dispatch($command)->onQueue('price-updater');

        session()->flash('alert', $this->alerts['run']['success']);

        return redirect()->back();
    }
}
