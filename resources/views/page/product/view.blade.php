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
            <div class="col-md-6">
                <img src="{{ asset('images/' . $product->image) }}" class="w-100" alt="">
            </div>
            <div class="col-md-6">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">{{ $product->name }}</h6>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">Information</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                                type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Description</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                                type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Contact</button>
                        </div>
                    </nav>
                    <div class="tab-content pt-3" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="bg-light rounded h-100 p-4">
                                <table class="table">
                                    <tbody id="tBodyInformation">

                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td>Remain Qty</td>
                                            <td>{{ $product->qty }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created At</td>
                                            <td>{{ $product->created_at->format('d-M-Y') }}</td>
                                        </tr>
                                    </tbody>
                                    <textarea name="" id="productInformation" class="d-none" readonly cols="30" rows="10">{{ $product->information }}</textarea>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            {{ $product->description }}
                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            Sit consetetur eirmod lorem ea magna sadipscing ipsum elitr invidunt, dolores lorem erat ipsum
                            ut aliquyam eos lorem sed. Nonumy aliquyam ea justo eos dolores dolores duo dolores. Aliquyam
                            dolor sea dolores sit takimata no erat vero. At lorem justo tempor lorem duo, stet kasd aliquyam
                            ipsum voluptua labore at.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            let info = JSON.parse($('#productInformation').val());
            let data = "";
            for (let i = 0; i < info.length; i++) {
                data += "<tr><td>"+info[i].key+"</td><td>"+info[i].value+"</td></tr>"
            }
            $('#tBodyInformation').html(data);
        });
    </script>
@endpush
