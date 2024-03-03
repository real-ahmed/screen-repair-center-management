@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('component.all')}}">

                        <div class="form-row">
                            <div class="input-group col-auto">
                                <input type="text" class="form-control" value="{{request()->input('search')}}"
                                       name="search" placeholder="@lang("بحث")" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                            class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col ml-auto">
                    <div class="dropdown float-right">

                        <button data-toggle="modal" data-target="#componentModal"
                                class="btn btn-primary float-right ml-3 new"
                                type="button">@lang("+ نوع جديد")</button>
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الاسم")</th>
                        <th>@lang("الرقم التسلسلي")</th>
                        <th>@lang("البراند")</th>
                        <th>@lang("الفئة")</th>
                        <th>@lang("الفئة الفرعية")</th>
                        <th>@lang("سعر البيع")</th>
                        <th>@lang("الكمية لانشاء طلب تلقائي")</th>
                        <th>@lang("الكمية المتوفرة")</th>
                            <th>@lang("العمليات")</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($components as $component)
                        <tr>
                            <td>{{$components->firstItem() + $loop->index}}</td>
                            <td>{{$component->name}}</td>
                            <td>{{$component->code}} </td>
                            <td>{{$component->brand->name??'-'}}</td>
                            <td>{{$component->category->name}}</td>
                            <td>{{$component->subcategory->name ?? "-"}}</td>
                            <td>{{showAmount($component->selling_price)}} {{$general->money_sign}}</td>
                            <td>{{$component->auto_request_quantity??"-"}}</td>
                            <td>{{$component->instockquantity}}</td>

                            <td>

                                <button data-id="{{ $component->id }}"
                                        onclick="printBarcode('{{$component->code}}','{{ $component->name }}')"
                                        class="btn btn-secondary"><i class="fa-solid fa-print"></i></button>

                                @if(auth()->user()->isWarehouseEmployee || auth()->user()->isreceptionist)
                                    <a data-id="{{ $component->id }}"
                                       data-name="{{ $component->name }}"
                                       data-code="{{$component->code}}"
                                       data-brand_id="{{$component->brand->id??null}}"

                                       data-category_id="{{$component->category->id}}"
                                       data-subcategory_id="{{$component->subcategory->id??null}}"
                                       data-selling_price="{{$component->selling_price}}"
                                       data-auto_request_quantity="{{$component->auto_request_quantity}}"
                                       class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                @endif
                                @if(auth()->user()->isadmin)

                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $component->id }}"
                                       class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    <iframe id="barcodeFrame" style="display: none;"></iframe>
                    <div id='printDiv' style="display: none;">
                        <div style="text-align: center;">
                            <div style="display: flex;
                                        justify-content: space-between;">
                                <img src="{{getImage(getFilePath('logoIcon') .'/invoice_logo.png')}}" alt="Logo"
                                     style="max-height: 15px;">
                                <div style="
                                            display: flex;
                                            flex-direction: column;
                                            ">
                                    <span style="margin: 0; font-size: 8px;">{{$general->phone}}</span>
                                    <span style="margin: 0; font-size: 8px;">{{$general->sac_phone}}</span>
                                </div>
                            </div>
                            <div>
                                <svg id='barcode'></svg>
                                <p style="margin: 0; font-size: 10px;" id="product_print_name"></p>
                            </div>
                        </div>
                    </div>

                    </tbody>
                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($components->hasPages())
                        {{ $components->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @include("component.model")
    @include("models.delete")
@endsection

@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#componentModal');

            var saveAction = `{{ route('item.component.save') }}`;
            var deleteAction = `{{ route('admin.component.delete') }}`;

            $('.new').click(function () {

                var form = modal.find('form');
                form.find('[name=code]').val(generateProductCode());


            });
            $('.edit').click(function () {
                var data = $(this).data();
                var form = modal.find('form');
                form.attr('action', `${saveAction}/${data.id}`);
                form.find('[name=name]').val(data.name);
                form.find('[name=code]').val(data.code);
                form.find('[name=brand_id]').val(data.brand_id);

                form.find('[name=category_id]').val(data.category_id);
                form.find('[name=auto_request_quantity]').val(data.auto_request_quantity);
                form.find('[name=selling_price]').val(data.selling_price);

                // Clear existing options
                $('#subcategory_id option:not(:first-child)').remove();

                // Add new options based on the response
                $.ajax({
                    url: '{{ route('warehouse.employee.subcategory.getSubcategories') }}',
                    method: 'GET',
                    data: {category_id: data.category_id},
                    success: function (subcategories) {
                        $.each(subcategories, function (index, subcategory) {
                            $('#subcategory_id').append($('<option>', {
                                value: subcategory.id,
                                text: subcategory.name
                            }));
                        });

                        // Set the selected subcategory
                        form.find('[name=subcategory_id]').val(data.subcategory_id);
                    }
                });

                modal.modal('show');
            });


            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
                $('#subcategory_id option:not(:first-child)').remove();
            });


            var deleteModal = $('#deleteModel');
            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });

            function generateProductCode() {
                const timestamp = new Date().getTime();
                return 'PRD' + timestamp;
            }

        })(jQuery);
    </script>
@endpush


@push('script')
    <script>
        function printBarcode(code, name, papersCount) {
            // Generate barcode with JsBarcode
            JsBarcode("#barcode", code, {
                format: "CODE128", // You can change the barcode format as needed
                displayValue: true, // Set to true if you want to display the value below the barcode
                width: 1,
                height: 25,
                textMargin: 0,
                fontSize: 10,
                marginBottom: 0,
                background: '#ffffff'
            });
            $('#product_print_name').text(name);
            var productCodeDiv = $('#printDiv').html();
            var iframe = $('#barcodeFrame')[0];
            var blob = new Blob([productCodeDiv], {type: 'text/html'});
            iframe.src = URL.createObjectURL(blob);
            iframe.onload = function () {
                // Print the barcode
                iframe.contentWindow.print();
            };
        }

    </script>
@endpush
