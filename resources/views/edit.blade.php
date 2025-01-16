
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        {{-- {{$errors}} --}}
        <div class="container my-5 border border-1 border-opacity-25 p-4 rounded">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Update Product</h1>
                <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
            <form action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$product->name}}" placeholder="Enter product name">
                    @error('name')
                        <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" value="{{$product->price}}" name="price" placeholder="Enter product price">
                    @error('price')
                        <p class="invalid-feedback">{{$message}}</p>

                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label><input type="number" value="{{$product->stock}}" class="form-control @error('stock') is-invalid @enderror"
                        name="stock">
                        @error('stock')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label><textarea placeholder="Enter product description..."
                        class="form-control @error('description') is-invalid @enderror" name="description" rows="5">{{$product->description}} </textarea>
                        @error('description')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label><input type="file" class="form-control @error('image') is-invalid @enderror"
                        name="image"  placeholder="Image">

                        @error('image')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror

                        <img src="{{asset('uploads/products/'.$product->image)}}" class="mt-2" width="100" height="100">
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary">Update</button>
                </div>

            </form>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
