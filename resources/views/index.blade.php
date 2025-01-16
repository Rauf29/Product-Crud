<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>

    <body>
        <div class="container my-5 border border-1 border-opacity-25 p-4 rounded">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>All Products</h1>
                <a href="{{route('products.create')}}" class="btn btn-primary">Create New Product</a>
            </div>

            <div class="mb-4">
                <input type="search" id="search" name="search" class="form-control" placeholder="Search products...">
            </div>

            <div class="row d-flex justify-content-center">
                @if (session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>

                @endif
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>No</th>
                        <th style="min-width: 140px;">
                            <a class="text-white" href="{{route('products.index',['sortBy'=>'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])}}">Name
                            <i class="fa {{request('sortBy') == 'name' && request('order') == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'}}"></i>
                            </a>
                        </th>
                        <th>Image</th>
                        <th>Detail</th>
                        <th style="min-width: 110px;">
                            <a href="{{route('products.index',['sortBy'=>'price', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])}}" class="text-white">Price
                            <i class="fa {{request('sortBy') == 'price' && request('order') == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'}}"></i>

                            </a>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productsTable">
                    @if($products->isnotEmpty())
                    @foreach($products as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->name}}</td>
                        <td><img src="{{asset('uploads/products/'.$product->image)}}" width="100" ></td>
                        <td class="w-50">{{$product->description}}</td>
                        <td>{{$product->price}} Tk</td>
                        <td >
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a href="{{route('products.show', $product->id)}}" class="btn btn-sm btn-primary">Show</a>

                            <a href="{{route('products.edit', $product->id)}}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="#" onclick="deleteProduct({{$product->id}})" class="btn btn-sm btn-danger">Delete</a>
                            <form action="{{route('products.destroy', $product->id)}}" method="POST" id="delete-form-{{$product->id}}">
                                @csrf
                                @method('DELETE')
                            </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>

            <div class="d-flex mt-4 justify-content-end">
                {!! $products->links('pagination::bootstrap-5') !!}
            </div>
            <style>
                .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between {
    gap: 15px;
}
            </style>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function deleteProduct(id) {
                if(confirm('Are you sure to delete?')) {
                    document.getElementById('delete-form-'+id).submit();
                }
            }

            setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
            }, 3000);
            $(document).ready(function() {

            $('#search').on('keyup',function(){
                var search = $(this).val();
                $.ajax({
                    url: "{{ route('products.search') }}",
                    type: "GET",
                    data: {search:search},
                    success:function(data){
                        console.log(data);
                        $('#productsTable').html(data);
                    },
                    error:function(error){
                    alert('No product found!, Please try again');
                    }
                });
            })
            })
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
