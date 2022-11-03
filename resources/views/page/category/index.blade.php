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
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Errors</strong> <br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="col-md-12 my-2">
                <div class="float-end">
                    <a type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addCategory"><i
                            class="fa-solid fa-plus"></i>
                        Add Category
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Category Table</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">DB ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Created_at</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($categories) == 0)
                                    <tr>
                                        <td colspan="5">
                                            <h1 class="text-center my-5"><i class="fa-solid fa-circle-exclamation">
                                                </i>There is no Category</h1>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($categories as $c)
                                        <tr rid="{{ $c->id }}">
                                            <th scope="row">{{ $c->id }}</th>
                                            <td>{{ $c->name }}</td>
                                            <td>{{ $c->description }}</td>
                                            <td>{{ $c->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <button class="btn btn-primary editCategoryBtn">Edit</button>
                                                    <button class="btn btn-danger mx-1 deleteCategoryBtn">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class=" float-end">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- -------Model-------- --}}
    <div class="modal fade" id="addCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Category Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('member#category_add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="my-2">
                            <label for="name">Name</label>
                            <input required type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="my-2">
                            <label for="desc">Description</label>
                            <textarea required name="description" id="desc" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- -------Edit Model-------- --}}
    <button data-bs-toggle="modal" class="d-none" id="editModelFormBtn" data-bs-target="#editCategory ">Edit Model</button>
    <div class="modal fade" id="editCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Category Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="editForm" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="my-2">
                            <label for="name">Name</label>
                            <input required type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="my-2">
                            <label for="desc">Description</label>
                            <textarea required name="description" id="desc" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let _token = '{{ csrf_token() }}';
        $(document).ready(function() {
            activeSidebar('.category_view');
            $('.deleteCategoryBtn').click(function() {
                let row = $(this).closest('tr')
                let id = row.attr('rid');
                Swal.fire({
                    icon: 'question',
                    title: 'Are You Sure To Del this Product',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#BB2D3B',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('member#category_destroy') }}",
                            data: {
                                id,
                                _token
                            },
                            dataType: "JSON",
                            success: function(response) {
                                if (response.success) {
                                    row.remove()
                                    Swal.fire('Success', 'Category Delete Successul',
                                        'success')
                                }
                            },
                            error: function(err) {
                                console.log(err);
                            }
                        });
                    }
                })
            })
            $('.editCategoryBtn').click(function() {
                let row = $(this).closest('tr')
                let id = row.attr('rid');
                $.ajax({
                    type: "GET",
                    url: "{{ url('category/edit') }}" + '/' + id,
                    data: {
                        id
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.success) {
                            $('.editForm').find('[name="name"]').val(response.data.name);
                            $('.editForm').find('[name="description"]').val(response.data.name);
                            $('.editForm').attr('action', "{{ url('category/edit') }}" + '/' +
                                response.data.id)
                        }
                        $('#editModelFormBtn').click();
                    }
                });
            });
        });
    </script>
@endpush
