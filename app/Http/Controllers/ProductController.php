<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller {
    public function index( Request $request ) {
        $sortBy = $request->get( 'sortBy', 'id' );
        $order = $request->get( 'order', 'asc' );
        if ( $order === 'desc' ) {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
        $products = Product::orderBy( $sortBy, $order )->paginate( 5 );
        return view( 'index', ['products' => $products] );
    }

    public function create() {
        return view( 'create' );
    }

    public function store( Request $request ) {
        $rules = [
            'name'        => 'required|min:3|max:30',
            'price'       => 'required|numeric',
            'stock'       => 'numeric',
            'description' => 'required|min:10 ',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ];
        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            return redirect()->route( 'products.create' )->withInput()->withErrors( $validator );
        }
        $product = new Product();
        $product->name = $request->name;
        $product->product_id = $product->name . strtoupper( Str::random( 8 ) );
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        // image
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $image->move( public_path( 'uploads/products' ), $imageName );
        $product->image = $imageName;
        $product->save();
        return redirect()->route( 'products.index' )->with( 'success', 'Product created successfully!' );
    }

    public function show( $id ) {
        $product = Product::find( $id );
        return view( 'show', ['product' => $product] );
    }

    public function edit( $id ) {
        $product = Product::find( $id );
        return view( 'edit', ['product' => $product] );
    }

    public function update( Request $request, $id ) {

        $rules = [
            'name'        => 'required|min:3|max:30',
            'price'       => 'required|numeric',
            'stock'       => 'nullable|numeric',
            'description' => 'required|min:10',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make( $request->all(), $rules );

        if ( $validator->fails() ) {
            return redirect()->route( 'products.edit', $id )
                ->withInput()
                ->withErrors( $validator );
        }

        $product = Product::findOrFail( $id );
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        if ( $request->hasFile( 'image' ) ) {
            if ( $product->image && File::exists( public_path( 'uploads/products/' . $product->image ) ) ) {
                File::delete( public_path( 'uploads/products/' . $product->image ) );
            }
            $image = $request->file( 'image' );
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move( public_path( 'uploads/products' ), $imageName );
            $product->image = $imageName;
        }

        $product->save();
        return redirect()->route( 'products.index' )->with( 'success', 'Product updated successfully!' );
    }

    public function destroy( $id ) {
        $product = Product::find( $id );
        File::delete( public_path( 'uploads/products/' . $product->image ) );
        $product->delete();
        return redirect()->route( 'products.index' )->with( 'success', 'Product deleted successfully!' );
    }

    public function search( Request $request ) {
        if ( $request->ajax() ) {
            $output = '';
            $products = Product::where( 'name', 'LIKE', '%' . $request->search . '%' )->orWhere( 'description', 'LIKE', '%' . $request->search . '%' )->paginate( 5 );

            if ( $products->isNotEmpty() ) {
                $output = ''; // Initialize the $output variable

                foreach ( $products as $product ) {
                    $output .= '<tr>' .
                    '<td>' . $product->id . '</td>' .
                    '<td>' . $product->name . '</td>' .
                    '<td><img src="' . asset( 'uploads/products/' . $product->image ) . '" alt="' . $product->name . '" width="100"></td>' .
                    '<td>' . $product->description . '</td>' .
                    '<td>' . $product->price . '</td>' .
                    '<td>' .
                    '<div class="d-flex justify-content-center align-items-center gap-2">' .
                    '<a href="' . route( 'products.show', $product->id ) . '" class="btn btn-sm btn-primary">Show</a>' .
                    '<a href="' . route( 'products.edit', $product->id ) . '" class="btn btn-sm btn-warning">Edit</a>' .
                    '<a href="#" onclick="deleteProduct(' . $product->id . ')" class="btn btn-sm btn-danger">Delete</a>' .
                    '<form action="' . route( 'products.destroy', $product->id ) . '" method="POST" id="delete-form-' . $product->id . '">' .
                    csrf_field() .
                    method_field( 'DELETE' ) .
                        '</form>' .
                        '</div>' .
                        '</td>' .
                        '</tr>';
                }
                return response()->json( $output );
            } elseif ( $products->isEmpty() ) {
                return response()->json( '<tr></tr><td class="text-center" colspan="6">No results found.</td></tr>' );
            }
            return view( 'index' );
        }
    }
}
