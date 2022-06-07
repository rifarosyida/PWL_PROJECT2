<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierControlller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Supplier::all(); // Mengambil semua isi tabel
        $all_supplier = Supplier::orderBy('id', 'asc')->paginate(5);
        return view('admin.datasupplier', ['supplier' => $supplier, 'all_supplier' => $all_supplier]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.supplier.createSupplier');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_supplier' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable',
        ]);

        if($request->file('gambar')){
            $validatedData['gambar'] = $request->file('gambar')->store('images', 'public');
        }

        Supplier::create($validatedData);

        return redirect()->route('supplier.index')
        ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::where('id', $id)->first();

        return view('admin.supplier.detailSupplier',[
            'supplier' => $supplier
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::where('id', $id)->first();
         return view('admin.supplier.editSupplier', [
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules= [
            'nama_supplier' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable',
        ];

        $validatedData = $request->validate($rules);

        Supplier::where('id', $supplier->id)
        ->update($validatedData);

        return redirect()->route('supplier.index')
        ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if (File::exists('storage/'.$supplier->gambar)) {
            File::delete('storage/'.$supplier->gambar);
        }

        Supplier::find($supplier->id)->delete();
        return redirect()->route('supplier.index')
            ->with('success','Produk berhasil dihapus');
    }
}
