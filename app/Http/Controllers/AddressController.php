<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     */
    public function index(Request $request)
    {
        $addresses = Address::where('user_id', $request->user()->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($addresses);
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other defaults
        if (isset($validated['is_default']) && $validated['is_default']) {
            Address::where('user_id', $request->user()->id)
                ->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json([
            'message' => 'Alamat berhasil ditambahkan',
            'address' => $address,
        ], 201);
    }

    /**
     * Display the specified address.
     */
    public function show(Request $request, $id)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($address);
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'label' => 'sometimes|string|max:255',
            'recipient_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string|max:255',
            'province' => 'sometimes|string|max:255',
            'postal_code' => 'sometimes|string|max:10',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other defaults
        if (isset($validated['is_default']) && $validated['is_default']) {
            Address::where('user_id', $request->user()->id)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return response()->json([
            'message' => 'Alamat berhasil diupdate',
            'address' => $address->fresh(),
        ]);
    }

    /**
     * Remove the specified address.
     */
    public function destroy(Request $request, $id)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $address->delete();

        return response()->json([
            'message' => 'Alamat berhasil dihapus',
        ]);
    }

    /**
     * Set an address as default.
     */
    public function setDefault(Request $request, $id)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        // Unset all other defaults
        Address::where('user_id', $request->user()->id)
            ->update(['is_default' => false]);

        // Set this one as default
        $address->update(['is_default' => true]);

        return response()->json([
            'message' => 'Alamat default berhasil diubah',
            'address' => $address->fresh(),
        ]);
    }
}
