<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCompany;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompaniesController extends Controller
{
    public function store(AddCompany $request)
    {
        //Request Data validation
        $validatedData = $request->validated();

        //Create User
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'role' => 1,
            'password' =>  Hash::make($validatedData['password']),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $userId = $user->id;
        //Create Company On User Created
        if ($user) {
            $company = Company::create([
                'company_name' => $validatedData['company_name'],
                'address' => $validatedData['address'],
                'country' => $validatedData['country'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'zip' => $validatedData['pincode'],
            ]);

            if ($company) {
                User::where('id',$userId)->update(['company_id' => $company->id]);
                return response()->json(['success' => true, 'message' => 'Company created successfully']);
            } else {
                // Handle the case where Company creation fails
                $user->delete(); // Rollback the user creation
                return response()->json(['error' => 'Failed to create Company'], 500);
            }
        } else {
            // Handle the case where User creation fails
            return response()->json(['error' => 'Failed to create User'], 500);
        }
    }
}
