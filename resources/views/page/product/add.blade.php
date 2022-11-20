@extends('admin.app')
@section('contact')
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-2">
                <div class="float-start">
                    <a href="{{ route('member#product') }}" class="btn btn-link"><i
                            class="fa-sharp fa-solid fa-arrow-left-long"></i> Back</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Product Insert Form</h6>
                    <form action="{{ route('member#product_add') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="p-name" class="form-label">Name</label>
                            <input required type="text" name="name" autocomplete="none" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" id="p-name">
                            <div class="invalid-feedback">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="p-category" class="form-label">Categroy</label>
                                    <select required name="categoryId" id="p-category"
                                        class="form-select  @error('categoryId') is-invalid @enderror">
                                        <option value="">Choose Category..</option>
                                        @foreach ($categories as $c)
                                        <option @if (old('categoryId') == $c->id) selected @endif value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('categoryId')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="p-qty" class="form-label">Qty</label>
                                    <input required type="number" name="qty" value="{{ old('qty') }}"
                                        class="form-control @error('qty') is-invalid @enderror" autocomplete="none"
                                        id="p-qty">
                                    <div class="invalid-feedback">
                                        @error('qty')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row  my-2">
                            <label for="" class="form-label">Information</label>
                            <div id="infoContainer" class="information m-0 p-0">
                                <div class="row m-0 p-0 sub-information">
                                    <div class="col-md-5">
                                        <input type="text" required placeholder="Enter Key" readonly value="Price"
                                            class="key form-control  @error('information') is-invalid @enderror">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" required value="" placeholder="Enter Value"
                                            class="value form-control @error('information') is-invalid @enderror">
                                    </div>
                                </div>
                                <div class="row m-0 p-0 sub-information my-1">
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Enter Key" value="Color" readonly
                                            class="key form-control @error('information') is-invalid @enderror">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" placeholder="Enter Value"
                                            class="value form-control @error('information') is-invalid @enderror">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="float-end btn btn-link" type="button" id="addInfonBtn">Add
                                    Information</button>
                            </div>
                        </div>
                        <div class="mb-3 d-none">
                            <label for="p-info" class="form-label">Info Data</label>
                            <textarea name="information" required class="form-control" id="p-info" rows="1">{{ old('information') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="p-image" class="form-label">Image</label>
                            <input type="file" required name="image" class="form-control" id="p-image">
                        </div>
                        <div class="mb-3">
                            <label for="p-desc" class="form-label">Description</label>
                            <textarea name="description" required class="form-control" id="p-desc" rows="6">{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@if (old('information'))
    @push('script')
        <script>
            $(document).ready(function() {
                let oldInfo = JSON.parse($('[name="information"]').val());
                if (oldInfo.length > 2) {
                    infoLoop(oldInfo).then(infoAdd())
                } else {
                    infoAdd();
                }
                async function infoLoop(oldInfo) {
                    for (let i = 0; i < oldInfo.length - 2; i++) {
                        $('#addInfonBtn').click()
                    }
                }

                function infoAdd() {
                    let subinfo = $('.sub-information');
                    subinfo.each(function(index) {
                        $(this).find('.key').val(oldInfo[index].key)
                        $(this).find('.value').val(oldInfo[index].value)
                    })
                }
            });
        </script>
    @endpush
@endif
@push('script')
    <script src="{{ asset('js/product/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            informationKeep();
            activeSidebar('.product_view');
        });
    </script>
@endpush
