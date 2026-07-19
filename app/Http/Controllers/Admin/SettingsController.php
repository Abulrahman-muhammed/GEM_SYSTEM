<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Settings\GeneralSettings;

class SettingsController extends Controller
{
    public function edit(
    GeneralSettings $settings
    ) {
        return view(
            'admin.settings.edit',
            compact('settings')
        );
    }

public function update(
    SettingRequest $request,
    GeneralSettings $settings
) {

    $data = $request->validated();

    if ($request->hasFile('logo')) {

        $data['logo'] = $request
            ->file('logo')
            ->store('settings','public');
    }

    $settings->fill($data);

    $settings->save();

    return back()
        ->with('success','تم الحفظ');
}

}
