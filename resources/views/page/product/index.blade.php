@extends('admin.app')
@section('contact')
    <div class="container mt-1">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="col-md-12 my-2">
                <div class="float-end">
                    <a href="{{ route('member#product_add_page') }}" class="btn btn-link"><i class="fa-solid fa-plus"></i>
                        Add Product</a>
                </div>
            </div>
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Product Table</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">DB ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Color</th>
                                    <th scope="col">Product Id</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($products) == 0)
                                <tr>
                                    <td colspan="8">
                                        <h1 class="text-center my-5"><i class="fa-solid fa-circle-exclamation">
                                            </i>There is no Product</h1>
                                    </td>
                                </tr>
                                @else
                                @foreach ($products as $p)
                                    <tr style="vertical-align: middle;">
                                        <th scope="row" class="db_id">{{ $p->id }}</th>
                                        <td>{{ $p->name }}</td>
                                        <td>
                                            <a href="{{ asset('images/'.$p->image) }}"><img style="width: 100px;height:100px" src="{{ asset('images/'.$p->image) }}" alt=""></a>
                                        </td>
                                        <td>
                                            <i class="fa-solid fa-circle fs-4"></i>
                                            <input type="text" disabled class="pInfo d-none" value="{{ $p->information }}">
                                        </td>
                                        <td>{{ 'custom' }}</td>
                                        <td>{{ $p->category_name->name }}</td>
                                        <td>{{ $p->qty }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('member#product_edit',$p->id) }}" class="btn btn-primary">Edit</a>
                                                <button class="btn btn-danger mx-1 deleteProductBtn">Delete</button>
                                                <a href="{{ route('member#product_show',$p->id) }}" class="btn btn-dark">View</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class=" float-end">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        let _token = '{{ csrf_token() }}';
        activeSidebar('.product_view');
        $(document).ready(function() {
            colorGet()
            $('.deleteProductBtn').click(function(){
                let row = $(this).closest('tr');
                let id = row.find('.db_id').html();
                console.log(id);
                Swal.fire({
                icon:'question',
                title: 'Are You Sure To Del this Product',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor : '#BB2D3B',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('member#product_destroy') }}",
                        data: {id,_token},
                        dataType: "JSON",
                        success: function (response) {
                            if (response.success) {
                                row.remove()
                                Swal.fire('Success','Product Delete Successul','success')
                            }
                        },
                        error:function(err){
                            console.log(err);
                        }
                    });
                }
            })
            })
        });

        function colorGet(){
            $('.pInfo').each(function(){
                let info = JSON.parse($(this).val());
                console.log(info);
                console.log($(this).closest('td').find('.fa-circle'));
                for (let i = 0; i < info.length; i++) {
                    if (info[i].key == 'color') {
                        $(this).closest('td').find('.fa-circle').css('color',info[i].value);
                    }
                }
            })
        }
    </script>
@endpush
