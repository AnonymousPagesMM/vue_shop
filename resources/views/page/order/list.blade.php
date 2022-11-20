@extends('admin.app')
@section('contact')
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-2">
                <div class="float-end">
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#filterModel">Filter <i class="fa-solid fa-filter"></i></button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Orders Table</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Order Id</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Payment</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <th scope="row">{{ $order->order_id }}</th>
                                        <td><span class=" text-uppercase">{{ $order->status }}</span></td>
                                        <td>{{ $order->city }}</td>
                                        <td>{{ $order->payment }}</td>
                                        <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                        <td>
                                            @php
                                                $total = 0;
                                                foreach ($order->order_items as $item) {
                                                    $total += $item->price;
                                                }
                                                echo $total + $order->delivery_charges;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('member#order_info',$order->order_id) }}" class="btn btn-primary editCategoryBtn">More</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class=" float-end">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="filterModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('member#order') }}" method="GET">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Order Filters</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-content-center mb-2">
                            <div class="col-4">Status</div>
                            <div class="col">
                                <select class="form-select" name="status">
                                    <option @if(request('status')=='') selected @endif value="">All</option>
                                    <option @if(request('status')=='pending') selected @endif value="pending">Pending</option>
                                    <option @if(request('status')=='accept') selected @endif value="accept">Accept</option>
                                    <option @if(request('status')=='success') selected @endif value="success">Success</option>
                                    <option @if(request('status')=='delivered') selected @endif value="success">Delivered</option>
                                    <option @if(request('status')=='reject') selected @endif value="reject">Reject</option>
                                </select>
                            </div>
                        </div>
                        <div class="row align-content-center mb-2">
                            <div class="col-4">From Date</div>
                            <div class="col">
                                <input type="date" value="{{ request('from_date') }}" name="from_date"  class="form-control">
                            </div>
                        </div>
                        <div class="row align-content-center mb-2">
                            <div class="col-4">To Date</div>
                            <div class="col">
                                <input type="date" value="{{ request('to_date') }}" name="to_date"  class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            activeSidebar('.order_view');
        });
    </script>
@endpush
