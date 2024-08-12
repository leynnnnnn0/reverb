<?php

use App\Events\OrderStatus;
use App\Http\Controllers\ProfileController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


Route::post('/status-change/{id}', function($id){

    $validated = Validator::make(request()->all(), [
        'status' => 'required'
    ]);

    if($validated->fails()){
        return response()->json([
            'success' => false,
            'order_status' => request('status'),
            'order_id' => $id
        ], 422);
    }

    try {
        $order = Order::findOrFail($id);
        $order->status = $validated->safe()->only('status')['status'];
        $order->save();

        broadcast(new OrderStatus($order));

        return response()->json([
            'success' => true,
            'message' => 'Order status updated',
            'order' => $order
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 404);
    }


});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dragNdrop', function () {
    return view('dragNdrop');
});

Route::get('/dashboard', function () {
    $orders = Auth::user()->orders()->get();
    return view('dashboard', ['orders' => $orders]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
    $orders = Order::all();
    return view('admin', ['orders' => $orders]);
})->name('admin');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
