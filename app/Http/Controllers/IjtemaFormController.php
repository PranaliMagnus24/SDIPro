<?php

namespace App\Http\Controllers;
use App\Models\IjtemaForm;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;

class IjtemaFormController extends Controller
{
    public function index(Request $request)
    {
        $query = IjtemaForm::with('cities'); // Eager load city data

        // Apply filters if any
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        if ($request->filled('contact')) {
            $query->where('contact', 'like', '%' . $request->contact . '%');
        }
    
        if ($request->filled('city')) {
            $query->where('city_id', $request->city); // Using city_id here (assuming it's the correct column in the table)
        }
    
      
        $forms = $query->paginate(10); 
    
        // Fetch cities for the filter dropdown
        $cities = City::where('state_id', 1)->get();   // Fetch all FAQs
        return view('ijtema.formlist', compact('forms', 'cities')); // Pass data to the index view
    }

    // Show the form for creating a new FAQ
    public function create()
    {
        // Fetch cities where the associated state is Maharashtra
        $cities = City::whereHas('state', function ($query) {
            $query->where('name', 'Maharashtra'); // Assuming the state name is stored in 'name' column of the 'states' table
        })->get();
    
        return view('ijtema.addform', compact('cities')); // Return the create form
    }

    // Store a newly created FAQ in the database
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|numeric|max:255',
            'gender' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',  // Email is optional
            'contact' => 'required|numeric|digits:10|unique:ijtemaform,contact',
            'city' => 'required|exists:cities,id', // Validate city exists in the database (ensure you have the proper city table)
            'note' => 'nullable|string|max:255',
        ]);
    
        // Ensure the city is from Maharashtra. Assuming you have a 'state' column in the 'cities' table
        $city = City::find($request->city);
        if ($city && $city->state && $city->state->name !== 'Maharashtra') {
        return redirect()->back()->with('error', 'The selected city must be from Maharashtra.');
    }

    // Save the form to the database
    IjtemaForm::create($request->all());
        // Redirect with success message
        return redirect()->route('thankyou');
    }
 
    public function edit($id)
    {
        $cities = City::all();
        $form = IjtemaForm::findOrFail($id); // Find the FAQ by ID
        return view('ijtema.formedit', compact('form', 'cities')); // Pass data to the edit view
    }

    // Update an existing form
    public function update(Request $request, $id)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'age' => 'nullable|string|max:255',
        'gender' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'contact' => 'required|string|max:255',
       'city' => 'required|string|max:255',
        'note' => 'nullable|string|max:255',
        ]);

        $form = IjtemaForm::findOrFail($id); // Find the form by ID
        $form->update($request->all()); // Update the form data

        return redirect()->route('formlist')->with('success', 'form updated successfully.');
    }

    // Delete an form
    public function destroy($id)
    {
        $form = IjtemaForm::findOrFail($id); // Find the form by ID
        $form->delete(); // Delete the form

        return redirect()->route('formlist')->with('success', 'form deleted successfully.');
    }
    public function view($id)
{
    $form = IjtemaForm::findOrFail($id);
    return view('ijtema.view', compact('form'));
}
// Controller Method
public function checkContact(Request $request)
{
    $contact = $request->input('contact');
    $exists = IjtemaForm::where('contact', $contact)->exists();  // Check if contact exists
    return response()->json(['exists' => $exists]);
}


public function thankyou()
{
    return view('ijtema.thankyou');
}

}
