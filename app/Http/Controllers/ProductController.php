<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getdata()
    {
        $data = [
            ['id' => 1, 'produk' => 'Laptop'],
            ['id' => 2, 'produk' => 'Mouse'],
            ['id' => 3, 'produk' => 'Keyboard'],
        ];

        return view('List_Product', compact('data'));
    }
}