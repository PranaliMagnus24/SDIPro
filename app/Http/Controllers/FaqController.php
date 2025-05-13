<?php

namespace App\Http\Controllers;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all(); // Fetch all FAQs
        return view('faq.list', compact('faqs')); // Pass data to the index view
    }

    // Show the form for creating a new FAQ
    public function create()
    {
        return view('faq.create'); // Return the create form
    }

    // Store a newly created FAQ in the database
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:255',
    ]);

    Faq::create($request->all()); // Save the faqfaq to the database

    return redirect()->route('faqlist')->with('success', 'faq created successfully.');
}

    // Show the form for editing an existing FAQ
    public function edit($id)
    {
        $faq = Faq::findOrFail($id); // Find the FAQ by ID
        return view('faq.edit', compact('faq')); // Pass data to the edit view
    }

    // Update an existing faq
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        $faq = Faq::findOrFail($id); // Find the faq by ID
        $faq->update($request->all()); // Update the faq data

        return redirect()->route('faqlist')->with('success', 'faq updated successfully.');
    }

    // Delete an faq
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id); // Find the faq by ID
        $faq->delete(); // Delete the faq

        return redirect()->route('faqlist')->with('success', 'faq deleted successfully.');
    }
}
