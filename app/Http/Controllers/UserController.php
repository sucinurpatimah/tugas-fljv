<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function callSession(Request $request)
    {
        return redirect()->back()->with('status', 'Berhasil memanggil sesi');
    }

    public function getProduct()
    {
        $data = Product::all();
        // $user = User::find('id');
        // $data = $user->products;
        // return view('list_product')->with('products', $data);
        return view('index')->with('products', $data);
    }

    public function getProfile(Request $request, User $user)
    {
        $user = User::with('summarize')->find($user->id);
        // dd($user);
        return view('profile', ['user' => $user]);
    }

    public function getAdmin(User $user)
    {
        $products = Product::where('user_id', $user->id)->get();
        return view('admin_page', ['products' => $products, 'user' => $user]);
    }

    public function editProduct(Request $request, User $user, Product $product)
    {
        return view('edit_product', ['product' => $product, 'user' => $user]);
    }

    public function updateProduct(Request $request, User $user, Product $product)
    {
        if (!$request->filled('image')) {
            return redirect()->back()->with('error', 'Error. Field Gambar wajib diisi.');
        } else if (!$request->filled('nama')) {
            return redirect()->back()->with('error', 'Error. Field Nama wajib diisi.');
        } else if (!$request->filled('berat')) {
            return redirect()->back()->with('error', 'Error. Field Berat wajib diisi.');
        } else if (!$request->filled('harga')) {
            return redirect()->back()->with('error', 'Error. Field Harga wajib diisi.');
        } else if (!$request->filled('stok')) {
            return redirect()->back()->with('error', 'Error. Field Stok wajib diisi.');
        } else if ($request->kondisi === 0) {
            return redirect()->back()->with('error', 'Error. Field Kondisi wajib diisi.');
        } else if (!$request->filled('deskripsi')) {
            return redirect()->back()->with('error', 'Error. Field Deskripsi wajib diisi.');
        }

        if ($product->user_id === $user->id) {
            $product->name = $request->nama;
            $product->stock = $request->stok;
            $product->weight = $request->berat;
            $product->price = $request->harga;
            $product->description = $request->deskripsi;
            $product->condition = $request->kondisi;
            $product->image = $request->image;
            $product->save();
        }

        return redirect()->route('admin_page', ['user' => $user->id])->with('message', 'Data Berhasil Diupdate!');
    }

    public function deleteProduct(Request $request, User $user, Product $product)
    {
        if ($product->user_id === $user->id) {
            $product->delete();
        }
        return redirect()->back()->with('status', 'Data Berhasil Dihapus!');
    }

    public function getFormRequest()
    {
        return view('form_request');
    }

    public function sendRequest(Request $request)
    {
        return view('send_request');
    }


    public function handleRequest(Request $request, User $user)
    {
        return view('handle_request', ['user' => $user]);
    }

    public function postRequest(Request $request, User $user)
    {
        if (!$request->filled('image')) {
            return redirect()->back()->with('error', 'Error. Field Gambar wajib diisi.');
        } else if (!$request->filled('nama')) {
            return redirect()->back()->with('error', 'Error. Field Nama wajib diisi.');
        } else if (!$request->filled('berat')) {
            return redirect()->back()->with('error', 'Error. Field Berat wajib diisi.');
        } else if (!$request->filled('harga')) {
            return redirect()->back()->with('error', 'Error. Field Harga wajib diisi.');
        } else if (!$request->filled('stok')) {
            return redirect()->back()->with('error', 'Error. Field Stok wajib diisi.');
        } else if ($request->kondisi === 0) {
            return redirect()->back()->with('error', 'Error. Field Kondisi wajib diisi.');
        } else if (!$request->filled('deskripsi')) {
            return redirect()->back()->with('error', 'Error. Field Deskripsi wajib diisi.');
        }

        Product::create([
            'user_id' => $user->id,
            'image' => $request->image,
            'name' => $request->nama,
            'weight' => $request->berat,
            'price' => $request->harga,
            'condition' => $request->kondisi,
            'stock' => $request->stok,
            'description' => $request->deskripsi,
        ]);

        // return redirect()->route('get_product');
        return redirect()->route('admin_page', ['user' => $user->id]);
    }

    public function storeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        // Upload image
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('/public/images'), $imageName);

        // Save product
        Product::create([ // Menggunakan model Produk
            'image' => $imageName,
        ]);

        return redirect()->route('index');
    }

    public function updateImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            // Menghapus gambar lama jika ada
            Product::delete($product->image);

            // Menyimpan gambar baru
            $imagePath = $request->file('image')->store('public/images');
            return $imagePath;
        } else {
            // Jika tidak ada perubahan gambar, kembalikan path gambar yang sudah ada
            return $product->image;
        }
    }

    public function deleteImage(Product $product)
    {
        Product::delete($product->image);
    }
}
