<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::with(['inventaris.barang'])->get();
        return view('pemakaian.index',[
            'cart' => $cart,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_inventaris = Inventaris::where('id_barang', $request->id_barang)->where('id_ruangan', $request->id_ruangan)->first();
        // dd($id_inventaris);
        // dd($request); 
        $request->validate([
            'id_barang' => 'required',
            'id_ruangan' => 'required',
            'jumlah_barang' => 'required',
            'keterangan_pemakaian' => 'nullable',
        ]);
        $cart = new Cart();
        $cart->id_inventaris = $id_inventaris->id_inventaris;
        if($request->jumlah_barang <= $id_inventaris->jumlah_barang){
            $cart->jumlah_barang = $request->jumlah_barang;
        } else{
            return redirect()->back()->with(['error' => 'Jumlah barang melebihi stok.',]);
        }
        $cart->keterangan_pemakaian = $request->keterangan_pemakaian;
        $cart->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_cart)
    {
        $cart = Cart::find($id_cart);
        if ($cart) {
            $cart->delete();
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }

    public function ButtonBatal(){
        Cart::truncate();
        return redirect('/pemakaian');

        // return redirect('/pemakaian');
    }
}