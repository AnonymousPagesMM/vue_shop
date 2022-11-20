@extends('admin.app')

@section('contact')
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-2">
                <div class="float-end">
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addSlideShow">Add <i
                            class="fa-solid fa-plus"></i></button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4"><i class="fa-solid fa-layer-group"></i> SlideShows</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Goto</th>
                                    <th scope="col">TimeStamp</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($slides as $slide)
                                    <tr>
                                        <td class="col-1">
                                            <select old_num="{{ $slide->custom_number }}"
                                                class="form-select slideOrderSelect">
                                                @for ($i = 0; $i < count($slides); $i++)
                                                    <option @selected($i + 1 == $slide->custom_number)>{{ $i + 1 }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td><img src="{{ asset('images/' . $slide->image) }}" height="200" width="300"
                                                alt=""></td>
                                        <td><p style="height: 200px;overflow:auto">{{ $slide->description }}</p></td>
                                        <td>
                                            @if ($slide->link != null)
                                                <a class="btn btn-link" href="{{ $slide->link }}">Link</a>
                                            @endif
                                        </td>
                                        <td>
                                            <span>C - {{ $slide->created_at->format('M/d/Y') }}</span><br>
                                            <span>U - {{ $slide->updated_at->format('M/d/Y') }}</span><br>
                                        </td>
                                        <td>
                                            <div>
                                                <button class="btn btn-danger btn-sm" onclick="deleteSlide({{ $slide->id }})">
                                                    <i class="fa-solid fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class=" float-end">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- --- Add Slide Show Modal --  --}}
    <!-- Modal -->
    <div class="modal fade" id="addSlideShow" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Insert <i
                            class="fa-solid fa-layer-group"></i>SlideShow</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('member#slideShow_insert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="inputTitle">Title</label>
                            <input required type="text" id="inputTitle" placeholder="Enter Title" name="title"
                                value="{{ old('title') }}" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="inputImage">Image</label>
                            <input required type="file" id="inputImage" placeholder="Enter Image" accept="image/*"
                                class="form-control" name="image">
                        </div>
                        <div class="form-group mb-2">
                            <label for="inputLink">Link</label>
                            <input type="text" class="form-control" placeholder="Enter link to go" id="inputLink"
                                value="{{ old('link') }}" name="link">
                        </div>
                        <div class="form-group mb-2">
                            <label for="inputDesc">Description</label>
                            <textarea name="description" class="form-control" id="inputDesc" placeholder="Enter Description" rows="5">{{ old('description') }}</textarea>
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

    @if (session('success'))
        <script>
            Swal.fire('Success', '{{ session('success') }}', 'success');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            activeSidebar('.slideShow_view')
            $('.slideOrderSelect').change(function(e) {
                let new_num = $(this).val();
                let old_num = $(this).attr('old_num');
                $('.slideOrderSelect').attr('disabled', 'true');
                let url = 'slide_show/num_change/' + old_num + '/' + new_num;
                window.location.href = url;
            });
        });

        function deleteSlide(id) {
            Swal.fire({
                title: 'Are you sure to Delete?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = 'slide_show/delete/' + id;
                    window.location.href = url;
                    // Swal.fire(
                    //     'Deleted!',
                    //     'Your file has been deleted.',
                    //     'success'
                    // )
                }
            })
        }
    </script>
@endpush
