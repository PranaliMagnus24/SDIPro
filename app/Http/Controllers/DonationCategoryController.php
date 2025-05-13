<?php

namespace App\Http\Controllers;
use App\Models\DonationCategory;
use Illuminate\Http\Request;

class DonationCategoryController extends Controller
{
    public function index()
    {
        $categories = DonationCategory::all(); // Fetch all FAQs
        return view('donation.categorylist', compact('categories')); // Pass data to the index view
    }

    // Show the form for creating a new FAQ
    public function create()
    {
        return view('donation.category'); // Return the create form
    }

    // Store a newly created FAQ in the database
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    DonationCategory::create($request->all()); // Save the categorycategory to the database

    return redirect()->route('categorylist')->with('success', 'category created successfully.');
}

    // Show the form for editing an existing FAQ
    public function edit($id)
    {
        $category = DonationCategory::findOrFail($id); // Find the FAQ by ID
        return view('donation.categoryedit', compact('category')); // Pass data to the edit view
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $category = DonationCategory::findOrFail($id); // Find the category by ID
        $category->update($request->all()); // Update the category data

        return redirect()->route('categorylist')->with('success', 'category updated successfully.');
    }

    // Delete an category
    public function destroy($id)
    {
        $category = DonationCategory::findOrFail($id); // Find the category by ID
        $category->delete(); // Delete the category

        return redirect()->route('categorylist')->with('success', 'category deleted successfully.');
    }
}
