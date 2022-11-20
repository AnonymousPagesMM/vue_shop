@extends('admin.app')
<link rel="stylesheet" href="{{ asset('css/orderInfo.css') }}">
@section('contact')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <div class="page-content container">
        <div class="page-header text-blue-d2">
            <h1 class="page-title text-secondary-d1">
                Order
                <small class="page-info">
                    <i class="fa fa-angle-double-right text-80"></i>
                    ID: {{ $order->order_id }} {{ session('error') }}
                </small>
            </h1>

            <div class="page-tools">
                <div class="action-buttons">
                    <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="Print">
                        <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                        Print
                    </a>
                    <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="PDF">
                        <i class="mr-1 fa fa-file-pdf-o text-danger-m1 text-120 w-2"></i>
                        Export
                    </a>
                </div>
            </div>
        </div>

        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <div>
                                <span class="text-sm text-grey-m2 align-middle">To:</span>
                                <span class="text-600 text-110 text-blue align-middle">{{ $order->customer->name }}</span>
                            </div>
                            <div class="text-grey-m2">
                                <div class="my-1 text-capitalize">
                                    {{ $order->city }}
                                </div>
                                <div class="my-1">
                                    {{ $order->address }}
                                </div>
                                <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b
                                        class="text-600">{{ $order->phone_1 }} , {{ $order->phone_2 }}</b></div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none" />
                            <div class="text-grey-m2">
                                <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                    Invoice
                                </div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">ID:</span> {{ $order->order_id }}</div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Order Date:</span>
                                    {{ $order->created_at->format('M d,Y') }}</div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Status:</span>
                                    @if ($order->status == 'pending')
                                        <span class="badge bg-warning badge-pill px-25">Pending</span>
                                    @elseif ($order->status == 'accept')
                                        <span class="badge bg-info badge-pill px-25">Accept</span>
                                    @elseif ($order->status == 'reject')
                                        <span class="badge bg-danger badge-pill px-25">Reject</span>
                                        @elseif ($order->status == 'delivered')
                                        <span class="badge bg-dark badge-pill px-25">Delivered</span>
                                    @elseif ($order->status == 'success')
                                        <span class="badge bg-success badge-pill px-25">Success</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="mt-4">
                        <div class="row text-600 text-white bgc-default-tp1 py-25">
                            <div class="d-none d-sm-block col-1">#</div>
                            <div class="col-9 col-sm-5">Description</div>
                            <div class="d-none d-sm-block col-4 col-sm-2">Qty</div>
                            <div class="d-none d-sm-block col-sm-2">Unit Price</div>
                            <div class="col-2">Amount</div>
                        </div>

                        <div class="text-95 text-secondary-d3">
                            @foreach ($order->order_items as $item)
                                <div class="row mb-2 mb-sm-0 py-25">
                                    <div class="d-none d-sm-block col-1">{{ $loop->index + 1 }}</div>
                                    <div class="col-9 col-sm-5">{{ $item->product->name }}</div>
                                    <div class="d-none d-sm-block col-2">{{ $item->qty }}</div>
                                    <div class="d-none d-sm-block col-2 text-95">{{ $item->price }}</div>
                                    <div class="col-2 text-secondary-d2">{{ $item->qty * $item->price }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row border-b-2 brc-default-l2"></div>

                        <div class="row mt-3">
                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            </div>

                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        SubTotal
                                    </div>
                                    <div class="col-5">
                                        <span class="text-120 text-secondary-d1">
                                            @php
                                                $total = 0;
                                                foreach ($order->order_items as $item) {
                                                    $total += $item->price;
                                                }
                                                echo $total;
                                            @endphp
                                        </span>
                                    </div>
                                </div>

                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        Delivery Charges
                                    </div>
                                    <div class="col-5">
                                        <span class="text-110 text-secondary-d1">{{ $order->delivery_charges }}</span>
                                    </div>
                                </div>

                                <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                    <div class="col-7 text-right">
                                        Total Amount
                                    </div>
                                    <div class="col-5">
                                        <span
                                            class="text-150 text-success-d3 opacity-2">{{ $total + $order->delivery_charges }}
                                            MMK</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-md-5">
                                @if ($order->status == 'pending')
                                    <a href="{{ route('member#order_accept', $order->order_id) }}"
                                        class="btn btn-success">Accept</a>
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">Reject</button>
                                @endif
                                @if ($order->status != 'pending' && $order->status != 'delivered')
                                <div class="row">
                                    <form action="{{ route('member#order_deliver_date', $order->order_id) }}"
                                        method="POST">
                                        @csrf
                                        <input value="{{ $order->deliver_date }}"
                                            class="form-control" type="date" name="date" required>
                                        <button  class="btn btn-primary float-end mt-1"
                                            type="submit">Will Deliver</button>
                                    </form>
                                </div>
                                @endif
                                @if ($order->status == 'accept' && $order->deliver_date != NULL)
                                    <a    href="{{ route('member#order_delivered',$order->order_id) }}" class="btn btn-success float-end">Delivered</a>
                                @endif


                            </div>
                            <div class="col-md-7  text-end">
                                <form action="{{ route('member#order_remark', $order->order_id) }}" method="POST">
                                    @csrf
                                    <textarea class="form-control" name="remark" placeholder="Enter Remark" rows="5"></textarea>
                                    <button class="btn w-100 btn-primary mt-2">Submit</button>
                                </form>
                            </div>
                        </div>
                        {{-- -----------------------------Remark List------------------------------ --}}
                        <h3 class="text-center mt-4">History and remark of Order</h3>
                        <ul class="list-group">
                            @php
                                $remarks = array_reverse(json_decode($order->remark));
                            @endphp
                            @if ($remarks)
                                @foreach ($remarks as $remark)
                                    <li class="list-group-item">{{ $remark }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ---------- Reject Input Modal --------------- --}}
    <!-- Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Order Reject</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('member#order_reject', $order->order_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <textarea class="form-control" name="reason" required placeholder="Enter Reject Reason" rows="5"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @if (session('error'))
        <script>
            $(document).ready(function() {
                Swal.fire('Error', "{{ session('error') }}", 'error');
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            activeSidebar('.order_view');
            $('#rejectBtn').click(function() {

            });
        });
    </script>
@endpush
