@extends('admin.layout.app')

@section('content')
     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <span class="fs-5 ml-5">Total - {{$order->total()}}</span>
                <div class="card-tools">
                    <form action="{{route('admin#orderSearch')}}" method="get">
                        @csrf
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="searchData" class="form-control float-right" placeholder="Search" value="{{old('searchData')}}">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Customer Name</th>
                      <th>Pizza Name</th>
                      <th>Pizza Count</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($order as $item)
                    <tr>
                        <td>{{$item->order_id}}</td>
                        <td>{{$item->customer_name}}</td>
                        <td>{{$item->pizza_name}}</td>
                        <td>{{$item->count}}</td>
                        <td>{{$item->order_time}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{-- paginate --}}
                <div class="mt-4 ms-3">{{$order->links()}}</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection
