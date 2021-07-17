<?php

namespace Modules\Main\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nwidart\Modules\Facades\Module;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:edit-module')->only(['edit', 'update']);
        $this->middleware('can:delete-module')->only(['destroy']);
        $this->middleware('can:show-modules')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $modules = Module::all();
        return view('main::admin.modules.all', compact('modules'));
    }

    /**
     * disable the Module
     *
     * @param  mixed $module
     * @return void
     */
    public function disable($module)
    {
        $module = Module::find($module);
        if (Module::canDisable($module->getName())) {
            $module->disable();
        }

        alert()->info('ماژول '. $module->getName() .' غیر فعال شد ');
        return back();
    }

    /**
     * enable The Module
     *
     * @param  mixed $module
     * @return void
     */
    public function enable($module)
    {
        $module = Module::find($module);
        if (Module::canDisable($module->getName())) {
            $module->enable();
        }

        alert()->success('ماژول '. $module->getName(). ' فعال شد ');
        return back();
    }
}
