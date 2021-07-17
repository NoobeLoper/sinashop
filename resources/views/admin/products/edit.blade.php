@component('admin.layouts.content' , ['title' => 'ویرایش محصول'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">فهرست محصولات</a></li>
        <li class="breadcrumb-item active">ویرایش محصول</li>
    @endslot

    @slot('script')
        <script>
            // Delete Handle
            function delete_section(e,id){
                e.preventDefault();
                swal({
                    title: "آیا از حذف این مورد مطمئن هستید؟",
                    text: "پس از حذف امکان دست یابی به اطلاعات مقدور نمی باشد",
                    icon: "warning",
                    buttons: {
                        cancel: "نه اشتباه شد!",
                        catch: {
                        text: "بله پاک کن",
                        value: "ok",
                        },
                    },
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            document.getElementById('attribute-'+id).remove()
                            swal('پاک شد رفت ...',{
                                icon: "error",
                            });
                        } else {
                            e.preventDefault();
                            swal('خیلی هم خوب ...',{
                                icon: "success",
                            });
                        }
                    });
            }
            // End Of Delete Handle

            //File Manager
            document.addEventListener("DOMContentLoaded", function() {

            document.getElementById('button-image').addEventListener('click', (event) => {
                event.preventDefault();

                window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
            });
            });

            // set file link for file manager
            function fmSetLink($url) {
            document.getElementById('image_label').value = $url;
            }

            //Select2
            $('#categories').select2({
                'placeholder' : 'دسترسی مورد نظر را انتخاب کنید'
            })


            let changeAttributeValues = (event , id) => {
                let valueBox = $(`select[name='attributes[${id}][value]']`);


                //
                $.ajaxSetup({
                    headers : {
                        'X-CSRF-TOKEN' : document.head.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type' : 'application/json'
                    }
                })
                //
                $.ajax({
                    type : 'POST',
                    url : '/admin/attribute/values',
                    data : JSON.stringify({
                        name : event.target.value
                    }),
                    success : function(res) {
                        valueBox.html(`
                            <option value="" selected>انتخاب کنید</option>
                            ${
                                res.data.map(function (item) {
                                    return `<option value="${item}">${item}</option>`
                                })
                            }
                        `);
                    }
                });
            }

            let createNewAttr = ({ attributes , id }) => {

                return `
                    <div class="row" id="attribute-${id}">
                        <div class="col-5">
                            <div class="form-group">
                                 <label>عنوان ویژگی</label>
                                 <select name="attributes[${id}][name]" onchange="changeAttributeValues(event, ${id});" class="attribute-select form-control">
                                    <option value="">انتخاب کنید</option>
                                    ${
                                        attributes.map(function(item) {
                                            return `<option value="${item}">${item}</option>`
                                        })
                                    }
                                 </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                 <label>مقدار ویژگی</label>
                                 <select name="attributes[${id}][value]" class="attribute-select form-control">
                                        <option value="">انتخاب کنید</option>
                                 </select>
                            </div>
                        </div>
                         <div class="col-2">
                            <label >اقدامات</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-warning" onclick="delete_section(event,${id})">حذف</button>
                            </div>
                        </div>
                    </div>
                `
            }

            $('#add_product_attribute').click(function() {
                let attributesSection = $('#attribute_section');
                let id = attributesSection.children().length;


                let attributes = $('#attributes').data('attributes');


                attributesSection.append(
                    createNewAttr({
                        attributes,
                        id
                    })
                );

                $('.attribute-select').select2({ tags : true });
            });

            $('.attribute-select').select2({ tags : true });
        </script>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ویرایش محصول</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div id="attributes" data-attributes="{{ json_encode(\App\Attribute::all()->pluck('name')) }}"></div>
                <form class="form-horizontal" action="{{ route('admin.products.update' , $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">نام محصول</label>
                            <input type="text" name="title" class="form-control" id="inputEmail3" placeholder="نام محصول را وارد کنید" value="{{ old('title' , $product->title) }}">
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">توضیحات</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ old('description',$product->description) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">قیمت</label>
                            <input type="number" name="price" class="form-control" id="inputPassword3" placeholder="قیمت را وارد کنید" value="{{ old('price',$product->price) }}">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">موجودی</label>
                            <input type="number" name="inventory" class="form-control" id="inputPassword3" placeholder="موجودی را وارد کنید" value="{{ old('inventory',$product->inventory) }}">
                        </div>
                        <div class="form-group">
                            <hr>
                            <label class="col-sm-2 control-label">آپلود تصویر شاخص</label>
                            <div class="input-group mb-2">
                                <input type="text" id="image_label" class="form-control" name="image" dir="ltr" value="{{ $product->image }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="button-image">انتخاب</button>
                                </div>
                            </div>
                            <img class="w-25" src="{{ $product->image }}" alt="">

                        </div>
                        <div class="form-group">
                            <label for="categories" class="col-sm-2 control-label">دسته بندی ها</label>
                            <select class="form-control selectpicker" name="categories[]" id="categories" multiple>
                                @foreach (App\Category::all() as $category)
                                    <option {{ in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <h6>ویژگی محصول</h6>
                        <hr>
                        <div id="attribute_section">
                            @foreach ($product->attributes as $attribute)
                                <div class="row" id="attribute-{{ $loop->index }}">
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label>عنوان ویژگی</label>
                                            <select name="attributes[{{ $loop->index }}][name]" onchange="changeAttributeValues(event, {{ $loop->index }});" class="attribute-select form-control">
                                                <option value="">انتخاب کنید</option>
                                                @foreach (\App\Attribute::all() as $attr)
                                                    <option {{ $attr->name == $attribute->name ? 'selected' : '' }} value="{{ $attr->name }}">{{ $attr->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label>مقدار ویژگی</label>
                                            <select name="attributes[{{ $loop->index }}][value]" class="attribute-select form-control">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach ($attribute->values as $value)
                                                        <option {{ $value->id === $attribute->pivot->value->id ? 'selected' : '' }} value="{{ $value->value }}">{{ $value->value }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <label >اقدامات</label>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="delete_section(event,{{ $loop->index }})">حذف</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="btn btn-sm btn-danger" type="button" id="add_product_attribute">ویژگی جدید</button>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ویرایش محصول</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>

@endcomponent
