@component('admin.layouts.content' , ['title' => 'مدیریت ماژول‌ها'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item active">مدیریت ماژول‌ها</li>
    @endslot

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ماژول‌ها</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        @foreach($modules as $module)
                            @php
                                $moduleData = new \Nwidart\Modules\Json($module->getPath() . '\module.json');
                            @endphp
                            <div class="col-sm-4">
                                <div class="mt-2">
                                    <h4>{{ $moduleData->get('alias') }}</h4>
                                    <p>{{ $moduleData->get('description') }}</p>
                                </div>

                                @if (Module::canDisable($module->getName()))

                                    @if(Module::isEnable($module->getName()))  {{-- hamun Helpers dar module, kari ke dar cart.blade.php kardim, kholase shode(moraje'e be MainServiceProvider.php dar Main Module) --}}
                                        <form action="{{ route('admin.modules.disable', ['module' => $module->getName()]) }}" method="POST" id="{{ $module->getName() }}-disable">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                        <a href="#" onclick="event.preventDefault();document.getElementById('{{ $module->getName() }}-disable').submit()" class="btn btn-sm btn-danger">غیرفعال‌سازی</a>
                                    @else
                                        <form action="{{ route('admin.modules.enable', ['module' => $module->getName()]) }}" method="POST" id="{{ $module->getName() }}-enable">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                        <a href="#" onclick="event.preventDefault();document.getElementById('{{ $module->getName() }}-enable').submit()" class="btn btn-sm btn-primary">فعال‌سازی</a>
                                    @endif

                                @else
                                    <a href="#" class="btn btn-sm btn-dark disabled">قابل تغییر نیست</a>
                                @endif
                            </div>

                        @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer"></div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endcomponent
