<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Details</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Product Details</h1>
                <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
            <div class="card shadow-lg">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{asset('uploads/products/'.$product->image)}}"  class="img-fluid rounded-start"
                            alt="Product Image">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h2 class="card-title" id="productName">{{ $product->name }}</h2>
                            <h4 class="text-primary">Price: {{ $product->price }}</h4>
                            <h4 class="text-primary">Stock: {{ $product->stock }}</h4>
                            <h4 class="text-primary">Product ID: {{ $product->product_id }}</h4>
                            <p class="card-text">
                                <strong>Details:</strong>
                                <span>
                                    {{ $product->description }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <a href="{{route('products.edit', $product->id)}}" class="btn btn-warning">Edit Product</a>
                <form action="{{route('products.destroy', $product->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
